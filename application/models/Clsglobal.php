<?php 

class Clsglobal extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Auth_model","auth");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Soal_model","soal");
	}

	public function check_availability($table,$condition)
	{
		$query = $this->db->get_where($table,$condition);
		if ( $query->num_rows() > 0 ) {
			return 2;
		} else {
			return 3;
		}
	}

	public function num_rows($table)
	{
		return $this->db->get($table)->num_rows();
	}

	public function upload_files($key,$directory,$allow_extension,$file_name = "random")
	{
		if ( $_FILES[$key]['error'] == 4 ) {
			return "";
		} else {
			$total = count($_FILES[$key]['name']);
			$returnName = array();

			if ( $total > 1 ) {
				for ($i=0; $i < $total; $i++) { 
					$name = $_FILES[$key]['name'][$i];
					$tmp = $_FILES[$key]['tmp_name'][$i];
					$explodename = explode(".", $name);
					$extension = strtolower(end($explodename));

					if ( in_array($extension, $allow_extension) ) {
						$newName = date("YmdHis") . "." . $extension;
						$dir = "./assets/" . $directory . "/";
						move_uploaded_file($tmp, $dir . $newName);

						array_push($returnName, $newName);
					} else {
						return 5;
					}
				}
			} else {
				$name = $_FILES[$key]['name'];
				$tmp = $_FILES[$key]['tmp_name'];
				$explodename = explode(".", $name);
				$extension = strtolower(end($explodename));

				if ( in_array($extension, $allow_extension) ) {
					if ( $file_name == "random" ) {
						$newName = date("YmdHis") . "." . $extension;
					} else {
						$newName = $file_name;
					}

					$dir = "./assets/" . $directory . "/";
					move_uploaded_file($tmp, $dir . $newName);

					array_push($returnName, $newName);
				} else {
					return 5;
				}
			}

			return $returnName;
		}

	}

	public function check_file_extension($key,$allow_extension)
	{
		if ( $_FILES[$key]['error'] == 4 ) {
			return "";
		} else {
			$total = count($_FILES[$key]['name']);
			$returnName = array();
			$name = $_FILES[$key]['name'];
			$tmp = $_FILES[$key]['tmp_name'];
			$explodename = explode(".", $name);
			$extension = strtolower(end($explodename));

			if ( in_array($extension, $allow_extension) ) {
				return 0;
			} else {
				return 5;
			}
		}
	}

	public function get_data($table,$condition)
	{
		if ( $this->check_availability($table,$condition) == 2 ) {
			return $this->db->get_where($table,$condition)->result_array()[0];
		} else {
			return 3;
		}
	}

	public function get_query($table,$condition)
	{
		if ( $this->check_availability($table,$condition) == 2 ) {
			return $this->db->get_where($table,$condition)->result_array();
		} else {
			return 3;
		}
	}

	public function site_info($show)
	{
		$this->db->where("key",$show);
		return $this->db->get("tblinfosekolah")->result_array()[0]['value'];
	}

	public function user_info($id_user)
	{
		$this->db->where("id_user",$id_user);
		return $this->db->get("tbluser")->result_array()[0];
	}

	public function get_new_id($table,$key)
	{
		if ( $this->num_rows($table) > 0 ) {
			$this->db->order_by($key,"desc");
			$get = $this->db->get($table)->result_array();

			return $get[0][$key] + 1; 
		} else {
			return 1;
		}
	}

	public function check_verification()
	{
		$user = $this->user_info($this->session->user_id);
		$siswa = $this->siswa->get_siswa($user['id_siswa']);
		if ( $siswa['verification'] == "not" ) {
			return 4;
		} else {
			return 0;
		}
	}

	public function romawi($angka)
	{
	    $output = "";
	    if ($angka < 1 || $angka > 5000) { 
	        $output = "Batas Angka 1 s/d 5000";
	    } else {
	        while ($angka >= 1000) {
	            $output .= "M";
	            $angka -= 1000;
	        }
	    }


	    if ($angka >= 500) {
	        if ($angka > 500) {
	            if ($angka >= 900) {
	                $output .= "CM";
	                $angka -= 900;
	            } else {
	                $output .= "D";
	                $angka-=500;
	            }
	        }
	    }
	    while ($angka>=100) {
	        if ($angka>=400) {
	            $output .= "CD";
	            $angka -= 400;
	        } else {
	            $angka -= 100;
	        }
	    }
	    if ($angka>=50) {
	        if ($angka>=90) {
	            $output .= "XC";
	            $angka -= 90;
	        } else {
	            $output .= "L";
	            $angka-=50;
	        }
	    }
	    while ($angka >= 10) {
	        if ($angka >= 40) {
	            $output .= "XL";
	            $angka -= 40;
	        } else {
	            $output .= "X";
	            $angka -= 10;
	        }
	    }
	    if ($angka >= 5) {
	        if ($angka == 9) {
	            $output .= "IX";
	            $angka-=9;
	        } else {
	            $output .= "V";
	            $angka -= 5;
	        }
	    }
	    while ($angka >= 1) {
	        if ($angka == 4) {
	            $output .= "IV"; 
	            $angka -= 4;
	        } else {
	            $output .= "I";
	            $angka -= 1;
	        }
	    }

	    return ($output);
	}
}