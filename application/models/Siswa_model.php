<?php 

class Siswa_model extends CI_Model {
	var $table = 'tblsiswa';
    var $column_order = array('id_siswa', 'nama_siswa', 'id_kelas', 'jenis_kelamin', 'alamat');
    var $column_search = array('nama_siswa');
    var $order = array('id_siswa' => 'asc'); 
 
    private function _get_datatables_query()
    {
        $i = 0;
     
        foreach ($this->column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) {
                    $this->db->group_end(); 
                } 
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
        $this->db->from($this->table);

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

    public function get_all_siswa()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_siswa($id_siswa)
    {
        return $this->Clsglobal->get_data($this->table,["id_siswa" => $id_siswa]);
    }

    public function insert_siswa($data)
    {
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

    public function update_login($data)
    {
        $check = $this->Clsglobal->check_availability("tblsiswa",["id_siswa" => $data['id_siswa'],"verification" => "verif"]);
        if ( $check == 2 ) {
            $check = $this->Clsglobal->check_availability("tbluser",["username" => $data['username']]);
            if ( $check == 2 ) {
                $get = $this->Clsglobal->get_data("tbluser",["username" => $data['username']]);
                if ( $get['id_siswa'] == $data['id_siswa'] ) {
                    $this->db->set("password",$data['password']);
                    $this->db->where("id_siswa",$data['id_siswa']);
                    $update = $this->db->update("tbluser");
                    if ( $update > 0 ) {
                        return 0;
                    } else {
                        return 1;
                    }
                } else {
                    return 2;
                }
            } else {
                $this->db->set("username",$data['username']);
                $this->db->set("password",$data['password']);
                $this->db->where("id_siswa",$data['id_siswa']);
                $update = $this->db->update("tbluser");
                if ( $update > 0 ) {
                    return 0;
                } else {
                    return 1;
                }
            }
        } else {
            return 2;
        }
    }

    public function import_siswa($filename)
    {
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('./assets/excel_files/' . $filename);
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

        $data = array();

        $numrow = 1;
        foreach($sheet as $row){
            if($numrow > 2){
                $kelas = $this->Clsglobal->get_data("tblkelas",["kelas" => $row['F']]);
                array_push($data, array(
                    'no_urut' => $row['D'],
                    'nama_siswa' => strtoupper($row['E']),
                    'id_kelas' => $kelas['id_kelas'],
                    'jenis_kelamin' => strtolower($row['G']),
                    'tempat_lahir' => "",
                    'tgl_lahir' => "01/01/2020",
                    'verification' => "not"
                ));
            }
            $numrow++;
        }

        $this->db->truncate($this->table);


        $output = 0;
        foreach ($data as $row) {
            if ( !($row['jenis_kelamin'] == "pria") ) {
                if ( !($row['jenis_kelamin'] == "wanita") ) {
                    $this->db->truncate($this->table);
                    unlink('./assets/excel_files/' . $filename);
                    return 4;
                } else {
                    $output = 0;
                }
            }

            if ( $output == 0 ) {
                $condition = ["id_kelas" => $row['id_kelas']];
                if ( $this->Clsglobal->check_availability("tblkelas",$condition) == 3 ) {
                    $this->db->truncate($this->table);
                    unlink('./assets/excel_files/' . $filename);
                    return 4;
                } else {
                    $output = 0;
                }
            }

            if ( $output == 0 ) {
                $insert = $this->db->insert($this->table,$row);
                if ( $insert > 0 ) {
                    $output = 0;
                } else {
                    $output = 1;
                }
            }
        }

        unlink('./assets/excel_files/' . $filename);

        return $output;
    }
}