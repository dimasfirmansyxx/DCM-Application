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

	public function register($data)
	{
		if ( $data['privilege'] == "siswa" ) {
			if ( $this->Clsglobal->check_availability("tbluser",["id_siswa" => $data['id_siswa']]) == 3 ) {
				if ( $this->Clsglobal->check_availability("tbluser",["username" => $data['username']]) == 3 ) {
					$insert = $this->db->insert("tbluser",$data);
					if ( $insert > 0 ) {
						return 0;
					} else {
						return 1;
					}
				} else {
					return 202;
				}
			} else {
				return 201;
			}
		}
	}

	public function verification($data)
	{
		$user = $this->Clsglobal->user_info($data['id_user']);

		$this->db->set("verification","verif");
		$this->db->set("tempat_lahir",$data['tempat_lahir']);
		$this->db->set("tgl_lahir",$data['tgl_lahir']);
		$this->db->where("id_siswa",$user['id_siswa']);
		$update = $this->db->update("tblsiswa");

		if ( $update > 0 ) {
			return 0;
		} else {
			return 1;
		}
	}

	public function get_siswa($data)
	{
		return $this->Clsglobal->get_data("tblsiswa",$data);
	}
}