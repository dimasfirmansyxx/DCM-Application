<?php 

class Auth_model extends CI_Model {
	public function login_check($data)
	{
		$username = $data['username'];
		$password = $data['password'];

		if ( $this->Clsglobal->check_availability("tbluser",["username" => $username]) == 2 ) {
			$get = $this->Clsglobal->get_data("tbluser",["username" => $username]);
			if ( password_verify($password, $get['password']) ) {
				$this->session->set_userdata("user_logged",true);
				$this->session->set_userdata("user_id",$get['id_user']);
				return 0;
			} else {
				return 4;
			}
		} else {
			return 4;
		}
	}
}