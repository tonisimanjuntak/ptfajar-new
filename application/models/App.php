<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Model {

	public function get_akun_barang()
    {
    	$nlenakun = strlen($this->session->userdata('kodeakunbarang'));
    	$this->db->where(' left(kodeakun, '.$nlenakun.') = "'.$this->session->userdata('kodeakunbarang').'" and `level` = '.$this->session->userdata('levelmaxakunbarang'));
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

    public function get_hargajual_ma($kodeakun, $tahun)
    {
        return $this->db->query("select get_hargajual_ma('".$kodeakun."', '".$tahun."') as hargajual")->row()->hargajual;
    }

    public function upload_file_importexcel($filename){
        $this->load->library('upload'); 
        $config['upload_path'] = 'uploads/importexcel/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = $filename;
      
        $this->upload->initialize($config); 
        if($this->upload->do_upload('file')){ 
          $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
          return $return;
        }else{
          $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
          return $return;
        }
    }

    public function hapusSemuaTransaksi()
    {
        $this->db->trans_begin();

        $this->db->query("delete from kartustok");

        $this->db->query("delete from jurnaldetail");
        $this->db->query("delete from jurnal");

        $this->db->query("delete from pengeluaranstatusterkirim");
        $this->db->query("delete from pengeluaran_tempdetail");
        $this->db->query("delete from pengeluaran_temp");
        $this->db->query("delete from pengeluarandetail");
        $this->db->query("delete from pengeluaran");

        $this->db->query("delete from penerimaan_tempdetail");
        $this->db->query("delete from penerimaan_temp");
        $this->db->query("delete from penerimaandetail");
        $this->db->query("delete from penerimaan");
        
        $this->db->query("delete from stokopnamedetail");
        $this->db->query("delete from stokopname");

        $this->db->query("delete from saldoawaldetail");
        $this->db->query("delete from saldoawal");

        $this->db->query("delete from postingjurnal");

        $this->db->query("delete from penandatangan");

        $this->db->query("delete from akun");

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

}

/* End of file App.php */
/* Location: ./application/models/App.php */