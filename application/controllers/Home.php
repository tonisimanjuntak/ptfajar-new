<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->authlogin();
        $this->load->model('Home_model');
	}

	public function index()
	{
		$data["menu"] = "home";	
		$this->load->view("home", $data);
	}


	public function getinfobox()
    {

    	$rowpengaturan = $this->db->query("select * from pengaturan")->row();

    	$kodeakunbarang = $rowpengaturan->kodeakunbarang;
    	$nlen = strlen($kodeakunbarang);

    	$levelmaxakunbarang = $rowpengaturan->levelmaxakunbarang;

        $sebulan = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))) );

        $jumlahstokhabis = $this->db->query("SELECT COUNT(*) AS jumlahstokhabis FROM akun where left(kodeakun, ".$nlen.")  = '$kodeakunbarang' and `level` = $levelmaxakunbarang and jumlahpersediaan=0")->row()->jumlahstokhabis;

        $jumlahbarangbelumterkirim = $this->db->query("SELECT COUNT(*) AS jumlahbarangbelumterkirim FROM v_pengeluaranstatusterkirim where statusterkirim='Belum Terkirim'")->row()->jumlahbarangbelumterkirim;

        $data = array(
                    'jumlahstokhabis' => $jumlahstokhabis, 
                    'jumlahbarangbelumterkirim' => $jumlahbarangbelumterkirim, 
                );

        echo json_encode($data);
    }


    public function getchartbarangkeluar()
    {	
    	$kodeakun = $this->input->get('kodeakun');
        $bulanbarangkeluar = array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des');
        $dataSet = array();


        if (empty($kodeakun)) {
        	
	    	$rsakun = $this->Home_model->get_akun_barang();
	        foreach ($rsakun->result() as $rowakun) {
	        	
		        $totalkeluar_ma = array();
		        $jumlahkeluar = 0;
		        $i=1;
		        $rsbarangkeluar = $this->Home_model->getchartakun(date('Y'), $rowakun->kodeakun);
		        foreach ($rsbarangkeluar->result()[0] as $value) {
		        	$jumlahkeluar += $value;
		            $totalkeluar_ma[] = $jumlahkeluar/$i;
		            $i++;
		        }
		        $color = $this->rand_color();
		        $dataSetTemp = array(
		        				'type' => 'line',
					            'label' => $rowakun->namaakun,
					            'data' => $totalkeluar_ma,
					            'backgroundColor' => 'transparent',
					            'borderColor' => $color,
					            'pointBorderColor' => $color,
					            'pointBackgroundColor' => $color,
					            'fill' => false
		        			);
		        array_push($dataSet, $dataSetTemp);
	        }

        }else{
        	$rowakun = $this->db->query("select * from akun where kodeakun='$kodeakun'")->row();
        	$totalkeluar_ma = array();
	        $jumlahkeluar = 0;
	        $i=1;
	        $rsbarangkeluar = $this->Home_model->getchartakun(date('Y'), $kodeakun);
	        foreach ($rsbarangkeluar->result()[0] as $value) {
	        	$jumlahkeluar += $value;
	            $totalkeluar_ma[] = $jumlahkeluar/$i;
	            $i++;
	        }
	        $color = $this->rand_color();
	        $dataSetTemp = array(
	        				'type' => 'line',
				            'label' => $rowakun->namaakun,
				            'data' => $totalkeluar_ma,
				            'backgroundColor' => 'transparent',
				            'borderColor' => $color,
				            'pointBorderColor' => $color,
				            'pointBackgroundColor' => $color,
				            'fill' => false
	        			);
	        array_push($dataSet, $dataSetTemp);

        }

        
        echo json_encode(array('bulanbarangkeluar' => $bulanbarangkeluar, 'dataSet' => $dataSet));
    }


    function rand_color() {
	    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */