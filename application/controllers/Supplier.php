<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Supplier_model');
    }

    public function index()
    {
        $data['menu'] = 'supplier';
        $this->load->view('supplier/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idsupplier'] = '';        
        $data['menu'] = 'supplier';  
        $this->load->view('supplier/form', $data);
    }

    public function edit($idsupplier)
    {       
        $idsupplier = $this->encrypt->decode($idsupplier);

        if ($this->Supplier_model->get_by_id($idsupplier)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('supplier');
            exit();
        };
        $data['idsupplier'] =$idsupplier;        
        $data['menu'] = 'supplier';
        $this->load->view('supplier/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Supplier_model->get_datatables();
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
                $row[] = $rowdata->namasupplier;
                $row[] = $rowdata->alamatsupplier;
                $row[] = $rowdata->notelpsupplier.'<br>'.$rowdata->emailsupplier;
                $row[] = $rowdata->statusaktif;

                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'supplier/edit/'.$this->encrypt->encode($rowdata->idsupplier) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('supplier/delete/'.$this->encrypt->encode($rowdata->idsupplier) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>
                ';

                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Supplier_model->count_all(),
                        "recordsFiltered" => $this->Supplier_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idsupplier)
    {
        $idsupplier = $this->encrypt->decode($idsupplier);  
        $rsdata = $this->Supplier_model->get_by_id($idsupplier);
        if ($rsdata->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('supplier');
            exit();
        };

        $hapus = $this->Supplier_model->hapus($idsupplier);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('supplier');        

    }

    public function simpan()
    {       
        $idsupplier             = $this->input->post('idsupplier');
        $namasupplier        = $this->input->post('namasupplier');
        $alamatsupplier        = $this->input->post('alamatsupplier');
        $notelpsupplier        = $this->input->post('notelpsupplier');
        $emailsupplier        = $this->input->post('emailsupplier');
        $statusaktif        = $this->input->post('statusaktif');
        $created_at        = date('Y-m-d H:i:s');
        $updated_at        = date('Y-m-d H:i:s');
        $tglinsert          = date('Y-m-d H:i:s');

        if ( $idsupplier=='' ) {  
            $idsupplier = $this->db->query("SELECT create_idsupplier('".$namasupplier."','".date('Y-m-d')."') as idsupplier")->row()->idsupplier;

            $data = array(
                            'idsupplier'   => $idsupplier, 
                            'namasupplier'   => $namasupplier, 
                            'alamatsupplier'   => $alamatsupplier, 
                            'notelpsupplier'   => $notelpsupplier, 
                            'emailsupplier'   => $emailsupplier, 
                            'statusaktif'   => $statusaktif, 
                            'created_at'   => $created_at, 
                            'updated_at'   => $updated_at, 
                        );
            $simpan = $this->Supplier_model->simpan($data);      
        }else{ 
            $data = array(
                            'namasupplier'   => $namasupplier, 
                            'alamatsupplier'   => $alamatsupplier, 
                            'notelpsupplier'   => $notelpsupplier, 
                            'emailsupplier'   => $emailsupplier, 
                            'statusaktif'   => $statusaktif, 
                            'updated_at'   => $updated_at,                      
                        );
            $simpan = $this->Supplier_model->update($data, $idsupplier);
        }

        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('supplier');   
    }
    
    public function get_edit_data()
    {
        $idsupplier = $this->input->post('idsupplier');
        $RsData = $this->Supplier_model->get_by_id($idsupplier)->row();

        $data = array( 
                            'idsupplier'     =>  $RsData->idsupplier,  
                            'namasupplier'     =>  $RsData->namasupplier,  
                            'alamatsupplier'     =>  $RsData->alamatsupplier,  
                            'notelpsupplier'     =>  $RsData->notelpsupplier,  
                            'emailsupplier'     =>  $RsData->emailsupplier,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                            'created_at'     =>  $RsData->created_at,  
                            'updated_at'     =>  $RsData->updated_at,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Supplier.php */
/* Location: ./application/controllers/Supplier.php */