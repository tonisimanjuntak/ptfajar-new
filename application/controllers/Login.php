<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();      
        $this->load->model('Login_model'); 
    }

    public function keluar()
    {
        $this->session->sess_destroy(); 
        redirect('login');
    }

    public function index()
    { 
        $idpengguna = $this->session->userdata('idpengguna');
        if (!empty($idpengguna)) {
            redirect(site_url());
        }else{
            $rowpengaturan = $this->db->query("select * from pengaturan")->row();
            if (empty($rowpengaturan->logoperusahaan)) {
                $data['logoperusahaan'] = base_url('images/logo-default.jpg');
            }else{
                $data['logoperusahaan'] = base_url('uploads/pengaturan/'.$rowpengaturan->logoperusahaan);                
            }
            $data['rowpengaturan'] = $rowpengaturan;
            $this->load->view('login', $data);     
        }

    }

    public function cek_login()
    {
        $username = trim($this->input->post('username'));
        $password = trim($this->input->post('password'));

        if (empty($username) || empty($password)) {
            $pesan = '<script>swal("Gagal!", "Username atau password tidak boleh kosong.", "info")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('login');
        }else{
            $kirim = $this->Login_model->cek_login($username, md5($password));
            if ($kirim->num_rows() > 0) {
                $result = $kirim->row();

                if (!empty($result->fotouser)) {
                    $fotouser = base_url('uploads/pengguna/'.$result->fotouser);
                }else{
                    $fotouser = base_url('images/no-user-images.png');
                }

                $rowpengaturan = $this->db->query("select * from pengaturan limit 1")->row();
                if (empty($rowpengaturan->logoperusahaan)) {
                    $logoperusahaan = base_url('images/logo-default.jpg');
                  }else{
                    $logoperusahaan = base_url('uploads/pengaturan/'.$rowpengaturan->logoperusahaan);
                  }

                $data = array(
                    'idpengguna' => $result->idpengguna,
                    'namapengguna' => $result->namapengguna,
                    'fotouser' => $fotouser,
                    'logoperusahaan' => $logoperusahaan,
                    'namaperusahaan' => $rowpengaturan->namaperusahaan,
                    'alamatperusahaan' => $rowpengaturan->alamatperusahaan,
                    'notelp' => $rowpengaturan->notelp,
                );
                                
                $this->session->set_userdata( $data );  
                redirect(site_url());
            }else{
                $pesan = '<script>swal("Gagal!", "Kombinasi username dan password anda salah!", "error")</script>';
                $this->session->set_flashdata('pesan', $pesan);
                redirect('login');
            }

        }
    }

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */