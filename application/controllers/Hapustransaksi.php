<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hapustransaksi extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
    }

    public function index()
    {
        $data['menu'] = 'hapustransaksi';
        $this->load->view('hapustransaksi', $data);
    }   

    public function hapus()
    {
    	$hapus = $this->App->hapusSemuaTransaksi();
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Silahkan coba lagi!", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('hapustransaksi'); 
    }

}

/* End of file Hapustransaksi.php */
/* Location: ./application/controllers/Hapustransaksi.php */