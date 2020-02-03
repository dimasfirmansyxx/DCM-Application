<?php 

class Tabulasi_model extends CI_Model {
	public function num_soal($id_kategori)
	{
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get("tblsoal")->num_rows();
	}

	public function get_siswa($id_kelas)
	{
		$this->db->where("id_kelas",$id_kelas);
		return $this->db->get("tblsiswa")->result_array();
	}

	public function get_jawaban($id_siswa)
	{
		$this->db->where("id_siswa",$id_siswa);
		return $this->db->get("tbljawaban")->result_array();
	}

	public function get_num_siswa($id_kelas)
	{
		$this->db->where("id_kelas",$id_kelas);
		return $this->db->get("tblsiswa")->num_rows();
	}
}