<?php
/*
*	ADMIN
*/
class invoiceModel extends CI_Model
{
	
	public function getInvoices()
	{
		//$this->db->query('SELECT * FROM invoice')->result();

		return array();
	}
	
	public function getInvoice($id = 0)
	{
		$id = abs((int)$id);
		
		//$this->db->query('SELECT * FROM invoice WHERE id = "'.$id.'"')->row();

		return new stdClass();
	}
	
}