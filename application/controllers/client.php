<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller 
{
	public $data = array();
						 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helpers('functions');
		
		$this->load->model(
			array(
				
			)
		);
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// __VIEW
	private function _view($type = 'index', $data = array())
	{
		$this->load->view('client/parts/header.php', $data);
		$this->load->view('client/'.$type.'.php', $data);
		$this->load->view('client/parts/footer.php');
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// __INFO
	private function _info($obj = null, $default = '')
	{
		if ( ! $obj) $obj = new stdClass();

		$this->data['h1']		= isset($obj->h1) ? $obj->h1 : $default;
		$this->data['name']		= isset($obj->name) ? $obj->name : $default;
		$this->data['title']	= isset($obj->title) ? $obj->title : $default;
		$this->data['metadesc']	= isset($obj->metadesc) ? $obj->metadesc : $default;
		$this->data['metakey']	= isset($obj->metakey) ? $obj->metakey : $default;
		$this->data['text']		= isset($obj->text) ? $obj->text : '';
		$this->data['spam']		= isset($obj->spam) ? $obj->spam : '';
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// __CRUMBS
	private function _crumbs($data = array(), $parent = 0)
	{
		static $j = 0;
		
		$crumbs = array();
		do{
			$terac = false;
			foreach ($data as  $item){
				foreach ($item as $i){
					if ($i->id == $parent){
						$crumbs[] = array('id'=>$i->id, 'name'=>$i->name, '_url'=>$i->_url);
						$parent = $i->parent;
						$terac = true;
						break;
					}
				}	
			}
			
			# предохранитель (избегаем зацыкливания)
			$j++;
			if ($j > 100000)return;
			# =====================================
			
		}while ($terac);

		return array_reverse($crumbs);
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// INDEX	
	public function Index()
	{
		$data = &$this->data;
		
		redirect('/admin/');
		return;
		
		$this->_view('home', $data);
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 404
	public function _404()
	{	
		header("HTTP/1.0 404 Not Found");
		$data = &$this->data;
		
		$this->_view('404', $data);
	}

}