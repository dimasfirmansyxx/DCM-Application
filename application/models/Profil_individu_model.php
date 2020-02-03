<?php 

class Profil_individu_model extends CI_Model {
	public function get_kategori($start,$end)
	{
		$this->db->where("id_kategori >=",$start);
		$this->db->where("id_kategori <=",$end);
		return $this->db->get("tblkategorisoal")->result_array();
	}

	public function get_siswa($no_urut)
    {
        return $this->Clsglobal->get_data("tblsiswa",["no_urut" => $no_urut]);
    }

    public function get_jawaban($id_siswa,$id_kategori)
	{
		$this->db->where("id_siswa",$id_siswa);
		$get = $this->db->get("tbljawaban")->result_array();
		$output = [];
		foreach ($get as $row) {
			$get_soal = $this->soal->get_soal($row['no_soal']);
			if ( $get_soal['id_kategori'] == $id_kategori ) {
				$output[] = $row;
			}
		}

		return $output;
	}
}