<?php 

class Butirsoal_model extends CI_Model {
	public function get_soal($id_kategori)
	{
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get("tblsoal")->result_array();
	}

	public function get_jawaban($no_soal)
	{
		$this->db->where("no_soal",$no_soal);
		$this->db->where("remarks","y");
		return $this->db->get("tbljawaban")->num_rows();
	}

	public function jmlsiswa()
	{
		$this->db->where("verification","verif");
		return $this->db->get("tblsiswa")->num_rows();
	}
}