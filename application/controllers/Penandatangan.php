<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penandatangan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Penandatangan_model');
    }

    public function index()
    {
        $data['menu'] = 'Penandatangan';
        $this->load->view('penandatangan/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idpenandatangan'] = '';        
        $data['menu'] = 'Penandatangan';  
        $this->load->view('penandatangan/form', $data);
    }

    public function edit($idpenandatangan)
    {       
        $idpenandatangan = $this->encrypt->decode($idpenandatangan);

        if ($this->Penandatangan_model->get_by_id($idpenandatangan)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', );
            redirect('Penandatangan');
            exit();
        };
        $data['idpenandatangan'] =$idpenandatangan;        
        $data['menu'] = 'Penandatangan';
        $this->load->view('penandatangan/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Penandatangan_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->namapenandatangan;
                $row[] = $rowdata->nip;
                $row[] = $rowdata->jabatan;
                $row[] = $rowdata->statusaktif;
                $row[] = '<a href="'.site_url( 'Penandatangan/edit/'.$this->encrypt->encode($rowdata->idpenandatangan) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('Penandatangan/delete/'.$this->encrypt->encode($rowdata->idpenandatangan) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Penandatangan_model->count_all(),
                        "recordsFiltered" => $this->Penandatangan_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idpenandatangan)
    {
        $idpenandatangan = $this->encrypt->decode($idpenandatangan);  
        $rsdata = $this->Penandatangan_model->get_by_id($idpenandatangan);
        if ($rsdata->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('Penandatangan');
            exit();
        };

        $hapus = $this->Penandatangan_model->hapus($idpenandatangan);
        if ($hapus) {       
            $pesan = '<div>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Berhasil!</strong> Data berhasil dihapus!
                        </div>
                    </div>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Data gagal dihapus karena sudah digunakan! <br>
                        </div>
                    </div>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('Penandatangan');        

    }

    public function simpan()
    {       
        $idpenandatangan             = $this->input->post('idpenandatangan');
        $namapenandatangan        = $this->input->post('namapenandatangan');
        $nip        = $this->input->post('nip');
        $jabatan        = $this->input->post('jabatan');
        $statusaktif        = $this->input->post('statusaktif');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idpenandatangan=='' ) {  
            $data = array(
                            'namapenandatangan'   => $namapenandatangan, 
                            'nip'   => $nip, 
                            'jabatan'   => $jabatan, 
                            'statusaktif'   => $statusaktif, 
                        );
            $simpan = $this->Penandatangan_model->simpan($data);      
        }else{ 

            $data = array(
                            'namapenandatangan'   => $namapenandatangan, 
                            'nip'   => $nip, 
                            'jabatan'   => $jabatan, 
                            'statusaktif'   => $statusaktif,                      );
            $simpan = $this->Penandatangan_model->update($data, $idpenandatangan);
        }

        if ($simpan) {
            $pesan = '<div>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Berhasil!</strong> Data berhasil disimpan!
                        </div>
                    </div>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Data gagal disimpan! <br>
                            Pesan Error : '.$eror['code'].' '.$eror['message'].'
                        </div>
                    </div>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('Penandatangan');   
    }
    
    public function get_edit_data()
    {
        $idpenandatangan = $this->input->post('idpenandatangan');
        $RsData = $this->Penandatangan_model->get_by_id($idpenandatangan)->row();

        $data = array( 
                            'idpenandatangan'     =>  $RsData->idpenandatangan,  
                            'namapenandatangan'     =>  $RsData->namapenandatangan,  
                            'nip'     =>  $RsData->nip,  
                            'jabatan'     =>  $RsData->jabatan,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Penandatangan.php */
/* Location: ./application/controllers/Penandatangan.php */