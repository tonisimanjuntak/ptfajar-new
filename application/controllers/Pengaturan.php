<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Pengaturan_model');
        $this->load->library('image_lib');
    }

    public function index()
    {       
        $rowpengaturan = $this->Pengaturan_model->get_all()->row();
        $data['rowpengaturan'] = $rowpengaturan;        
        $data['menu'] = 'pengaturan';  
        $this->load->view('pengaturan/form', $data);
    }

    public function simpan()
    {       
        $namaperusahaan        = $this->input->post('namaperusahaan');
        $alamatperusahaan        = $this->input->post('alamatperusahaan');
        $notelp        = $this->input->post('notelp');
        $tglinsert          = date('Y-m-d H:i:s');


        $file_lama = $this->input->post('file_lama');
        $foto = $this->update_upload_foto($_FILES, "file", $file_lama);

        $data = array(
                        'logoperusahaan'   => $foto, 
                        'namaperusahaan'   => $namaperusahaan, 
                        'alamatperusahaan'   => $alamatperusahaan, 
                        'notelp'   => $notelp,                      
                    );
        $simpan = $this->Pengaturan_model->update($data);

        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('pengaturan');   
    }
    

    public function upload_foto($file, $nama)
    {

        if (!empty($file[$nama]['name'])) {
            $config['upload_path']          = 'uploads/pengaturan/';
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
            $config['upload_path']          = 'uploads/pengaturan/';
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

/* End of file Pengaturan.php */
/* Location: ./application/controllers/Pengaturan.php */