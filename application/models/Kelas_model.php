<?php 

class Kelas_model extends CI_Model {
	var $table = 'tblkelas';
    var $column_order = array('id_kelas', 'kelas');
    var $column_search = array('kelas');
    var $order = array('id_kelas' => 'asc'); 
 
    private function _get_datatables_query()
    {
         
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

    public function insert_kelas($data)
    {
    	$insert = $this->db->insert($this->table,$data);
    	if ( $insert > 0 ) {
    		return 0;
    	} else {
    		return 1;
    	}
    }

    public function delete_kelas($id_kelas)
    {
        $getsiswa = $this->Clsglobal->get_query("tblsiswa",["id_kelas" => $id_kelas]);
        foreach ($getsiswa as $siswa) {
            $this->db->where("id_siswa",$siswa['id_siswa']);
            $this->db->delete("tbljawaban");
         
            $this->db->where("id_siswa",$siswa['id_siswa']);
            $this->db->delete("tbluser");
            
            $this->db->where("id_siswa",$siswa['id_siswa']);
            $this->db->delete("tblsiswa");
        }

    	$this->db->where("id_kelas",$id_kelas);
    	$delete = $this->db->delete($this->table);
    	if ( $delete > 0 ) {
    		return 0;
    	} else {
    		return 1;
    	}
    }

    public function get_kelas($id_kelas)
    {
        return $this->Clsglobal->get_data($this->table,["id_kelas" => $id_kelas]);
    }

    public function get_all_kelas()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function update_kelas($data)
    {
        $multiple = false;

        $check = $this->Clsglobal->check_availability($this->table,["kelas" => $data['kelas']]);
        if ( $check == 2 ) {
            $get = $this->Clsglobal->get_data($this->table,["kelas" => $data['kelas']]);
            if ( $get['id_kelas'] == $data['id_kelas'] ) {
                $multiple = false;
            } else {
                $multiple = true;
            }
        } else {
            $multiple = false;
        }

        if ( $multiple == false ) {
            $this->db->set("kelas", $data['kelas']);
            $this->db->where("id_kelas", $data['id_kelas']);
            $update = $this->db->update($this->table);
            if ( $update > 0 ) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }
}