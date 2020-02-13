<?php 

class Pengelompokan_model extends CI_Model {
	public function get_all_soal()
	{
		$this->db->where("id_kategori !=", "13");
		return $this->db->get("tblsoal")->result_array();
	}

	public function get_kelompok($no_soal)
	{
		$output = [];

		$this->db->where("no_soal",$no_soal);
		$this->db->where("remarks","y");
		$get_jawaban = $this->db->get("tbljawaban")->result_array();
		foreach ($get_jawaban as $jawaban) {
			$siswa = $this->siswa->get_siswa($jawaban['id_siswa']);
			$kelas = $this->kelas->get_kelas($siswa['id_kelas']);
			$output[] = $siswa['no_urut'] . " (" . $kelas['kelas'] . ")";
		}

		return $output;
	}
}