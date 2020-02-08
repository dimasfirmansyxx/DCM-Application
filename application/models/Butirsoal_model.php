<?php 

class Butirsoal_model extends CI_Model {
	public function get_soal($id_kategori)
	{
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get("tblsoal")->result_array();
	}

	public function get_jawaban($no_soal, $id_kelas = null)
	{
		if ( $id_kelas == null ) {
			$this->db->where("no_soal",$no_soal);
			$this->db->where("remarks","y");
			return $this->db->get("tbljawaban")->num_rows();
		} else {
			$this->db->where("no_soal",$no_soal);
			$this->db->where("remarks","y");
			$get = $this->db->get("tbljawaban")->result_array();
			$output = [];

			foreach ($get as $row) {
				$get_siswa = $this->siswa->get_siswa($row['id_siswa']);
				if ( $get_siswa['id_kelas'] == $id_kelas ) {
					$output[] = $row;
				}
			}

			return count($output);
		}
	}

	public function jmlsiswa($id_kelas = null)
	{
		if ( $id_kelas == null ) {
			$this->db->where("verification","verif");
			return $this->db->get("tblsiswa")->num_rows();
		} else {
			$this->db->where("id_kelas",$id_kelas);
			$this->db->where("verification","verif");
			return $this->db->get("tblsiswa")->num_rows();
		}
	}
}