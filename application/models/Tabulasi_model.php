<?php 

class Tabulasi_model extends CI_Model {
	public function num_soal($id_kategori)
	{
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get("tblsoal")->num_rows();
	}

	public function get_jml_siswa()
	{
		$this->db->where("verification","verif");
		return $this->db->get("tblsiswa")->num_rows();
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

	public function get_score_paralel($id_kategori)
	{
		$terjawab = 0;
		$this->db->where("remarks","y");
		$get_jawaban = $this->db->get("tbljawaban")->result_array();
		foreach ($get_jawaban as $jawaban) {
			$get_soal = $this->Clsglobal->get_data("tblsoal",["no_soal" => $jawaban['no_soal']]);
			if ( $get_soal['id_kategori'] == $id_kategori ) {
				$terjawab += 1;
			}
		}

		return $terjawab;
	}
}