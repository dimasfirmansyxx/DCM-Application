<?php 

class Profil_individu_model extends CI_Model {
	public function get_kategori($start,$end)
	{
		$this->db->where("id_kategori >=",$start);
		$this->db->where("id_kategori <=",$end);
		return $this->db->get("tblkategorisoal")->result_array();
	}

	public function get_siswa($id_siswa)
    {
        return $this->Clsglobal->get_data("tblsiswa",["id_siswa" => $id_siswa]);
    }

    public function get_siswa_by_nourut($kelas,$no_urut)
    {
        return $this->Clsglobal->get_data("tblsiswa",["id_kelas" => $kelas, "no_urut" => $no_urut]);
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

	public function get_kategori_chart($id_siswa)
	{
		$kategori = $this->get_kategori(1,12);
		$siswa = $this->get_siswa($id_siswa);
		$output = [];
		foreach ($kategori as $row) {
			$jawaban = $this->get_jawaban($siswa['id_siswa'],$row['id_kategori']);
			$jumlah = 0;
			foreach ($jawaban as $jwb) {
				if ( $jwb['remarks'] == "y" ) {
					$jumlah++;
				}
			}

			$output[$row['nama_kategori']] = $jumlah / 20 * 100;
		}

		return $output;
	}

	public function get_essay()
	{
		$this->db->where("id_kategori","13");
		return $this->db->get("tblsoal")->result_array();
	}

	public function get_section_chart($id_siswa)
	{
		$siswa = $this->get_siswa($id_siswa);
		
		$catpribadi = $this->get_kategori(1,5);
		$jmlpribadi = 0;
		foreach ($catpribadi as $kategori) {
			$jawaban = $this->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			$jumlah = 0;
			foreach ($jawaban as $jwb) {
				if ( $jwb['remarks'] == "y" ) {
					$jumlah++;
				}
			}

			$jmlpribadi += $jumlah;
		}
		$jmlpribadi = $jmlpribadi / 100 * 100;

		$catsosial = $this->get_kategori(6,8);
		$jmlsosial = 0;
		foreach ($catsosial as $kategori) {
			$jawaban = $this->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			$jumlah = 0;
			foreach ($jawaban as $jwb) {
				if ( $jwb['remarks'] == "y" ) {
					$jumlah++;
				}
			}

			$jmlsosial += $jumlah;
		}
		$jmlsosial = $jmlsosial / 60 * 100;

		$catbelajar = $this->get_kategori(9,11);
		$jmlbelajar = 0;
		foreach ($catbelajar as $kategori) {
			$jawaban = $this->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			$jumlah = 0;
			foreach ($jawaban as $jwb) {
				if ( $jwb['remarks'] == "y" ) {
					$jumlah++;
				}
			}

			$jmlbelajar += $jumlah;
		}
		$jmlbelajar = $jmlbelajar / 60 * 100;

		$catkarir = $this->get_kategori(12,12);
		$jmlkarir = 0;
		foreach ($catkarir as $kategori) {
			$jawaban = $this->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			$jumlah = 0;
			foreach ($jawaban as $jwb) {
				if ( $jwb['remarks'] == "y" ) {
					$jumlah++;
				}
			}

			$jmlkarir += $jumlah;
		}
		$jmlkarir = $jmlkarir / 20 * 100;

		return [
			"pribadi" => ceil($jmlpribadi),
			"sosial" => ceil($jmlsosial),
			"belajar" => ceil($jmlbelajar),
			"karir" => ceil($jmlkarir)
		];
	}
}