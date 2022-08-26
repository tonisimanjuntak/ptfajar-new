<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function authlogin()
    {
        $idpengguna = $this->session->userdata('idpengguna');
        if (empty($idpengguna)) {
            // $pesan = '<script>swal("Session Berakhir!", "Session telah berakhir, silahkan login kembali untuk melanjutkan.", "info")</script>';
            // $this->session->set_flashdata('pesan', $pesan);
            redirect('login'); 
            exit();
        }
    } 

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */