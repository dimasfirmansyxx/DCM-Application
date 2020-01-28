<?php 

class Jawab_model extends CI_Model {
	public function get_soal($id_kategori)
	{
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get("tblsoal")->result_array();
	}
}