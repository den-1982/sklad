<?php
/*
*	ADMIN
*/
class categoryModel extends CI_Model
{
	public function getCategories()
	{	
		return $this->db->query('SELECT 
									c.nm,
									cs.*
								FROM category_struct cs
								LEFT JOIN category c ON c.id = cs.id
								ORDER BY cs.pos ASC')->result();
	}
	
	public function getCategory($id = 0)
	{
		$id = abs((int)$id);
		
		return $this->db->query('SELECT * FROM category WHERE id = "'.$id.'"')->row();
	}
	
	public function getCategoryTree()
	{
		# cat
		$categories = $this->db->query('SELECT 
									c.nm,
									cs.*
								FROM category_struct cs
								LEFT JOIN category c ON c.id = cs.id
								ORDER BY cs.pos ASC')->result();
		# sort
		$sortCat = array();
		foreach ($categories as $category) {
			$sortCat[$category->pid][$category->id] = $category;
		}
		unset($category);
		unset($categories);
		
		# tree
		$createTree = function($parents, $not = 0, $parent_id = 1, $parent_name = '', $level = -1) use (&$createTree){
			$output = array();
			$level++;
			
			if (array_key_exists($parent_id, $parents)) {
				
				if ($parent_name != '') $parent_name .= ' > ';
				
				foreach ($parents[$parent_id] as $parent) {
					# избегаем зацыкливания
					if ($parent->id == $not) continue;
					
					$output[$parent->id] = array(
						'id'	=> $parent->id,
						'name'	=> $parent_name . $parent->nm,
						'_name'	=> $parent->nm,
						'level'	=> $level
					);
					
					$output += $createTree($parents, $not, $parent->id, $parent_name . $parent->nm, $level);
				}
			}
			
			return $output;
		};
		
		return $createTree($sortCat);
	}
	
	public function sortCategories($categories = array())
	{
		$data = array();
		foreach ($categories as $category) {
			$data[$category->pid][$category->id] = $category;
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