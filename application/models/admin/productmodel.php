<?php
/*
*	ADMIN
*/
class productModel extends CI_Model
{
	public function getProductsOfCategories($ids = 0)
	{
		$ids = explode(':', $ids);
		
		$arrIds = array();
		foreach ($ids as $k){
			$arrIds[] =  abs((int) $k);
		}
		
		if ( ! count($arrIds)) return array();
		
		$products = $this->db->query('
			SELECT 
				p.*, 
				"image" AS image 
			FROM products p 
			WHERE p.category_id IN ('.join($arrIds, ',').') 
			ORDER BY p.order ASC
		')->result();
		
		return $products;
	}
	
	
	public function getProducts($parent = 0)
	{
		$parent = abs((int)$parent);
		return $this->db->query('SELECT 
										*,
										CONCAT("/img/products/", id, "/", id, "_82_82.jpg") AS image
									FROM products 
									WHERE parent = "'.$parent.'" 
									ORDER BY `order` ASC')->result();
	}
	
	public function getProduct($id = 0)
	{
		$product = $this->db->query('SELECT 
											*,
											CONCAT("/img/products/", id, "/", id, "_82_82.jpg") AS image
										FROM products 
										WHERE id = "'.$id.'"')->row();
		
		if ( ! $product) return array();

		# FILTER (NEW)
		$product->filter_item = array();
		$_filter_items = $this->db->query('SELECT id_filter_item FROM product_filter_item WHERE id_product = "'.$product->id.'"')->result();
		foreach ($_filter_items as $item){
			$product->filter_item[] = $item->id_filter_item;
		}
		
		# PRICES (NEW)
		$product->prices = $this->db->query('SELECT * FROM product_prices WHERE id_product = "'.$product->id.'"')->result();
		
		return $product;
	}
	
	public function addProduct()
	{
		$category_id	= isset($_POST['category_id'])	? abs((int)$_POST['category_id']) : 0;
		$name			= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$serial_number 	= isset($_POST['serial_number']) ? clean($_POST['serial_number'], true, true) : '';
		
		$this->db->query('INSERT INTO products (category_id, name, serial_number) 
							   VALUES (
								"'.$category_id.'", 
								"'.$name.'",  
								"'.$serial_number.'"
							)');
		
		# ID
		$id = $this->db->query('SELECT MAX(id) AS id FROM products LIMIT 1')->row()->id;
		
		# FILTER_ITEM (id_filter_item) значения фильтров
		if (isset($_POST['id_filter_item'])) foreach ($_POST['id_filter_item'] as $k){
			$id_filter_item = abs((int)$k);
			
			$this->db->query('INSERT INTO product_filter_item (id_product, id_filter_item) 
								VALUES(
									"'.$id.'", 
									"'.$id_filter_item.'"
								)');
		}
		
		# PRICES добавляем список цен
		if (isset($_POST['id_filter_item_price'])) foreach ($_POST['id_filter_item_price'] as $k=>$v){
			$id_filter_item	= isset($_POST['id_filter_item_price'][$k]) ? abs((int)$_POST['id_filter_item_price'][$k]) : 0;
			
			# добавляем в product_filter_item т.к. это значение фильтра
			$this->db->query('INSERT INTO product_filter_item (id_product, id_filter_item) 
								VALUES(
									"'.$id.'", 
									"'.$id_filter_item.'"
								)');
			
			$cnt_opt  = isset($_POST['cnt_opt'][$k]) ? clean($_POST['cnt_opt'][$k], true, true) : 0;
			$cnt_roz  = isset($_POST['cnt_roz'][$k]) ? clean($_POST['cnt_roz'][$k], true, true) : 0;
			
			$usa_opt  = isset($_POST['usa_opt'][$k]) ? abs((float)$_POST['usa_opt'][$k]) : 0;
			$usa_roz  = isset($_POST['usa_roz'][$k]) ? abs((float)$_POST['usa_roz'][$k]) : 0;
			
			$opt  = isset($_POST['opt'][$k]) ? abs((float)$_POST['opt'][$k]) : 0;
			$roz  = isset($_POST['roz'][$k]) ? abs((float)$_POST['roz'][$k]) : 0;
			
			# если цены в грн. не указаны
			$opt = !$opt ? $usa_opt * $course : $opt;
			$roz = !$roz ? $usa_roz * $course : $roz;

			$discount = isset($_POST['discount'][$k]) ? abs((float)$_POST['discount'][$k]) : 0;
			
			if ( ! $id_filter_item) continue;

			$this->db->query('INSERT INTO product_prices (id_product, id_filter_item, cnt_opt, cnt_roz, opt, roz, usa_opt, usa_roz, discount) 
								VALUES (
									"'.$id.'", 
									"'.$id_filter_item.'", 
									"'.$cnt_opt.'", 
									"'.$cnt_roz.'", 
									"'.$opt.'", 
									"'.$roz.'", 
									"'.$usa_opt.'", 
									"'.$usa_roz.'", 
									"'.$discount.'"
								)');
		}
		
		return true;
	}
	
	public function updateProduct()
	{
		$id				= isset($_POST['product_id']) 		? abs((int)$_POST['product_id']) : 0;
		$category_id	= isset($_POST['category_id'])		? abs((int)$_POST['category_id']) : 0;
		$name			= isset($_POST['name'])				? clean($_POST['name'], true, true) : '';
		$serial_number 	= isset($_POST['serial_number'])	? clean($_POST['serial_number'], true, true) : '';

		
		$this->db->query('UPDATE products 
							SET 
								category_id		= "'.$category_id.'",
								name			= "'.$name.'",
								serial_number	= "'.$serial_number.'"
							WHERE id = "'.$id.'"');
		
		# FILTER_ITEM (id_filter_item) значения фильтров
		$this->db->query('DELETE FROM product_filter_item WHERE id_product = '.$id);
		if (isset($_POST['id_filter_item'])){
			foreach ($_POST['id_filter_item'] as $k){
				$id_filter_item = abs((int)$k);
				
				$this->db->query('INSERT INTO product_filter_item (id_product, id_filter_item) 
									VALUES(
										"'.$id.'", 
										"'.$id_filter_item.'"
									)');
			}
		}
		
		# PRICES добавляем список цен
		$this->db->query('DELETE FROM product_prices WHERE id_product = '. $id);
		if (isset($_POST['id_filter_item_price'])){
			foreach ($_POST['id_filter_item_price'] as $k=>$v){
				$id_filter_item	= isset($_POST['id_filter_item_price'][$k]) ? abs((int)$_POST['id_filter_item_price'][$k]) : 0;
				
				# добавляем в product_filter_item т.к. это значение фильтра
				$this->db->query('INSERT INTO product_filter_item (id_product, id_filter_item) 
									VALUES(
										"'.$id.'", 
										"'.$id_filter_item.'"
									)');
				
				$cnt_opt  = isset($_POST['cnt_opt'][$k]) ? clean($_POST['cnt_opt'][$k], true, true) : 0;
				$cnt_roz  = isset($_POST['cnt_roz'][$k]) ? clean($_POST['cnt_roz'][$k], true, true) : 0;
				
				$usa_opt  = isset($_POST['usa_opt'][$k]) ? abs((float)$_POST['usa_opt'][$k]) : 0;
				$usa_roz  = isset($_POST['usa_roz'][$k]) ? abs((float)$_POST['usa_roz'][$k]) : 0;
				
				$opt  = isset($_POST['opt'][$k]) ? abs((float)$_POST['opt'][$k]) : 0;
				$roz  = isset($_POST['roz'][$k]) ? abs((float)$_POST['roz'][$k]) : 0;
				
				# если цены в грн. не указаны
				$opt = !$opt ? $usa_opt * $course : $opt;
				$roz = !$roz ? $usa_roz * $course : $roz;

				$discount = isset($_POST['discount'][$k]) ? abs((float)$_POST['discount'][$k]) : 0;
				
				//if ( ! $id_filter_item) continue;

				$this->db->query('INSERT INTO product_prices (id_product, id_filter_item, cnt_opt, cnt_roz, opt, roz, usa_opt, usa_roz, discount) 
									VALUES (
										"'.$id.'", 
										"'.$id_filter_item.'", 
										"'.$cnt_opt.'", 
										"'.$cnt_roz.'", 
										"'.$opt.'", 
										"'.$roz.'", 
										"'.$usa_opt.'", 
										"'.$usa_roz.'", 
										"'.$discount.'"
									)');
			}
		}						

		return true;
	}
	
	public function sortOrderProduct()
	{
		if (isset($_POST['product_order'])) foreach ($_POST['product_order'] as $k=>$v){
			$product_id		= isset($_POST['product_id'][$k])	? abs((int)$_POST['product_id'][$k]) : 0;
			$product_order	= isset($_POST['product_order'][$k])? abs((int)$_POST['product_order'][$k]) : 0;
			
			$this->db->query('UPDATE products SET `order` = "'.$product_order.'" WHERE id = "'.$product_id.'"');
		}
		
		return;
	}
	
	public function delProduct($id = 0)
	{
		$id = abs((int)$id);
		$this->db->query('DELETE FROM products WHERE id = "'.$id.'"');

		return true;
	}
	
}