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
}