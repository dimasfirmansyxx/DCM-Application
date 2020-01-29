<?php 

class Config_sekolah_model extends CI_Model {
	public function change_info($data)
	{
		$output = 0;
		foreach ($data as $key => $value) {
			$this->db->set("value",$value);
			$this->db->where("key",$key);
			$update = $this->db->update("tblinfosekolah");
			if ( $update > 0 ) {
				$output = 0;
			} else {
				return 1;
			}
		}

		return $output;
	}
}