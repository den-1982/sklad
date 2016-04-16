<?php
/*
*	ADMIN
*/
class novaposhtaModel extends CI_Model
{
	private $api_key = '0166186b16d0e49771a0aebf25ac4770';
	
	public function getInvoiceNovaPoshta($cargo_number = '')
	{
		//$cargo_number = '59000105822136';
		$cargo_number = preg_replace('/[^0-9]/', '', $cargo_number);
		
		$getElementsByClassName = function (DOMDocument $dom, $className) {
			$elements = $dom->getElementsByTagName('*');
			$matches = array();
			foreach($elements as $element) {
				if ( ! $element->hasAttribute('class')) continue;

				$classes = preg_split('/\s+/', $element->getAttribute('class'));
				
				if ( ! in_array($className, $classes)) continue;

				$matches[] = $element;
			}
			return $matches;
		};
		
		
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: ru\r\n" .
				"Cookie: language=ru\r\n"
			)
		);
		$context = stream_context_create($opts);
		$file = file_get_contents('http://novaposhta.ua/tracking/?cargo_number='.$cargo_number, false, $context);
		
		$dom = new DOMDocument();
		@$dom->loadHTML($file);
		$result = $getElementsByClassName($dom, 'response');
		
		return isset($result[0]) ? $dom->saveHTML($result[0]) : '<p style="color:red;">Номер не найден. Пожалуйста, проверьте правильнность указаного номера.<p>';
	}
	
	/* NEW FUNCTIONS */
	public function getOfficeNovaPoshta($ref = ''){
		$ref = clean($ref, true, true);
		
		return $this->db->query('SELECT * FROM novaposhta WHERE ref LIKE "'.$ref.'"')->row();
	}
	
	public function getOfficesNovaPoshta()
	{
		$result = $this->db->query('SELECT 
										ref,
										city_ref,
										cityRu,
										addressRu,
										phone,
										number,
										x,
										y
									FROM novaposhta ORDER BY number ASC')->result();
		
		$data['response'] = array();
		foreach ($result as $k){
			$data['response'][$k->city_ref][$k->ref] = $k;
		}
		
		return $data;
	}
	
	public function getBigCitiesNovaPoshta()
	{
		$res = array();
		$res['response'] = $this->db->query('SELECT 
												cityRu, 
												city_ref, 
												COUNT(*) AS cnt 
											FROM novaposhta 
											GROUP BY city_ref 
											HAVING cnt > 2 
											ORDER BY cityRu ASC;')->result();
		return $res;
	}
	
	public function getCitiesNovaPoshta()
	{
		$res = array();
		$res['response'] = $this->db->query('SELECT 
													cityRu, 
													city_ref 
												FROM novaposhta 
												GROUP BY city_ref 
												ORDER BY cityRu ASC')->result();
		return $res;
	}


	
/////////////////////////////////////////////////////////////////////////////////////////////////// OLD	
	private function sendRequest($xml) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://orders.novaposhta.ua/xml.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
	
	private function ApiNovaPoshta_getWarenhouse() 
	{
		$xml = "
		<?xml version=\"1.0\" encoding=\"utf-8\"?>
		<file>
			<auth>{$this->data['settings']->api_key_novaposhta}</auth>
			<warenhouse/>
		</file>";
		
        $response = $this->sendRequest($xml);
		if ( ! $response){
			log_message('error', 'Ошибка "curl_exec" Новая-Почта');
		}
		
		return $response;
	}
	
	public function refreshNovaPoshta() 
	{
		$response = $this->ApiNovaPoshta_getWarenhouse();
		$response = simplexml_load_string($response);
		
		if ( !$response || $response->responseCode != '200'){
			log_message('error', 'Ошибка загрузки БД Новая-Почта');
			return 'Ошибка загрузки БД Новая-Почта';
		}

		$cols	= array();
		$insert = array();
		$wrap	= function($data = ''){
			return $data ? '"'.mysql_real_escape_string($data).'"' : '""';
		};
		
		# название колонок
		foreach ($response->result->whs->warenhouse[0] as $k=>$v){
			$cols[] = $k . ' TEXT ';
		}
		# Создание таблицы
		$this->db->query('CREATE TABLE IF NOT EXISTS novaposhta ('.implode(',', $cols).')');

		# добавляем значения
		foreach ($response->result->whs->warenhouse as $item){
			$insert[] = '('. implode(', ', array_map($wrap, array_values((array)$item))) .')';
		}
		# очищаем таблицу и добавл. нов. значения
		$this->db->query('DELETE FROM novaposhta');
		$this->db->query('INSERT novaposhta VALUES '.implode(',', $insert).' ');
		
		return;
    }

	
}