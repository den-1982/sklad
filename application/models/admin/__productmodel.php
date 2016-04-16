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
			SELECT *, "image" AS image FROM products WHERE parent IN ('.join($arrIds, ',').')
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
		$parent		= isset($_POST['parent'])	? abs((int)$_POST['parent']) : 0;
		$name		= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$manufacturer = isset($_POST['manufacturer']) ? abs((int)$_POST['manufacturer']) : 0;
		
		$price 		= isset($_POST['price'])			? abs(str_replace(',', '.', $_POST['price'])*1) : 0;
		$price_usa	= isset($_POST['price_usa'])		? abs(str_replace(',', '.', $_POST['price_usa'])*1) : 0;

		# COURSE курс доллара данной категории
		$course = $this->db->query('SELECT course FROM category WHERE id = '.$parent)->row();
		$course = isset($course->course) ? $course->course : 0;
		# если есть цена в $ создаем цену в ua
		$price = !$price ? $price_usa * $course : $price;
		
		$this->db->query('INSERT INTO products (parent, name, price, price_usa, manufacturer) 
							   VALUES (
								"'.$parent.'", 
								"'.$name.'",  
								"'.$price.'", 
								"'.$price_usa.'", 
								"'.$manufacturer.'"
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
		
		# IMAGE
		$dir = ROOT.'/img/products/'.$id.'/';
		if ( ! is_dir($dir)){ mkdir($dir, 0755, true);}
		
		if (isset($_FILES['image'])){
			$img = $_FILES['image'];
			if ($img['error'] == 0){
				
				$i		= $dir.$id.'.jpg';
				$i82	= $dir.$id.'_82_82.jpg';
				$i150	= $dir.$id.'_150_150.jpg';
				$i300	= $dir.$id.'_300_300.jpg';

				add_watermark_image($img['tmp_name'], $i);
				
				$this->my_imagemagic->resize_square($img['tmp_name'], $i300, 300);
				add_watermark_image($i300, $i300);
				
				$this->my_imagemagic->resize_square($img['tmp_name'], $i150, 150);
				add_watermark_image($i150, $i150);
				
				$this->my_imagemagic->resize_square($img['tmp_name'], $i82, 82);
			}
		}
		
		return;
	}
	
	public function updateProduct()
	{
		$id			= isset($_POST['id']) ? abs((int)$_POST['id']) : 0;
		$parent		= isset($_POST['parent']) ? abs((int)$_POST['parent']) : 0;
		$name		= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$manufacturer = isset($_POST['manufacturer']) ? abs((int)$_POST['manufacturer']) : 0;
		
		$price 		= isset($_POST['price']) ? abs((float) str_replace(',', '.', $_POST['price'])) : 0;
		$price_usa	= isset($_POST['price_usa']) ? abs((float) str_replace(',', '.', $_POST['price_usa'])) : 0;
		
		# COURSE курс доллара данной категории
		$course = $this->db->query('SELECT course FROM category WHERE id = '.$parent)->row();
		$course = isset($course->course) ? $course->course : 0;
		$price = !$price ? $price_usa * $course : $price;
		
		$res = $this->db->query('UPDATE products 
									SET 
										parent	= "'.$parent.'",
										name	= "'.$name.'", 
										price	= "'.$price.'",
										price_usa = "'.$price_usa.'",
										manufacturer = "'.$manufacturer.'"
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
		
		# IMAGE
		$dir = ROOT.'/img/products/'.$id.'/';
		if ( ! is_dir($dir)){ mkdir($dir, 0755, true);}
		
		if (isset($_FILES['image'])){
			$img = $_FILES['image'];
			if ($img['error'] == 0){
				$i		= $dir.$id.'.jpg';
				$i82	= $dir.$id.'_82_82.jpg';
				$i150	= $dir.$id.'_150_150.jpg';
				$i300	= $dir.$id.'_300_300.jpg';
				
				add_watermark_image($img['tmp_name'], $i);
				
				$this->my_imagemagic->resize_square($img['tmp_name'], $i300, 300);
				add_watermark_image($i300, $i300);
				
				$this->my_imagemagic->resize_square($img['tmp_name'], $i150, 150);
				add_watermark_image($i150, $i150);
				
				$this->my_imagemagic->resize_square($img['tmp_name'], $i82, 82);
			}
		}

		return;
	}
	
	public function sortOrderProduct()
	{
		if (isset($_POST['product_order'])){
			for ($i = 0, $cnt = count($_POST['product_order']); $i < $cnt; $i++){
				$product_id		= isset($_POST['product_id'][$i])	? abs((int)$_POST['product_id'][$i]) : 0;
				$product_order	= isset($_POST['product_order'][$i])? abs((int)$_POST['product_order'][$i]) : 0;
				
				$this->db->query('UPDATE products SET `order` = "'.$product_order.'" WHERE id = "'.$product_id.'"');
			}
		}
		
		return;
	}
	
	public function delProduct($id = 0)
	{
		$id = abs((int)$id);
		$this->db->query('DELETE FROM products WHERE id = "'.$id.'"');
		
		$dir = ROOT.'/img/products/'.$id;
		
		if (is_dir($dir)){
			delDir($dir);
		}

		return 0;
	}
	
}