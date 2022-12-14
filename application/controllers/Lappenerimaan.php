<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lappenerimaan extends CI_Controller {

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
		$data['menu'] = 'lappenerimaan';
		$this->load->view('lappenerimaan/listdata', $data);
	}


	public function cetak()
    {
        error_reporting(0);
        $this->load->library('Pdf');

        $jeniscetakan       = $this->uri->segment(3);
        $tglawal 			= date('Y-m-d', strtotime($this->uri->segment(4))) ;
        $tglakhir 			= date('Y-m-d', strtotime($this->uri->segment(5))) ;
        $idgudang           = $this->uri->segment(6);
        $namasupplier       	= $this->uri->segment(7);
        $kodeakun       	= $this->uri->segment(8);

        $wherekodeakun = '';
        if ($kodeakun!='-') {
        	$wherekodeakun = " and kodeakun like '%".$kodeakun."%' ";
        }

        $whereidgudang = '';
        if ($idgudang!='-') {
        	$whereidgudang = " and idgudang like '%".$idgudang."%' ";
        }

        $wherenamasupplier = '';
        if ($namasupplier!='-') {
            $namasupplier = urldecode($namasupplier);
            $wherenamasupplier = " and namasupplier like '%".$namasupplier."%' ";
        }

        $rsdetail			= $this->db->query("
        								select * from v_penerimaandetail2 where tglpenerimaan between '$tglawal' and '$tglakhir' ".$whereidgudang." ".$wherekodeakun." ".$wherenamasupplier." order by v_penerimaandetail2.tglpenerimaan, v_penerimaandetail2.idpenerimaan, v_penerimaandetail2.kodeakun
        							");
        

        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rslaporan'] = $rsdetail;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['tahunperiode'] = date('Y', strtotime($tglawal));
        
        if (strtoupper($jeniscetakan)=='EXCEL') {
            $this->load->view('lappenerimaan/excel', $data);
        }else{
            $this->load->view('lappenerimaan/cetak', $data);
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

    public function getnamasupplier()
    {
        $cari= $this->input->post('cari');
        $query = "
            SELECT namasupplier FROM v_penerimaan 
                    WHERE namasupplier LIKE '%".$cari."%'
                    GROUP BY namasupplier order by namasupplier";

        $res = $this->db->query($query);        
        $result = array();

        foreach ($res->result() as $row) {
            if (!empty($row->namasupplier)) {                
                array_push($result, array(
                    'namasupplier' => $row->namasupplier,
                ));
            }
        }
        echo json_encode($result);
    }

}

/* End of file Lappenerimaan.php */
/* Location: ./application/controllers/Lappenerimaan.php */