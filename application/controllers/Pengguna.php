<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Pengguna_model');
        $this->load->library('image_lib');
    }  

    public function index()
    {
        $data['menu'] = 'pengguna';
        $this->load->view('pengguna/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idpengguna'] = '';        
        $data['menu'] = 'pengguna';  
        $this->load->view('pengguna/form', $data);
    }

    public function edit($idpengguna)
    {       
        $idpengguna = $this->encrypt->decode($idpengguna);

        if ($this->Pengguna_model->get_by_id($idpengguna)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengguna');
            exit();
        };
        $data['idpengguna'] =$idpengguna;        
        $data['menu'] = 'pengguna';
        $this->load->view('pengguna/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Pengguna_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                if (!empty($rowdata->fotouser)) {
                    $fotouser = '<img src="'.base_url('uploads/pengguna/'.$rowdata->fotouser).'" alt="" style="width: 80%;">';
                }else{
                    $fotouser = '<img src="'.base_url('images/no-user-images.png').'" alt="" style="width: 80%;">';
                }
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $fotouser;
                $row[] = $rowdata->namapengguna;
                $row[] = $rowdata->tempatlahir.'/ '.$rowdata->tgllahir.'<br>'.$rowdata->jeniskelamin;
                $row[] = $rowdata->nomorhp.'<br>'.$rowdata->email;
                $row[] = $rowdata->username;
                
                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'pengguna/edit/'.$this->encrypt->encode($rowdata->idpengguna) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('pengguna/delete/'.$this->encrypt->encode($rowdata->idpengguna) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>
                ';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pengguna_model->count_all(),
                        "recordsFiltered" => $this->Pengguna_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idpengguna)
    {
        $idpengguna = $this->encrypt->decode($idpengguna);  
        $rsdata = $this->Pengguna_model->get_by_id($idpengguna);
        if ($rsdata->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengguna');
            exit();
        };

        $hapus = $this->Pengguna_model->hapus($idpengguna);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('pengguna');        

    }

    public function simpan()
    {       
        $idpengguna             = $this->input->post('idpengguna');
        $namapengguna        = $this->input->post('namapengguna');
        $jeniskelamin        = $this->input->post('jeniskelamin');
        $nomorhp        = $this->input->post('nomorhp');
        $tempatlahir        = $this->input->post('tempatlahir');
        $tgllahir        = $this->input->post('tgllahir');
        $email        = $this->input->post('email');
        
        $username        = $this->input->post('username');
        $password        = $this->input->post('password');
        $password2        = $this->input->post('password2');
        $created_at        = date('Y-m-d H:i:s');
        $updated_at        = date('Y-m-d H:i:s');

        if (!empty($password) && $password <> $password2) {
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Ulangi password tidak sama!
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengguna'); 
        }

        if ( $idpengguna=='' ) {  

            $idpengguna = $this->db->query("SELECT create_idpengguna('".$namapengguna."') as idpengguna")->row()->idpengguna;

            $foto               = $this->upload_foto($_FILES, "file");   

            $data = array(
                            'idpengguna'   => $idpengguna, 
                            'namapengguna'   => $namapengguna, 
                            'jeniskelamin'   => $jeniskelamin, 
                            'nomorhp'   => $nomorhp, 
                            'tempatlahir'   => $tempatlahir, 
                            'tgllahir'   => $tgllahir, 
                            'email'   => $email, 
                            'fotouser'   => $foto, 
                            'username'   => $username, 
                            'password'   => md5($password), 
                            'created_at'   => $created_at, 
                            'updated_at'   => $updated_at, 
                        );
            $simpan = $this->Pengguna_model->simpan($data);      
        }else{ 

            $file_lama = $this->input->post('file_lama');
            $foto = $this->update_upload_foto($_FILES, "file", $file_lama);

            if (!empty($password)) {
                $data = array(
                            'namapengguna'   => $namapengguna, 
                            'jeniskelamin'   => $jeniskelamin, 
                            'nomorhp'   => $nomorhp, 
                            'tempatlahir'   => $tempatlahir, 
                            'tgllahir'   => $tgllahir, 
                            'email'   => $email, 
                            'fotouser'   => $foto, 
                            'username'   => $username, 
                            'password'   => md5($password), 
                            'updated_at'   => $updated_at,                      
                        );
            }else{
                $data = array(
                            'namapengguna'   => $namapengguna, 
                            'jeniskelamin'   => $jeniskelamin, 
                            'nomorhp'   => $nomorhp, 
                            'tempatlahir'   => $tempatlahir, 
                            'tgllahir'   => $tgllahir, 
                            'email'   => $email, 
                            'fotouser'   => $foto, 
                            'username'   => $username, 
                            'updated_at'   => $updated_at,                      
                        );
            }
            
            $simpan = $this->Pengguna_model->update($data, $idpengguna);
        }

        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('pengguna');   
    }
    
    public function get_edit_data()
    {
        $idpengguna = $this->input->post('idpengguna');
        $RsData = $this->Pengguna_model->get_by_id($idpengguna)->row();

        $data = array( 
                            'idpengguna'     =>  $RsData->idpengguna,  
                            'namapengguna'     =>  $RsData->namapengguna,  
                            'jeniskelamin'     =>  $RsData->jeniskelamin,  
                            'nomorhp'     =>  $RsData->nomorhp,  
                            'tempatlahir'     =>  $RsData->tempatlahir,  
                            'tgllahir'     =>  $RsData->tgllahir,  
                            'email'     =>  $RsData->email,  
                            'fotouser'     =>  $RsData->fotouser,  
                            'username'     =>  $RsData->username,  
                            'password'     =>  $RsData->password,  
                            'created_at'     =>  $RsData->created_at,  
                            'updated_at'     =>  $RsData->updated_at,  
                        );

        echo(json_encode($data));
    }


    public function upload_foto($file, $nama)
    {

        if (!empty($file[$nama]['name'])) {
            $config['upload_path']          = 'uploads/pengguna/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['remove_space']         = TRUE;
            $config['max_size']             = '2000KB';

            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload($nama)) {
                $foto = $this->upload->data('file_name');
                $size = $this->upload->data('file_size');
                $ext  = $this->upload->data('file_ext'); 
             }else{
                 $foto = "";
             }

        }else{
            $foto = "";
        }
        return $foto;
    }

    public function update_upload_foto($file, $nama, $file_lama)
    {
        if (!empty($file[$nama]['name'])) {
            $config['upload_path']          = 'uploads/pengguna/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['remove_space']         = TRUE;
            $config['max_size']            = '2000KB';
            

            $this->load->library('upload', $config);           
            if ($this->upload->do_upload($nama)) {
                $foto = $this->upload->data('file_name');
                $size = $this->upload->data('file_size');
                $ext  = $this->upload->data('file_ext'); 
            }else{
                $foto = $file_lama;
            }          
        }else{          
            $foto = $file_lama;
        }

        return $foto;
    }

}

/* End of file Pengguna.php */
/* Location: ./application/controllers/Pengguna.php */