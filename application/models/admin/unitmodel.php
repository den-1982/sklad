<?php
/*
*	ADMIN
*/
class unitModel extends CI_Model
{
	public function getInvoices()
	{
		return $this->db->query('SELECT * FROM unit')->result();
	}
	
	public function getInvoice($id = 0)
	{
		$id = abs((int)$id);
		$invoice = $this->db->query()->row();
		return $invoice;
	}
	
	public function addInvoice()
	{
		$this->db->query('');
		return true;
	}
	
	public function updateInvoice()
	{
		$this->db->query('');
		return true;
	}
	
	public function delInvoice($id = 0)
	{
		$id = abs((int)$id);
		$this->db->query();
		return true;
	}
	
	
}