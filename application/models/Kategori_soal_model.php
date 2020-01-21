<?php 

class Kategori_soal_model extends CI_Model {
	var $table = 'tblkategorisoal';
    var $column_order = array('id_kategori', 'nama_kategori');
    var $column_search = array('nama_kategori');
    var $order = array('id_kategori' => 'asc'); 
 
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

    public function get_all_kategori()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_kategori($id_kategori)
    {
        return $this->Clsglobal->get_data($this->table,["id_kategori" => $id_kategori]);
    }

    public function insert_kategori($data)
    {
    	$insert = $this->db->insert($this->table,$data);
    	if ( $insert > 0 ) {
    		return 0;
    	} else {
    		return 1;
    	}
    }

    public function delete_kategori($id_kategori)
    {
    	$this->db->where("id_kategori",$id_kategori);
    	$delete = $this->db->delete($this->table);
    	if ( $delete > 0 ) {
    		return 0;
    	} else {
    		return 1;
    	}
    }

    public function update_kategori($data)
    {
        $multiple = false;

        $check = $this->Clsglobal->check_availability($this->table,["nama_kategori" => $data['nama_kategori']]);
        if ( $check == 2 ) {
            $get = $this->Clsglobal->get_data($this->table,["nama_kategori" => $data['nama_kategori']]);
            if ( $get['id_kategori'] == $data['id_kategori'] ) {
                $multiple = false;
            } else {
                $multiple = true;
            }
        } else {
            $multiple = false;
        }

        if ( $multiple == false ) {
            $this->db->set("nama_kategori", $data['nama_kategori']);
            $this->db->where("id_kategori", $data['id_kategori']);
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