<?php
/*
*	ADMIN
*/
class adminModel extends CI_Model
{
	public function getAdmin()
	{
		return $this->db->query('SELECT * FROM admin')->row();
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// VALID_ADMIN (NEW)
	public function VALID_ADMIN()
	{
		# LOGOUT
		if ( isset($_GET['logout']) ){
			$this->session->unset_userdata('admin');
			redirect('/admin');
			exit;
		}
		
		# RECOVER PASSWORD
		if ( isset($_POST['recover']) ){
			$this->_recoverPassword();
			redirect('/admin');
			exit;
		}
		
		# AUTH
		if ( isset($_POST['login']) ){
			$login		= isset($_POST['login']) ? mysql_real_escape_string($_POST['login']) : '';
			$password	= isset($_POST['password']) ? mysql_real_escape_string($_POST['password']) : '';		

			$admin = $this->db->query('SELECT * FROM admin WHERE login LIKE "'.$login.'" AND password LIKE "'.$password.'"')->row();
			
			if ($admin){
				$this->session->set_userdata('admin', TRUE);
			}
			redirect('/admin');
			exit;
		}
		
		# VALID
		if ( ! $this->session->userdata('admin') ){
			if ($this->uri->total_segments() > 1) redirect('/admin');
			
			$this->load->view('admin/a_login');
			$this->output->_display();
			
			exit;
		}
	}
////////////////////////////////////////////////////////////////////// RECOVER
	private function _recoverPassword()
	{
		$response = array(
			'response'=>''
		);
		
		$email = isset($_POST['email']) ? mysql_real_escape_string($_POST['email']) : '';
		
		$data['admin'] = $this->db->query('SELECT * FROM admin WHERE email LIKE "'.$email.'"')->row();
		
		# ERROR
		if ( ! $data['admin']){
			$response['response'] = '<div style="padding:20px 50px;text-align:center;font-size:16px;">Такого пользователя ('.$email.') нет!</div>';
			echo json_encode($response);
			exit;
		}

		# SEND EMAIL
		$this->load->view('/admin/service/recover-password.php', $data);
		$html = $this->output->get_output();
		// file_put_contents(ROOT.'/TEST-RECOVER.html', $html);
		
		$to			= $data['admin']->email;
		$tema		= 'Востановление пароля';	
		$headers	= "From: ".strtoupper(SERVER)." <webmaster@".strtoupper(SERVER).">\r\n";
		$headers	.= "Content-type: text/html; charset=\"utf-8\"";
		mail($to, $tema, $html, $headers);
		
		### скрытие email (ha***@ua.fm)
		$data = explode('@', $data['admin']->email);
		$_a = isset($data[0]) ? $data[0] : '';
		$_b = isset($data[1]) ? $data[1] : '';
		$_r = '';
		
		for ($i=0; $i < strlen($_a); $i++){
			if($i > 1) 
				$_r .= '*';
			else
				$_r .= $_a{$i};
		}
		$email = $_r.'@'.$_b;

		$response['response'] = 
		'<div style="padding:20px 50px;text-align:center;font-size:18px;">
			На ваш адрес 
			<span style="color:#ff0000;">'.$email.'</span> 
			<br>
			выслан пароль.
		</div>';
		
		echo json_encode($response);
		exit;
		
	}

	
}