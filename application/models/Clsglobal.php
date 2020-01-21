<?php 

class Clsglobal extends CI_Model {
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

	public function upload_files($key,$directory,$allow_extension)
	{
		if ( $_FILES[$key]['error'] == 4 ) {
			return "";
		} else {
			$total = count($_FILES[$key]['name']);
			$returnName = array();

			for ($i=0; $i < $total; $i++) { 
				$name = $_FILES[$key]['name'][$i];
				$tmp = $_FILES[$key]['tmp_name'][$i];
				$explodename = explode(".", $name);
				$extension = strtolower(end($explodename));

				if ( in_array($extension, $allow_extension) ) {
					$newName = uniqid() . "." . $extension;
					$dir = "./assets/" . $directory;
					move_uploaded_file($tmp, $dir . $newName);

					array_push($returnName, $newName);
				} else {
					return 4;
				}
			}

			return $returnName;
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
}