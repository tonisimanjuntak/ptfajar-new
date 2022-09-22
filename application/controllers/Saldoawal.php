<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldoawal extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Saldoawal_model');
       	$this->load->model('Akun_model');
    }

    public function index()
    {
        $data['menu'] = 'saldoawal';
        $this->load->view('saldoawal/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idsaldoawal'] = '';        
        $data['menu'] = 'saldoawal';  
        $this->load->view('saldoawal/form', $data);
    }

    public function edit($idsaldoawal)
    {       
    	$idsaldoawal = $this->encrypt->decode($idsaldoawal);
    	if ($this->Saldoawal_model->get_by_id($idsaldoawal)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('saldoawal');
            exit();
        };

        $data['idsaldoawal'] = $idsaldoawal;        
        $data['menu'] = 'saldoawal';  
        $this->load->view('saldoawal/form', $data);
    }

    public function cetakso($idsaldoawal)
    {       
        $idsaldoawal = $this->encrypt->decode($idsaldoawal);

        if ($this->Saldoawal_model->get_by_id($idsaldoawal)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('saldoawal');
            exit();
        };

        $rowstokopname = $this->db->query("
        								select * from v_stokopname where idsaldoawal='$idsaldoawal'
        							")->row();
        $rsstokopnamedetail = $this->db->query("
        								select * from v_stokopnamedetail where idsaldoawal='$idsaldoawal'
        							");
        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        error_reporting(0);
        $this->load->library('Pdf');

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rowstokopname'] = $rowstokopname;
        $data['rsstokopnamedetail'] = $rsstokopnamedetail;
        $this->load->view('saldoawal/cetakso', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Saldoawal_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->tahunanggaran;
                $row[] = $rowdata->namajenisakun;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->namapengguna;
                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'saldoawal/edit/'.$this->encrypt->encode($rowdata->idsaldoawal) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('saldoawal/delete/'.$this->encrypt->encode($rowdata->idsaldoawal) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>
                ';

                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Saldoawal_model->count_all(),
                        "recordsFiltered" => $this->Saldoawal_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($idsaldoawal)
    {
        $idsaldoawal = $this->encrypt->decode($idsaldoawal);  
        $rsdata = $this->Saldoawal_model->get_by_id($idsaldoawal);
        if ($rsdata->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('saldoawal');
            exit();
        };

        $hapus = $this->Saldoawal_model->hapus($idsaldoawal);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }
        $this->session->set_flashdata('pesan', $pesan);
        redirect('saldoawal');        
    }

    public function simpan()
    {       
        $idsaldoawal             = $this->input->post('idsaldoawal');
        $tahunanggaran        = $this->input->post('tahunanggaran');
        $jenisakun        = $this->input->post('jenisakun');
        $deskripsi        = $this->input->post('deskripsi');
        $created_at        = date('Y-m-d H:i:s');
        $updated_at        = date('Y-m-d H:i:s');
        $tglinsert          = date('Y-m-d H:i:s');
        $idpengguna = $this->session->userdata('idpengguna');

        $arrkodeakun = $this->input->post('kodeakun');
        $arrdebet = $this->input->post('debet');
        $arrkredit = $this->input->post('kredit');


        if (empty($idsaldoawal)) {
        	
	        $cek1 = $this->db->query("select * from saldoawal where tahunanggaran='$tahunanggaran' and jenisakun='$jenisakun'");
	        if ($cek1->num_rows()>0) {
	        	$namaakun = $this->Akun_model->get_by_id($jenisakun)->row()->namaakun;
	            $pesan = '<script>swal("Gagal!", "Jenis akun '.$namaakun.' ini sudah ada!", "error")</script>';
		        $this->session->set_flashdata('pesan', $pesan);
		        redirect('saldoawal');   
	        }

	        $idsaldoawal = $this->db->query("SELECT create_idsaldoawal('".date('Y-m-d')."') as idsaldoawal")->row()->idsaldoawal;

	        $data = array(
	                        'idsaldoawal'   => $idsaldoawal, 
	                        'tahunanggaran'   => $tahunanggaran, 
	                        'jenisakun'   => $jenisakun, 
	                        'deskripsi'   => $deskripsi, 
	                        'totaldebet'   => 0, 
	                        'totalkredit'   => 0, 
	                        'created_at'   => $created_at, 
	                        'updated_at'   => $updated_at, 
	                        'idpengguna'   => $idpengguna, 
	                    );

	        $arrDetail = array();
	        foreach ($arrkodeakun as $key => $value) {
	        	$debet = (int)untitik($arrdebet[$key]);
	        	$kredit = (int)untitik($arrkredit[$key]);        	

	        	array_push($arrDetail, array(
	        									'idsaldoawal' => $idsaldoawal, 
	        									'kodeakun' => $value, 
	        									'debet' => $debet, 
	        									'kredit' => $kredit
	        								));
	        }
	        $simpan = $this->Saldoawal_model->simpan($data, $arrDetail);

        }else{
        	$data = array(
	                        'idsaldoawal'   => $idsaldoawal, 
	                        'deskripsi'   => $deskripsi, 
	                        'totaldebet'   => 0, 
	                        'totalkredit'   => 0, 
	                        'updated_at'   => $updated_at, 
	                        'idpengguna'   => $idpengguna, 
	                    );

	        $arrDetail = array();
	        foreach ($arrkodeakun as $key => $value) {
	        	$debet = (int)untitik($arrdebet[$key]);
	        	$kredit = (int)untitik($arrkredit[$key]);        	

	        	array_push($arrDetail, array(
	        									'idsaldoawal' => $idsaldoawal, 
	        									'kodeakun' => $value, 
	        									'debet' => $debet, 
	        									'kredit' => $kredit
	        								));
	        }
        	// var_dump($arrDetail) ;
        	// exit();
	        $simpan = $this->Saldoawal_model->update($data, $arrDetail, $idsaldoawal);

        }

        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('saldoawal');   
    }
    
    public function get_edit_data()
    {
        $idsaldoawal = $this->input->post('idsaldoawal');
        $RsData = $this->Saldoawal_model->get_by_id($idsaldoawal)->row();

        $data = array( 
                            'idsaldoawal'     =>  $RsData->idsaldoawal,  
                            'tahunanggaran'     =>  $RsData->tahunanggaran,  
                            'deskripsi'     =>  $RsData->deskripsi,  
                            'jenisakun'     =>  $RsData->jenisakun,  
                        );

        echo(json_encode($data));
    }

    public function get_akun_saldoawal()
    {
    	$jenisakun = $this->input->get('jenisakun');
    	$tahunanggaran = $this->input->get('tahunanggaran');
    	$rssaldoawal = $this->Saldoawal_model->get_saldo_barang($tahunanggaran, $jenisakun);
    	echo json_encode($rssaldoawal->result());
    }

}

/* End of file Saldoawal.php */
/* Location: ./application/controllers/Saldoawal.php */