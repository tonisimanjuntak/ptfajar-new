<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Gudang_model');
    }

    public function index()
    {
        $data['menu'] = 'gudang';
        $this->load->view('gudang/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idgudang'] = '';        
        $data['menu'] = 'gudang';  
        $this->load->view('gudang/form', $data);
    }

    public function edit($idgudang)
    {       
        $idgudang = $this->encrypt->decode($idgudang);

        if ($this->Gudang_model->get_by_id($idgudang)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('gudang');
            exit();
        };
        $data['idgudang'] =$idgudang;        
        $data['menu'] = 'gudang';
        $this->load->view('gudang/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Gudang_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                if ($rowdata->statusaktif=='Aktif') {
                    $statusaktif = '<span class="badge badge-success">'.$rowdata->statusaktif.'</span>';
                }else{
                    $statusaktif = '<span class="badge badge-danger">'.$rowdata->statusaktif.'</span>';
                }
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->namagudang;
                $row[] = $rowdata->alamatgudang;
                $row[] = $rowdata->notelpgudang.'<br>'.$rowdata->emailgudang;
                $row[] = $rowdata->statusaktif;

                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'gudang/edit/'.$this->encrypt->encode($rowdata->idgudang) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('gudang/delete/'.$this->encrypt->encode($rowdata->idgudang) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>
                ';

                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Gudang_model->count_all(),
                        "recordsFiltered" => $this->Gudang_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idgudang)
    {
        $idgudang = $this->encrypt->decode($idgudang);  
        $rsdata = $this->Gudang_model->get_by_id($idgudang);
        if ($rsdata->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('gudang');
            exit();
        };

        $hapus = $this->Gudang_model->hapus($idgudang);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('gudang');        

    }

    public function simpan()
    {       
        $idgudang             = $this->input->post('idgudang');
        $namagudang        = $this->input->post('namagudang');
        $alamatgudang        = $this->input->post('alamatgudang');
        $notelpgudang        = $this->input->post('notelpgudang');
        $emailgudang        = $this->input->post('emailgudang');
        $statusaktif        = $this->input->post('statusaktif');
        $created_at        = date('Y-m-d H:i:s');
        $updated_at        = date('Y-m-d H:i:s');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idgudang=='' ) {  
            $idgudang = $this->db->query("SELECT create_idgudang('".$namagudang."','".date('Y-m-d')."') as idgudang")->row()->idgudang;

            $data = array(
                            'idgudang'   => $idgudang, 
                            'namagudang'   => $namagudang, 
                            'alamatgudang'   => $alamatgudang, 
                            'notelpgudang'   => $notelpgudang, 
                            'emailgudang'   => $emailgudang, 
                            'statusaktif'   => $statusaktif, 
                            'created_at'   => $created_at, 
                            'updated_at'   => $updated_at, 
                        );
            $simpan = $this->Gudang_model->simpan($data);      
        }else{ 
            $data = array(
                            'namagudang'   => $namagudang, 
                            'alamatgudang'   => $alamatgudang, 
                            'notelpgudang'   => $notelpgudang, 
                            'emailgudang'   => $emailgudang, 
                            'statusaktif'   => $statusaktif, 
                            'updated_at'   => $updated_at,                      
                        );
            $simpan = $this->Gudang_model->update($data, $idgudang);
        }

        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('gudang');   
    }
    
    public function get_edit_data()
    {
        $idgudang = $this->input->post('idgudang');
        $RsData = $this->Gudang_model->get_by_id($idgudang)->row();

        $data = array( 
                            'idgudang'     =>  $RsData->idgudang,  
                            'namagudang'     =>  $RsData->namagudang,  
                            'alamatgudang'     =>  $RsData->alamatgudang,  
                            'notelpgudang'     =>  $RsData->notelpgudang,  
                            'emailgudang'     =>  $RsData->emailgudang,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                            'created_at'     =>  $RsData->created_at,  
                            'updated_at'     =>  $RsData->updated_at,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Gudang.php */
/* Location: ./application/controllers/Gudang.php */