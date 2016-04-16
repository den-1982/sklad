<?php
class categoryModel extends CI_Model
{
	public function getCategories()
	{	
		return $this->db->query('SELECT 
									c.*, 
									(SELECT COUNT(*) FROM category WHERE parent = c.id) AS cnt_childs 
								FROM category c 
								ORDER BY c.order ASC')->result();
	}
	
	public function getCategory($id = 0)
	{
		$id = abs((int)$id);
		$category = $this->db->query('SELECT * FROM category WHERE id = "'.$id.'"')->row();
		
		return $category;
	}
	
	public function sortCategories($categories = array())
	{
		$data = array();
		foreach ($categories as $category) {
			$data[$category->parent][$category->id] = $category;
		}
		return $data;
	}
	
	public function addCategory()
	{
		$parent		= isset($_POST['parent'])	? abs((int)$_POST['parent']) : 0;
		$name       = isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		
		$this->db->query('INSERT INTO category (parent, name) 
							   VALUES (
								"'.$parent.'",  
								"'.$name.'"
							)');

		return;
	}
	
	public function updateCategory()
	{
		$id			= isset($_POST['id']) ? abs((int)$_POST['id']) : 0;
		$parent		= isset($_POST['parent']) ? abs((int)$_POST['parent']) : 0;
		$name       = isset($_POST['name'])		? clean($_POST['name'], true, true) : '';

		$this->db->query('UPDATE category 
							SET 
								parent	= "'.$parent.'",
								name	= "'.$name.'"
							WHERE id = "'.$id.'"');
		
		return;
	}
	
	public function sortOrderCategory()
	{
		if (isset($_POST['category_order'])){
			for ($i = 0, $cnt = count($_POST['category_order']); $i < $cnt; $i++){
				
				$category_id	= isset($_POST['category_id'][$i]) ? abs((int)$_POST['category_id'][$i]) : 0;
				$category_order = isset($_POST['category_order'][$i]) ? abs((int)$_POST['category_order'][$i]) : 0;
				
				$this->db->query('UPDATE category SET `order` = "'.$category_order.'" WHERE id = "'.$category_id.'"');
			}
		}
		
		return;
	}
	
	public function delCategory($id = 0)
	{
		$id = abs((int)$id);
		$res1 = $this->db->query('SELECT COUNT(*) AS cnt1 FROM category WHERE parent = '.$id)->row()->cnt1;
		$res2 = $this->db->query('SELECT COUNT(*) AS cnt2 FROM products WHERE parent = '.$id)->row()->cnt2;
		
		if ($res1 || $res2){
			return 'Категория имеет дочерние элементы!';
		}else{
			$this->db->query('DELETE FROM category WHERE id = "'.$id.'"');
			
			$dir = ROOT.'/img/categories/'.$id;
			
			if (is_dir($dir)){
				delDir($dir);
			}
		}
		
		return 0;
	}

}