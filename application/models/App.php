<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Model {

	public function get_akun_barang()
    {
    	$nlenakun = strlen($this->session->userdata('kodeakunbarang'));
    	$this->db->where(' kodeakun like "%'.$this->session->userdata('kodeakunbarang').'%" and `level` = '.$this->session->userdata('levelmaxakunbarang'));
    	$this->db->order_by('namaakun');
    	return $this->db->get('v_akun');
    }

}

/* End of file App.php */
/* Location: ./application/models/App.php */