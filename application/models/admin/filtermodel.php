<?php
/*
*	ADMIN
*/
class filterModel extends CI_Model
{
	
	public function getFilter($id = 0)
	{
		$id = abs((int)$id);
		$filter = $this->db->query('SELECT * FROM filter WHERE id = "'.$id.'"')->row();
		
		if ( ! $filter) return array();
		
		# ITEMS
		$filter->items = $this->db->query('SELECT * 
												FROM filter_item 
												WHERE id_filter = "'.$filter->id.'" 
												ORDER BY `order` ASC')->result();
		
		return $filter;
	}
	
	public function getFilters()
	{
		$filters = $this->db->query('SELECT * FROM filter ORDER BY `order` ASC')->result();
	
		foreach ($filters as $filter){
			$filter->items = $this->db->query('SELECT * 
													FROM filter_item 
													WHERE id_filter = "'.$filter->id.'" 
													ORDER BY `order` ASC')->result();
		}
		
		return $filters;
	}
	
	/* ??? */
	public function getFiltersOfCategory($id_category = 0)
	{
		$id_category = abs((int)$id_category);
		$filters = $this->db->query('SELECT * 
										FROM filter f 
										LEFT JOIN category_filter cf ON f.id = cf.id_filter
										WHERE cf.id_category = "'.$id_category.'" AND pricing = 0 
										ORDER BY f.order ASC')->result();
		foreach ($filters as $filter){
			$filter->items = $this->db->query('SELECT * 
													FROM filter_item 
													WHERE id_filter = "'.$filter->id.'" 
													ORDER BY `order` ASC')->result();
		}
		
		return $filters;
	}
	
	
	
	/* ???? */
	public function getFilterItemPricing()
	{
		# значения фильтров которые используются в формировании цены
		return $this->db->query('SELECT * 
									FROM filter_item 
									WHERE id_filter IN(
										SELECT id FROM filter WHERE pricing = 1
									)
									ORDER BY `order` ASC')->result();
	}
	
	public function addFilter()
	{
		$name		= isset($_POST['name'])	? clean($_POST['name'], true, true)  : '';
		
		$this->db->query('INSERT INTO filter (name, pricing) 
							VALUES(
								"'.$name.'"
							)');
		
		# ID
		$id_filter = $this->db->query('SELECT MAX(id) AS id FROM filter')->row()->id;
		
		# ITEMS значения фильтра
		if (isset($_POST['filter_item_name']['insert'])) foreach ($_POST['filter_item_name']['insert'] as $k=>$v){
			$_name	= isset($_POST['filter_item_name']['insert'][$k])	? clean($_POST['filter_item_name']['insert'][$k], true, true) : '';
			$_prefix= isset($_POST['filter_item_prefix']['insert'][$k])	? clean($_POST['filter_item_prefix']['insert'][$k], true, true) : '';
			$_order	= isset($_POST['filter_item_order']['insert'][$k])	? abs((int)$_POST['filter_item_order']['insert'][$k]) : 0;
				
			if ( ! mb_strlen($_name)) continue;
			
			$this->db->query('INSERT INTO filter_item (id_filter, name, prefix, `order`) 
								VALUES(
									"'.$id_filter.'", 
									"'.$_name.'",
									"'.$_prefix.'", 
									"'.$_order.'"
								)');
		}
		
		return;
	}
	
	public function updateFilter()
	{
		$id_filter	= isset($_POST['id'])				? abs((int)$_POST['id']) : 0;
		$name		= isset($_POST['name'])				? clean($_POST['name'], true, true) : 'empty';
		
		$this->db->query('UPDATE filter 
							SET 
								name = "'.$name.'"
							WHERE id = "'.$id_filter.'"');
		
		# ITEMS удаляем старые значения
		$this->db->query('DELETE FROM filter_item WHERE id_filter = "'.$id_filter.'"');
		if (isset($_POST['filter_item_name']['update'])) foreach ($_POST['filter_item_name']['update'] as $k=>$v){
			$_id	= abs((int)$k);
			$_name	= isset($_POST['filter_item_name']['update'][$k])	? clean($_POST['filter_item_name']['update'][$k], true, true) : '';
			$_prefix= isset($_POST['filter_item_prefix']['update'][$k])	? clean($_POST['filter_item_prefix']['update'][$k], true, true) : '';
			$_order	= isset($_POST['filter_item_order']['update'][$k])	? abs((int)$_POST['filter_item_order']['update'][$k]) : 0;
				
			if ( ! mb_strlen($_name)) continue;
			
			$this->db->query('INSERT INTO filter_item (id, id_filter, name, prefix, `order`) 
								VALUES(
									"'.$_id.'",
									"'.$id_filter.'", 
									"'.$_name.'",
									"'.$_prefix.'", 
									"'.$_order.'"
								)');
		}
		if (isset($_POST['filter_item_name']['insert'])) foreach ($_POST['filter_item_name']['insert'] as $k=>$v){
			$_name	= isset($_POST['filter_item_name']['insert'][$k])	? clean($_POST['filter_item_name']['insert'][$k], true, true) : '';
			$_prefix= isset($_POST['filter_item_prefix']['insert'][$k])	? clean($_POST['filter_item_prefix']['insert'][$k], true, true) : '';
			$_order	= isset($_POST['filter_item_order']['insert'][$k])	? abs((int)$_POST['filter_item_order']['insert'][$k]) : 0;
				
			if ( ! mb_strlen($_name)) continue;
			
			$this->db->query('INSERT INTO filter_item (id_filter, name, image, prefix, `order`) 
								VALUES(
									"'.$id_filter.'", 
									"'.$_name.'", 
									"'.$_prefix.'", 
									"'.$_order.'"
								)');
		}
		
		return;
	}
	
	public function setOrderFilter()
	{
		for ($i = 0, $cnt = count($_POST['filter_order']); $i < $cnt; $i++){
			$id = isset($_POST['filter_id'][$i]) ? abs((int)$_POST['filter_id'][$i]) : 0;
			$order = abs((int)$_POST['filter_order'][$i]);
			
			$this->db->query('UPDATE filter SET `order` = "'.$order.'" WHERE id = "'.$id.'"');
		}
		return;
	}
	
	public function deleteFilter($id = 0)
	{
		$id = abs((int)$id);
		$this->db->query('DELETE FROM filter WHERE id = "'.$id.'"');
	}
	
	
}