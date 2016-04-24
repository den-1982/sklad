<?php
/*
*	ADMIN
*/
class sizeModel extends CI_Model
{
	public function getSize($id = 0)
	{
		$id = abs((int)$id);
		return $this->db->query('SELECT * FROM size WHERE id = "'.$id.'"')->row();
	}
	
	public function getSizes()
	{
		return $this->db->query('SELECT * FROM size ORDER BY `order` ASC')->result();
	}
	
	public function addSize()
	{
		$name	= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$prefix	= isset($_POST['prefix'])	? clean($_POST['prefix'], true, true) : '';
		
		$this->db->query('INSERT INTO size (name, prefix) 
							VALUES(
								"'.$name.'",
								"'.$prefix.'"
							)');
		
		return true;
	}
	
	public function updateSize()
	{
		$id		= isset($_POST['size_id'])	? clean($_POST['size_id'], true, true) : '';
		$name	= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$prefix	= isset($_POST['prefix'])	? clean($_POST['prefix'], true, true) : '';
		
		$this->db->query('UPDATE size
							SET
								name = "'.$name.'",
								prefix = "'.$prefix.'"
							WHERE id = "'.$id.'"');
		
		return true;
	}
	
	public function setOrderSize()
	{
		if (isset($_POST['size_order'])) foreach ($_POST['size_order'] as $k=>$v){
			
			$id		= isset($_POST['size_id'][$k])	? abs((int)$_POST['size_id'][$k]) : 0;
			$order	= isset($_POST['size_order'][$k])	? abs((int)$_POST['size_order'][$k]) : 0;
			
			$this->db->query('UPDATE size SET `order` = "'.$order.'" WHERE id = "'.$id.'"');
		}

		return true;
	}
	
	public function deleteSize($id = 0)
	{
		$id = abs((int)$id);
		$this->db->query('DELETE FROM size WHERE id = "'.$id.'"');
		
		return true;
	}
	
	
}