<?php 

class Siswa_model extends CI_Model {
	var $table = 'tblsiswa';
    var $column_order = array('id_siswa', 'nama_siswa', 'id_kelas', 'jenis_kelamin', 'alamat');
    var $column_search = array('id_siswa', 'nama_siswa');
    var $order = array('id_siswa' => 'asc'); 
 
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

    public function get_siswa($id_siswa)
    {
        return $this->Clsglobal->get_data($this->table,["id_siswa" => $id_siswa]);
    }

    public function insert_siswa($data,$userdata)
    {
        $createuser = [
            "nama" => $data['nama_siswa'],
            "username" => $userdata['username'],
            "password" => $userdata['password'],
            "privilege" => "siswa",
            "id_siswa" => $data['id_siswa'],
            "profile_photo" => "noava.png"
        ];
        $this->db->insert("tbluser",$createuser);
        $insert = $this->db->insert($this->table,$data);
        if ( $insert > 0 ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function delete_siswa($id_siswa)
    {
        $this->db->where("id_siswa",$id_siswa);
        $this->db->delete("tbluser");

        $this->db->where("id_siswa",$id_siswa);
        $delete = $this->db->delete($this->table);
        if ( $delete > 0 ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function update_siswa($data)
    {
        $multiple = false;
        $condition = [
            "id_kelas" => $data['id_kelas'],
            "nama_siswa" => $data['nama_siswa']
        ];

        $check = $this->Clsglobal->check_availability($this->table,$condition);
        if ( $check == 2 ) {
            $get = $this->Clsglobal->get_data($this->table,$condition);
            if ( $get['id_siswa'] == $data['id_siswa'] ) {
                $multiple = false;
            } else {
                $multiple = true;
            }
        } else {
            $multiple = false;
        }

        if ( $multiple == false ) {
            $dataupdate = [
                "id_kelas" => $data['id_kelas'],
                "nama_siswa" => $data['nama_siswa'],
                "jenis_kelamin" => $data['jenis_kelamin']
            ];
            $this->db->where("id_siswa", $data['id_siswa']);
            $update = $this->db->update($this->table,$dataupdate);
            if ( $update > 0 ) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }

    // public function import_soal($filename)
    // {
    //     include APPPATH.'third_party/PHPExcel/PHPExcel.php';
    //     $excelreader = new PHPExcel_Reader_Excel2007();
    //     $loadexcel = $excelreader->load('./assets/excel_files/' . $filename);
    //     $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

    //     $data = array();

    //     $numrow = 1;
    //     foreach($sheet as $row){
    //         if($numrow > 2){
    //             array_push($data, array(
    //                 'no_soal' => $row['D'],
    //                 'id_kategori' => $row['E'],
    //                 'soal' => $row['F'],
    //                 'jenis' => $row['G'],
    //             ));
    //         }
    //         $numrow++;
    //     }

    //     $this->db->truncate($this->table);


    //     $output = 0;
    //     foreach ($data as $row) {
    //         if ( !($row['jenis'] == "check") ) {
    //             if ( !($row['jenis'] == "essay") ) {
    //                 $this->db->truncate($this->table);
    //                 unlink('./assets/excel_files/' . $filename);
    //                 return 4;
    //             } else {
    //                 $output = 0;
    //             }
    //         }

    //         if ( $output == 0 ) {
    //             $condition = ["id_kategori" => $row['id_kategori']];
    //             if ( $this->Clsglobal->check_availability("tblkategorisoal",$condition) == 3 ) {
    //                 $this->db->truncate($this->table);
    //                 unlink('./assets/excel_files/' . $filename);
    //                 return 4;
    //             } else {
    //                 $output = 0;
    //             }
    //         }

    //         if ( $output == 0 ) {
    //             $insert = $this->db->insert($this->table,$row);
    //             if ( $insert > 0 ) {
    //                 $output = 0;
    //             } else {
    //                 $output = 1;
    //             }
    //         }
    //     }

    //     unlink('./assets/excel_files/' . $filename);

    //     return $output;
    // }
}