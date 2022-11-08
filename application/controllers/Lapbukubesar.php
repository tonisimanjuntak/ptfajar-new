<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapbukubesar extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
    }

    public function index()
    {
        $tglawal    = $this->input->post('tglawal');
        $tglakhir   = $this->input->post('tglakhir');
        $id_pegawai = $this->session->userdata('id_pegawai');

        if (empty($tglawal)) {
            $tglawal  = date('Y-m-d');
            $tglakhir = date('Y-m-d');
        }

        $data['tglawal']  = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['menu']     = 'lapbukubesar';
        $this->load->view('lapbukubesar/listdata', $data);
    }

    public function cetak()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan = $this->uri->segment(3);
        $jenisakun = $this->uri->segment(4);
        $kodeakun = $this->uri->segment(5);
        $tglawal  = date('Y-m-d', strtotime($this->uri->segment(6)));
        $tglakhir = date('Y-m-d', strtotime($this->uri->segment(7)));

        $where = " where tgljurnal between '$tglawal' and '$tglakhir'";
        $where_lalu = " where tgljurnal < '$tglawal' and year(tgljurnal)='".date('Y', strtotime($tglawal))."'";

		$namaakun = $this->db->query("select namaakun from akun where kodeakun='$kodeakun'")->row()->namaakun;

        if ($jenisakun=='3') {
        	$where .= " and parentakun='$kodeakun'";        	
        	$where_lalu .= " and parentakun='$kodeakun'";        	
        }else{
        	$where .= " and kodeakun='$kodeakun'";        	
        	$where_lalu .= " and kodeakun='$kodeakun'";        	
        }


        $order_by = " order by tgljurnal asc";
        $query = "select * from v_jurnaldetail ". $where.$order_by;
        $rsjurnal = $this->db->query($query);
        
        $query_lalu = "select sum(debet) as debet, sum(kredit) as kredit from v_jurnaldetail ". $where_lalu;
        $rowjurnal_lalu = $this->db->query($query_lalu)->row();

        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        
        $data['rowpengaturan'] = $rowpengaturan;
        $data['rsjurnal']  = $rsjurnal;
        $data['rowjurnal_lalu']  = $rowjurnal_lalu;
        $data['kodeakun']  = $kodeakun;
        $data['namaakun']  = $namaakun;
        $data['tglawal']  = $tglawal;
        $data['tglakhir'] = $tglakhir;
        if ($jeniscetakan=='pdf') {
	        $this->load->view('lapbukubesar/cetak', $data);        	
        }else{
        	$data['namafile'] = 'Lap Buku Besar.xls';
	        $this->load->view('lapbukubesar/excel', $data);
        }
    }

    public function get_akun4()
    {
    	$rsakun = $this->db->query("select * from akun where level=4 order by kodeakun");
    	echo json_encode($rsakun->result() );
    }

    public function get_akun3()
    {
    	$rsakun = $this->db->query("select * from akun where level=3 order by kodeakun");
    	echo json_encode($rsakun->result() );
    }

}

/* End of file Lapbukubesar.php */
/* Location: ./application/controllers/Lapbukubesar.php */