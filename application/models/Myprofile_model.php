<?php 

class Myprofile_model extends CI_model {
	public function change_name($data)
	{
		$this->db->where("id_user",$data['id_user']);
		$this->db->set("nama",$data['nama']);
		$update = $this->db->update("tbluser");

		if ( $update > 0 ) {
			return 0;
		} else {
			return 1;
		}
	}

	public function change_username($data)
	{
		$check = $this->Clsglobal->check_availability("tbluser",["username" => $data['username']]);
		if ( $check == 2 ) {
			return 2;
		} else {
			$this->db->where("id_user",$data['id_user']);
			$this->db->set("username",$data['username']);
			$update = $this->db->update("tbluser");
			if ( $update > 0 ) {
				return 0;
			} else {
				return 1;
			}
		}
	}

	public function change_password($data)
	{
		$get_user = $this->Clsglobal->user_info($data['id_user']);
		if ( password_verify($data['passlama'], $get_user['password']) ) {
			$this->db->where("id_user",$data['id_user']);
			$this->db->set("password",$data['passbaru']);
			$update = $this->db->update("tbluser");
			if ( $update > 0 ) {
				return 0;
			} else {
				return 1;
			}
		} else {
			return 4;
		}
	}

	public function change_avatar($id_user,$filename)
	{
		$this->db->where("id_user",$id_user);
		$this->db->set("profile_photo",$filename);
		$update = $this->db->update("tbluser");
		if ( $update > 0 ) {
			return 0;
		} else {
			return 1;
		}
	}
}