<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapneracasaldo extends MY_Controller {

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
        $data['menu']     = 'lapneracasaldo';
        $this->load->view('lapneracasaldo/listdata', $data);
    }

    public function cetak()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan = $this->uri->segment(3);
        $jenisakun = $this->uri->segment(4);
        $tglawal  = date('Y-m-d', strtotime($this->uri->segment(5)));
        $tglakhir = date('Y-m-d', strtotime($this->uri->segment(6)));

        $where = '';
        if ($jenisakun=='3') {
        	$query = "
        				SELECT akun.kodeakun, akun.namaakun, 
        					sum_jurnal_debet(akun.kodeakun, '$tglawal', '$tglakhir', 3) as debet, 
        					sum_jurnal_kredit(akun.kodeakun, '$tglawal', '$tglakhir', 3) as kredit
        					from akun where level = 3 
        				";
        }else{
        	$query = "
        				SELECT akun.kodeakun, akun.namaakun, 
        					sum_jurnal_debet(akun.kodeakun, '$tglawal', '$tglakhir', 4) as debet, 
        					sum_jurnal_kredit(akun.kodeakun, '$tglawal', '$tglakhir', 4) as kredit
        					from akun where level = 4 
        				";    	
        }

        $rsAkun = $this->db->query($query);
        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        
        $data['rowpengaturan'] = $rowpengaturan;
        $data['rsAkun']  = $rsAkun;
        $data['tglawal']  = $tglawal;
        $data['tglakhir'] = $tglakhir;
        if ($jeniscetakan=='pdf') {
	        $this->load->view('lapneracasaldo/cetak', $data);        	
        }else{
        	$data['namafile'] = 'download-laporan.xls';
	        $this->load->view('lapneracasaldo/excel', $data);
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

/* End of file Lapneracasaldo.php */
/* Location: ./application/controllers/Lapneracasaldo.php */