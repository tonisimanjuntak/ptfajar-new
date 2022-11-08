<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapjurnal extends CI_Controller {

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
		$data['rsakun3'] = $rsakun3;
		$data['menu'] = 'lapjurnal';
		$this->load->view('lapjurnal/listdata', $data);
	}


	public function cetak_old()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan       = $this->uri->segment(3);
        $tglawal 			= date('Y-m-d', strtotime($this->uri->segment(4))) ;
        $tglakhir 			= date('Y-m-d', strtotime($this->uri->segment(5))) ;

        $rsdetailjurnal			= $this->db->query("
        								select * from v_jurnaldetail where tgljurnal between '$tglawal' and '$tglakhir' order by v_jurnaldetail.tgljurnal, v_jurnaldetail.idjurnal, v_jurnaldetail.nourut
        							");
        

        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rslaporan'] = $rsdetailjurnal;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['tahunperiode'] = date('Y', strtotime($tglawal));
        
        if (strtoupper($jeniscetakan)=='EXCEL') {
            $this->load->view('lapjurnal/excel', $data);
        }else{
            $this->load->view('lapjurnal/cetak', $data);
        }
    }

    public function cetak()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan       = $this->uri->segment(3);
        $tglawal 			= date('Y-m-d', strtotime($this->uri->segment(4))) ;
        $tglakhir 			= date('Y-m-d', strtotime($this->uri->segment(5))) ;

        $rsdetailjurnal			= $this->db->query("
        								select * from v_jurnaldetail where tgljurnal between '$tglawal' and '$tglakhir' order by v_jurnaldetail.tgljurnal, v_jurnaldetail.idjurnal, v_jurnaldetail.nourut
        							");
        

        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rslaporan'] = $rsdetailjurnal;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['tahunperiode'] = date('Y', strtotime($tglawal));
        
        if (strtoupper($jeniscetakan)=='EXCEL') {
            $this->load->view('lapjurnal/excel', $data);
        }else{
            $this->load->view('lapjurnal/cetak', $data);
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

/* End of file Lapjurnal.php */
/* Location: ./application/controllers/Lapjurnal.php */