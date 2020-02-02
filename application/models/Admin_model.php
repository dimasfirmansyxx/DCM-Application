<?php 

class Admin_model extends CI_Model {
	var $table = 'tbluser';
    var $column_order = array('id_user', 'nama','username','password','privilege','id_siswa','profile_photo');
    var $column_search = array('nama','username');
    var $order = array('id_user' => 'asc'); 

    private function _get_datatables_query()
    {
        $this->db->where("privilege","admin");
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function insert_admin($data)
    {
    	$input = [
    		"nama" => $data['nama'],
    		"username" => $data['username'],
    		"password" => $data['password'],
    		"privilege" => "admin",
    		"id_siswa" => "0",
    		"profile_photo" => "noava.png"
    	];
    	$insert = $this->db->insert($this->table,$input);
    	if ( $insert > 0 ) {
    		return 0;
    	} else {
    		return 1;
    	}
    }

    public function delete_admin($id_user)
    {
    	$this->db->where("id_user",$id_user);
    	$delete = $this->db->delete($this->table);
    	if ( $delete > 0 ) {
    		return 0;
    	} else {
    		return 1;
    	}
    }
}