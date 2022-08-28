<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartustok extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('Akun3_model');
        $this->load->model('Akun_model');
        // $this->load->model('App');
	}

	public function index()
	{
		$rsakun3 = $this->Akun_model->get_all();
		$rowpengaturan = $this->db->query("select * from pengaturan")->row();

        $data['rowpengaturan'] = $rowpengaturan;
		$data['rsakun3'] = $rsakun3;
		$data['menu'] = 'kartustok';
		$this->load->view('kartustok/listdata', $data);
	}


	public function cetak()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan       = $this->uri->segment(3);
        $tglawal 			= date('Y-m-d', strtotime($this->uri->segment(4))) ;
        $tglakhir 			= date('Y-m-d', strtotime($this->uri->segment(5))) ;
        $kodeakun       	= $this->uri->segment(6);

        
        $wherekodeakun = " and kodeakun like '%".$kodeakun."%' ";
        
        $rskartustok			= $this->db->query("
        								select * from kartustok where CONVERT(tglinsert, DATE) between '$tglawal' and '$tglakhir' ".$wherekodeakun." order by kartustok.tglinsert, kartustok.idkartustok
        							");


        $rowpengaturan = $this->db->query("select * from pengaturan")->row();
        $rowakun = $this->Akun_model->get_by_id($kodeakun)->row();

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rowakun'] = $rowakun;
        $data['rskartustok'] = $rskartustok;
        $data['kodeakun'] = $kodeakun;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['tahunperiode'] = date('Y', strtotime($tglawal));
        
        if (strtoupper($jeniscetakan)=='EXCEL') {
            $this->load->view('kartustok/excel', $data);
        }else{
            $this->load->view('kartustok/cetak', $data);
        }
    }


	public function get_akun4()
	{
		$kdakun3 = $this->input->get('kdakun3');
		$rsakun4 = $this->db->query("select * from akun4 where kdakun3='$kdakun3' order by kdakun4");
		$arrAkun4 = array();

		if ($rsakun4->num_rows()>0) {
			foreach ($rsakun4->result() as $rowakun4) {
				array_push($arrAkun4, array(
											'kdakun4' => $rowakun4->kdakun4,
											'namaakun4' => $rowakun4->namaakun4
											));
			}
		}
		echo json_encode($arrAkun4);
	}

}

/* End of file Kartustok.php */
/* Location: ./application/controllers/Kartustok.php */