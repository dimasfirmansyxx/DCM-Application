<?php 

class Panduan_model extends CI_Model {
	public function edit($data)
	{
		$this->db->where("id_info",7);
		$this->db->set("value",$data['panduan_app']);
		$update = $this->db->update("tblinfosekolah");
		if ( $update ) {
			return 1;
		} else {
			return 0;
		}
	}
}