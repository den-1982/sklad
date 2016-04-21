<?php
/*
*	ADMIN
*/
class invoiceModel extends CI_Model
{
	
	public function getInvoices()
	{
		$invoices = $this->db->query('SELECT * FROM invoice')->result();

		return $invoices;
	}
	
	public function getInvoice($id = 0)
	{
		$id = abs((int)$id);
		
		$invoice = $this->db->query('SELECT * FROM invoice WHERE id = "'.$id.'"')->row();

		return $invoice;
	}
	
	public function addInvoice()
	{
		echo '<pre>';
		print_r($_POST);
		echo '<pre>';
		exit;
		
		$id = abs((int)$id);
		
		$this->db->query('')->row();

		return true;
	}
	
	public function updateInvoice()
	{
		echo '<pre>';
		print_r($_POST);
		echo '<pre>';
		exit;
		
		$id = abs((int)$id);
		
		$this->db->query('')->row();

		return true;
	}
	
	public function delInvoice($id = 0)
	{
		echo '<pre>';
		print_r($_POST);
		echo '<pre>';
		exit;
		
		$id = abs((int)$id);
		
		$this->db->query('DELETE FROM invoice WHERE id = "'.$id.'"')->row();

		return true;
	}
	
}