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

    public function get_stok_akhir($kodeakun)
    {
    	$rowpersediaansistem = $this->db->query("
                                        select stokakhir from kartustok where kodeakun='".$kodeakun."' order by tglinsert desc, idkartustok desc limit 1
                                        ");
    	if ($rowpersediaansistem->num_rows()>0) {
    		$stokakhir = $rowpersediaansistem->row()->stokakhir;
    	}else{
    		$stokakhir = 0;
    	}

    	return $stokakhir;
    }

}

/* End of file App.php */
/* Location: ./application/models/App.php */