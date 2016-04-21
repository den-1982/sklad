<?php
/*
*	ADMIN
*/
class supplierModel extends CI_Model
{
	public function getSuppliers()
	{
		return $this->db->query('SELECT *
									FROM supplier
									ORDER BY `order` ASC')->result();
	}
	
	public function getSupplier($id = 0)
	{
		$id = abs((int)$id);
		
		return $this->db->query('SELECT *
									FROM supplier
									WHERE id = "'.$id.'"')->row();
	}
	
	public function addSupplier()
	{
		$name		= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$person		= isset($_POST['person'])	? clean($_POST['person'], true, true) : '';
		$phone		= isset($_POST['phone'])	? clean($_POST['phone'], true, true) : '';
		$email		= isset($_POST['email'])	? clean($_POST['email'], true, true) : '';
		$website	= isset($_POST['website'])	? clean($_POST['website'], true, true) : '';
		$payment_details = isset($_POST['payment_details']) ? clean($_POST['payment_details'], true, true) : '';
		$note 		= isset($_POST['note'])		? clean($_POST['note'], true, true) : '';
		
		
		$this->db->query('INSERT INTO supplier (name, person, phone, email, website, payment_details, note) 
							   VALUES (
								"'.$name.'", 
								"'.$person.'", 
								"'.$phone.'", 
								"'.$email.'", 
								"'.$website.'", 
								"'.$payment_details.'", 
								"'.$note.'"
							)');	
		return true;
	}
	
	public function updateSupplier()
	{
		$id 		= isset($_POST['supplier_id']) ? abs((int)$_POST['supplier_id']) : 0;
		$name		= isset($_POST['name'])		? clean($_POST['name'], true, true) : '';
		$person		= isset($_POST['person'])	? clean($_POST['person'], true, true) : '';
		$phone		= isset($_POST['phone'])	? clean($_POST['phone'], true, true) : '';
		$email		= isset($_POST['email'])	? clean($_POST['email'], true, true) : '';
		$website	= isset($_POST['website'])	? clean($_POST['website'], true, true) : '';
		$payment_details = isset($_POST['payment_details']) ? clean($_POST['payment_details'], true, true) : '';
		$note 		= isset($_POST['note'])		? clean($_POST['note'], true, true) : '';
		
		
		$this->db->query('UPDATE supplier 
							SET
								name	= "'.$name.'", 
								person	= "'.$person.'",  
								phone	= "'.$phone.'",  
								email	= "'.$email.'",  
								website = "'.$website.'",  
								payment_details = "'.$payment_details.'",  
								note	= "'.$note.'"
							WHERE id = "'.$id.'"');	
		return true;
	}
	
	public function sortOrderSuppliers()
	{
		if (isset($_POST['supplier_order'])) foreach ($_POST['supplier_order'] as $k=>$v){
			$supplier_id		= isset($_POST['supplier_id'][$k])	? abs((int)$_POST['supplier_id'][$k]) : 0;
			$supplier_order	= isset($_POST['supplier_order'][$k])? abs((int)$_POST['supplier_order'][$k]) : 0;
			
			$this->db->query('UPDATE supplier SET `order` = "'.$supplier_order.'" WHERE id = "'.$supplier_id.'"');
		}
		
		return true;
	}
	
	public function delSupplier($id = 0)
	{
		$id = abs((int)$id);
		$this->db->query('DELETE FROM supplier WHERE id = "'.$id.'"');

		return true;
	}
	
	
}