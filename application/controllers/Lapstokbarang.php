<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapstokbarang extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->authlogin();
        $this->load->model('Akun_model');
        // $this->load->model('App');
	}

	public function index()
	{
		$rsakun3 = $this->Akun_model->get_all();
		$rowpengaturan = $this->db->query("select * from pengaturan")->row();

        $data['rowpengaturan'] = $rowpengaturan;
		$data['rsakun3'] = $rsakun3;
		$data['menu'] = 'lapstokbarang';
		$this->load->view('lapstokbarang/listdata', $data);
	}


	public function cetak()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan       = $this->uri->segment(3);
        $kodeakun       	= $this->uri->segment(4);

        $wherekodeakun = "";
        if ($kodeakun!="-") {
	        $wherekodeakun = " and kodeakun like '%".$kodeakun."%' ";
        }
        

        $rowpengaturan = $this->db->query("select * from pengaturan")->row();
        $nlen = strlen($rowpengaturan->kodeakunbarang);
      	$rsstok = $this->db->query("select * from v_akun_level_max where left(kodeakun, ".$nlen.")  = '".$rowpengaturan->kodeakunbarang."' ".$wherekodeakun." order by kodeakun");

        $rowakun = $this->Akun_model->get_by_id($kodeakun)->row();

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rowakun'] = $rowakun;
        $data['rsstok'] = $rsstok;
        $data['kodeakun'] = $kodeakun;
        
        if (strtoupper($jeniscetakan)=='EXCEL') {
            $this->load->view('lapstokbarang/excel', $data);
        }else{
            $this->load->view('lapstokbarang/cetak', $data);
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

/* End of file Lapstokbarang.php */
/* Location: ./application/controllers/Lapstokbarang.php */