<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stokopname extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Stokopname_model');
    }

    public function index()
    {
        $data['menu'] = 'stokopname';
        $this->load->view('stokopname/listdata', $data);
    }   

    public function tambah()
    {       
    	$rsbarang = $this->App->get_akun_barang();
        $data['rsbarang'] = $rsbarang;        
        $data['idstokopname'] = '';        
        $data['menu'] = 'stokopname';  
        $this->load->view('stokopname/form', $data);
    }

    public function cetakso($idstokopname)
    {       
        $idstokopname = $this->encrypt->decode($idstokopname);

        if ($this->Stokopname_model->get_by_id($idstokopname)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('stokopname');
            exit();
        };

        $rowstokopname = $this->db->query("
        								select * from v_stokopname where idstokopname='$idstokopname'
        							")->row();
        $rsstokopnamedetail = $this->db->query("
        								select * from v_stokopnamedetail where idstokopname='$idstokopname'
        							");
        $rowpengaturan = $this->db->query("select * from pengaturan")->row();

        // error_reporting(0);
        $this->load->library('Pdf');
            // exit();

        $data['rowpengaturan'] = $rowpengaturan;
        $data['rowstokopname'] = $rowstokopname;
        $data['rsstokopnamedetail'] = $rsstokopnamedetail;
        $this->load->view('lappenerimaan/cetakso', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Stokopname_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->tglstokopname;
                $row[] = $rowdata->namapengguna;
                $row[] = $rowdata->deskripsi;
                $row[] = '
                	<a href="'.site_url( 'stokopname/cetakso/'.$this->encrypt->encode($rowdata->idstokopname) ).'" class="btn btn-warning" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                ';

                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Stokopname_model->count_all(),
                        "recordsFiltered" => $this->Stokopname_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }


    public function simpan()
    {       
        $idstokopname             = $this->input->post('idstokopname');
        $tglstokopname        = $this->input->post('tglstokopname');
        $deskripsi        = $this->input->post('deskripsi');
        $created_at        = date('Y-m-d H:i:s');
        $updated_at        = date('Y-m-d H:i:s');
        $tglinsert          = date('Y-m-d H:i:s');
        $idpengguna = $this->session->userdata('idpengguna');

        $arrkodeakun = $this->input->post('kodeakun');
        $arrjumlahpersediaan = $this->input->post('jumlahpersediaan');
        $arrjumlahpersediaaninput = $this->input->post('jumlahpersediaaninput');
        $arrselisih = $this->input->post('selisih');
        



        $idstokopname = $this->db->query("SELECT create_idstokopname('".date('Y-m-d')."') as idstokopname")->row()->idstokopname;

        $data = array(
                        'idstokopname'   => $idstokopname, 
                        'tglstokopname'   => tgldb($tglstokopname), 
                        'deskripsi'   => $deskripsi, 
                        'created_at'   => $created_at, 
                        'updated_at'   => $updated_at, 
                        'idpengguna'   => $idpengguna, 
                    );

        $arrDetail = array();
        foreach ($arrkodeakun as $key => $value) {
        	$jumlahpersediaansistem = (int)untitik($arrjumlahpersediaan[$key]);
        	$jumlahpersediaaninput = (int)untitik($arrjumlahpersediaaninput[$key]);

        	array_push($arrDetail, array(
        									'idstokopname' => $idstokopname, 
        									'kodeakun' => $value, 
        									'jumlahpersediaansistem' => $jumlahpersediaansistem, 
        									'jumlahpersediaaninput' => $jumlahpersediaaninput, 
        									'selisih' => $jumlahpersediaaninput - $jumlahpersediaansistem
        								));
        }
        // var_dump($arrDetail);
        // exit();
        $simpan = $this->Stokopname_model->simpan($data, $arrDetail);


        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('stokopname');   
    }
    
    public function get_edit_data()
    {
        $idstokopname = $this->input->post('idstokopname');
        $RsData = $this->Stokopname_model->get_by_id($idstokopname)->row();

        $data = array( 
                            'idstokopname'     =>  $RsData->idstokopname,  
                            'namasupplier'     =>  $RsData->namasupplier,  
                            'alamatsupplier'     =>  $RsData->alamatsupplier,  
                            'notelpsupplier'     =>  $RsData->notelpsupplier,  
                            'emailsupplier'     =>  $RsData->emailsupplier,  
                            'statusaktif'     =>  $RsData->statusaktif,  
                            'created_at'     =>  $RsData->created_at,  
                            'updated_at'     =>  $RsData->updated_at,  
                        );

        echo(json_encode($data));
    }

}

/* End of file Stokopname.php */
/* Location: ./application/controllers/Stokopname.php */