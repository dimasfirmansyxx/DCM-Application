<?php 

class Soal_model extends CI_Model {
	var $table = 'tblsoal';
    var $column_order = array('no_soal', 'id_kategori', 'soal', 'jenis');
    var $column_search = array('soal');
    var $order = array('no_soal' => 'asc'); 
 
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

    public function get_soal($no_soal)
    {
        return $this->Clsglobal->get_data($this->table,["no_soal" => $no_soal]);
    }

    public function insert_soal($data)
    {
        $insert = $this->db->insert($this->table,$data);
        if ( $insert > 0 ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function delete_soal($no_soal)
    {
        $this->db->where("no_soal",$no_soal);
        $delete = $this->db->delete($this->table);
        if ( $delete > 0 ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function update_soal($data)
    {
        $multiple = false;
        $condition = [
            "soal" => $data['soal'],
            "id_kategori" => $data['id_kategori']
        ];

        $check = $this->Clsglobal->check_availability($this->table,$condition);
        if ( $check == 2 ) {
            $get = $this->Clsglobal->get_data($this->table,$condition);
            if ( $get['no_soal'] == $data['no_soal'] ) {
                $multiple = false;
            } else {
                $multiple = true;
            }
        } else {
            $multiple = false;
        }

        if ( $multiple == false ) {
            $dataupdate = [
                "soal" => $data['soal'],
                "id_kategori" => $data['id_kategori'],
                "jenis" => $data['jenis']
            ];
            $this->db->where("no_soal", $data['no_soal']);
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

    public function import_soal($filename)
    {
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('./assets/excel_files/' . $filename);
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

        $data = array();

        $numrow = 1;
        foreach($sheet as $row){
            if($numrow > 2){
                array_push($data, array(
                    'no_soal' => $row['D'],
                    'id_kategori' => $row['E'],
                    'soal' => $row['F'],
                    'jenis' => $row['G'],
                ));
            }
            $numrow++;
        }

        $this->db->truncate($this->table);


        $output = 0;
        foreach ($data as $row) {
            if ( !($row['jenis'] == "check") ) {
                if ( !($row['jenis'] == "essay") ) {
                    $this->db->truncate($this->table);
                    unlink('./assets/excel_files/' . $filename);
                    return 4;
                } else {
                    $output = 0;
                }
            }

            if ( $output == 0 ) {
                $condition = ["id_kategori" => $row['id_kategori']];
                if ( $this->Clsglobal->check_availability("tblkategorisoal",$condition) == 3 ) {
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