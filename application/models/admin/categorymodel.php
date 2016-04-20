<?php
/*
*	ADMIN
*/
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

	public function delCategory($id = 0)
	{
		$id = abs((int)$id);
		$categories	= $this->db->query('SELECT COUNT(*) AS cnt FROM category_struct WHERE pid = "'.$id.'"')->row()->cnt;
		$products	= $this->db->query('SELECT COUNT(*) AS cnt FROM products WHERE category_id = "'.$id.'"')->row()->cnt;
		
		$response = array();
		
		if ($categories)
			$response[] = 'Категория имеет дочерние категории ('.$categories.' шт.)';
		
		if ($products)
			$response[] = 'Категория имеет вложенные товары ('.$products.' шт.)';
		
		
		return $response;
	}

}