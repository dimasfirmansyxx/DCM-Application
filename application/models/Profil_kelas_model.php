<?php 

class Profil_kelas_model extends CI_Model {
	public function get_siswa_by_kelas($id_kelas)
	{
		$this->db->where("id_kelas",$id_kelas);
		return $this->db->get("tblsiswa")->result_array();
	}

	public function get_score($id_siswa)
	{
		$jumlah = 0;
		$get_kategori = $this->kategori->get_all_kategori();
		$output = [];

		foreach ($get_kategori as $kategori) {
			$jml = 0;
			if ( !($kategori['id_kategori'] == 13) ) {
				$get_jawaban = $this->individu->get_jawaban($id_siswa,$kategori['id_kategori']);

				foreach ($get_jawaban as $jawaban) {
					if ( $jawaban['remarks'] == "y" ) {
						$jml++;
					}
				}
				$output[$kategori['id_kategori']] = $jml;
				$jumlah += $jml;
			}

		}
		$output["jumlah"] = $jumlah;

		return $output;
	}
}