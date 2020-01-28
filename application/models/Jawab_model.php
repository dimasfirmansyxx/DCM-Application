<?php 

class Jawab_model extends CI_Model {
	public function get_soal($id_kategori)
	{
		$this->db->where("id_kategori",$id_kategori);
		return $this->db->get("tblsoal")->result_array();
	}

	public function save_jawaban($data)
	{
		$getuser = $this->Clsglobal->user_info($data['id_user']);
		$id_siswa = $getuser['id_siswa'];
		$output;

		foreach ($data['jawaban'] as $kategori) {
			foreach ($kategori as $key => $value) {
				$insert = [
					"id_siswa" => $id_siswa,
					"no_soal" => $key,
					"remarks" => $value
				];

				$output = $this->db->insert("tbljawaban",$insert);
			}
		}

		if ( $output > 0 ) {
			return 0;
		} else {
			return 1;
		}
	}
}