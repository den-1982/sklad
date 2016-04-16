<?php
/*
*	ADMIN
*/
class settingsModel extends CI_Model
{
	private static $settingsData;
	
	public function getSettings()
	{
		if (self::$settingsData) return self::$settingsData;
		
		$settings = $this->db->query('SELECT * FROM settings')->row();
		
		self::$settingsData = $settings;
		
		return $settings;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////// EDIT SETTINGS
	public function editSettings()
	{
		# ADMIN редактирование админ данных 
		if (isset($_POST['admin']['login'])){
			$_login = isset($_POST['admin']['login']) ? clean($_POST['admin']['login'], false, true) : '';
			
			$this->db->query('UPDATE admin SET login = "'.$_login.'" ');
		}
		if (isset($_POST['admin']['password'])){
			$_password = isset($_POST['admin']['password']) ? mysql_real_escape_string($_POST['admin']['password']) : '';
			
			$this->db->query('UPDATE admin SET password = "'.$_password.'" ');
		}
		if (isset($_POST['admin']['email'])){		
			$_email = isset($_POST['admin']['email']) ? clean($_POST['admin']['email'], true, true) : '';
			
			$this->db->query('UPDATE admin SET email = "'.$_email.'" ');
		}

		return;
	}
	
	
}