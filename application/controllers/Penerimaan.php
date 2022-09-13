<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penerimaan extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Penerimaan_model');
        $this->load->model('App');
    }

    public function index()
    {
        $data['menu'] = 'penerimaan';
        $this->load->view('penerimaan/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idpenerimaan'] = "";     
        $data['menu'] = 'penerimaan';  
        $this->load->view('penerimaan/form', $data);
    }

    public function edit($idpenerimaan)
    {       
        $idpenerimaan = $this->encrypt->decode($idpenerimaan);

        if ($this->Penerimaan_model->get_by_id($idpenerimaan)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('penerimaan');
            exit();
        };
        $data['idpenerimaan'] = $idpenerimaan;      
        $data['menu'] = 'penerimaan';
        $this->load->view('penerimaan/form', $data);
    }

    public function cetaknota($idpenerimaan)
    {       
        $idpenerimaan = $this->encrypt->decode($idpenerimaan);
        $rspenerimaan = $this->Penerimaan_model->get_by_id($idpenerimaan);
        if ($rspenerimaan->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('penerimaan');
            exit();
        };

        error_reporting(0);
        $this->load->library('Pdf');
        $rsdetail = $this->db->query("select * from v_penerimaandetail where idpenerimaan='$idpenerimaan'");
        $rowpengaturan = $this->db->query("select * from pengaturan")->row();
        $rsgudang = $this->db->query("select * from gudang where idgudang='".$rspenerimaan->row()->idgudang."'");

        if ($rsgudang->num_rows()>0) {
            $rowgudang = $rsgudang->row();
            $data['namagudang'] = $rowgudang->namagudang;
            $data['alamatgudang'] = $rowgudang->alamatgudang;
            $data['emailgudang'] = $rowgudang->emailgudang;
            $data['notelpgudang'] = 'Telp. '.$rowgudang->notelpgudang;
        }else{
            $data['namagudang'] = '';
            $data['alamatgudang'] = '';
            $data['emailgudang'] = '';
            $data['notelpgudang'] = '';
        }

        $data['idpenerimaan'] = $idpenerimaan;      
        $data['rowpenerimaan'] = $rspenerimaan->row();      
        $data['rowpengaturan'] = $rowpengaturan;
        $data['rsdetail'] = $rsdetail;
        $this->load->view('penerimaan/cetak', $data);
    }    

    public function datatablesource()
    {
        $RsData = $this->Penerimaan_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tglpenerimaan).'<br>'.$rowdata->idpenerimaan;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->namagudang;
                $row[] = $rowdata->jenispenerimaan;
                $row[] = 'Rp. '.format_rupiah($rowdata->jumlahpenerimaan);
                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'penerimaan/edit/'.$this->encrypt->encode($rowdata->idpenerimaan) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('penerimaan/delete/'.$this->encrypt->encode($rowdata->idpenerimaan) ).'" id="hapus">Hapus</a>
                        <a class="dropdown-item" href="'.site_url('penerimaan/cetaknota/'.$this->encrypt->encode($rowdata->idpenerimaan) ).'" target="_blank">Cetak Nota</a>
                      </div>
                    </div>
                ';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Penerimaan_model->count_all(),
                        "recordsFiltered" => $this->Penerimaan_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function datatablesourcedetail()
    {
        // query ini untuk item yang dimunculkan sesuai dengan kategori yang dipilih        

        $idpenerimaan = $this->input->post('idpenerimaan');
        $query = "select * from v_penerimaandetail
                        WHERE v_penerimaandetail.idpenerimaan='".$idpenerimaan."'";

        $RsData = $this->db->query($query);

        $no = 0;
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {               
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->kodeakun;
                $row[] = $rowdata->kodeakun.' '.$rowdata->namaakun;
                $row[] = $rowdata->jumlahbarang;
                $row[] = format_rupiah($rowdata->hargabeli);
                $row[] = format_rupiah($rowdata->totalharga);
                $row[] = '<span class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></span>';
                $data[] = $row;
            }
        }

        $output = array(
                        "data" => $data,
                        );

        //output to json format
        echo json_encode($output);
    }

    public function delete($idpenerimaan)
    {
        $idpenerimaan = $this->encrypt->decode($idpenerimaan);  

        if ($this->Penerimaan_model->get_by_id($idpenerimaan)->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('penerimaan');
            exit();
        };

        $hapus = $this->Penerimaan_model->hapus($idpenerimaan);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('penerimaan');        

    }

    public function simpan()
    {
        $isidatatable       = $_REQUEST['isidatatable'];
        $idpenerimaan           = $this->input->post('idpenerimaan');
        $tglpenerimaan           = $this->input->post('tglpenerimaan');
        $deskripsi           = $this->input->post('deskripsi');
        $idgudang           = $this->input->post('idgudang');
        $jenispenerimaan           = $this->input->post('jenispenerimaan');
        $jumlahpenerimaan           = untitik($this->input->post('jumlahpenerimaan'));
        $created_at           = date('Y-m-d H:i:s');
        $updated_at           = date('Y-m-d H:i:s');
        $idpengguna           = $this->session->userdata('idpengguna');

        //jika session berakhir
        if (empty($idpengguna)) { 
            echo json_encode(array('msg'=>"Session telah berakhir, Silahkan refresh halaman!"));
            exit();
        }               

        if ($idgudang=='') {
            $idgudang = null;
        }
        
        if ($idpenerimaan=='') {
            
            $idpenerimaan = $this->db->query("select create_idpenerimaan('".date('Y-m-d')."') as idpenerimaan ")->row()->idpenerimaan;

            $arrayhead = array(
                                'idpenerimaan' => $idpenerimaan,
                                'tglpenerimaan' => $tglpenerimaan,
                                'deskripsi' => $deskripsi,
                                'idgudang' => $idgudang,
                                'jenispenerimaan' => $jenispenerimaan,
                                'jumlahpenerimaan' => $jumlahpenerimaan,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                        $kodeakun              = $item[1];
                        $jumlahbarang              = $item[3];
                        $hargabeli              = untitik($item[4]);
                        $hargajual              = 0;
                        $totalharga              = untitik($item[5]);
                $i++;

                $detail = array(
                                'idpenerimaan' => $idpenerimaan,
                                'kodeakun' => $kodeakun,
                                'jumlahbarang' => $jumlahbarang,
                                'hargabeli' => $hargabeli,
                                'hargajual' => $hargajual,
                                'totalharga' => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }


            $simpan  = $this->Penerimaan_model->simpan($arrayhead, $arraydetail, $idpenerimaan);
        }else{


            $arrayhead = array(
                                'tglpenerimaan' => $tglpenerimaan,
                                'deskripsi' => $deskripsi,
                                'idgudang' => $idgudang,
                                'jenispenerimaan' => $jenispenerimaan,
                                'jumlahpenerimaan' => $jumlahpenerimaan,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                        $kodeakun              = $item[1];
                        $jumlahbarang              = $item[3];
                        $hargabeli              = untitik($item[4]);
                        $hargajual              = 0;
                        $totalharga              = untitik($item[5]);
                $i++;

                $detail = array(
                                'idpenerimaan' => $idpenerimaan,
                                'kodeakun' => $kodeakun,
                                'jumlahbarang' => $jumlahbarang,
                                'hargabeli' => $hargabeli,
                                'hargajual' => $hargajual,
                                'totalharga' => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }

            $simpan  = $this->Penerimaan_model->update($arrayhead, $arraydetail, $idpenerimaan);

        }


        if (!$simpan) { //jika gagal
            $eror = $this->db->error(); 
            echo json_encode(array('msg'=>'Kode Eror: '.$eror['code'].' '.$eror['message']));
            exit();
        }

        // jika berhasil akan sampai ke tahap ini       
        echo json_encode(array('success' => true, 'idpenerimaan' => $idpenerimaan));
    }
    
    public function get_edit_data()
    {
        $idpenerimaan = $this->input->post('idpenerimaan');
        $RsData = $this->Penerimaan_model->get_by_id($idpenerimaan)->row();

        $data = array(
                    'idpenerimaan'     =>  $RsData->idpenerimaan,
                    'tglpenerimaan'     =>  $RsData->tglpenerimaan,
                    'deskripsi'     =>  $RsData->deskripsi,
                    'idgudang'     =>  $RsData->idgudang,
                    'jenispenerimaan'     =>  $RsData->jenispenerimaan,
                    'jumlahpenerimaan'     =>  $RsData->jumlahpenerimaan,
                    'created_at'     =>  $RsData->created_at,
                    'updated_at'     =>  $RsData->updated_at,
                    'idpengguna'     =>  $RsData->idpengguna,
                    );
        echo(json_encode($data));
    }

    public function importexcel()
    {
        $data['menu'] = 'penerimaan';  
        $this->load->view('penerimaan/importexcel', $data);
    }

    public function importdata()
    {
        $filename = 'penerimaan';
        $data = array(); 
        
        if(isset($_POST['preview'])){ 
          $upload = $this->App->upload_file_importexcel($filename);          
          if($upload['result'] == "success"){ 
            //PhpSpreadSheet
            $inputFileName = './uploads/importexcel/'.$filename.'.xlsx';
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($inputFileName);
            $sheet1 = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
            $arrSheet = $sheet1->toArray();            
            // $data['sheet'] = $arrSheet; 
            $this->Penerimaan_model->simpan_import_temp($arrSheet);
          }else{ 
            $data['upload_error'] = $upload['error']; 
          }
        }
        
        $data['menu'] = 'penerimaan';  
        $this->load->view('penerimaan/importexcel', $data);
    }

    public function simpan_import(){

        $idpengguna = $this->session->userdata('idpengguna');
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');


        $rsTemp = $this->db->query("select * from penerimaan_temp");
        $no = 1;
        if ($rsTemp->num_rows()>0) 
        {
            foreach ($rsTemp->result() as $rowheader) 
            {
          
                $idpenerimaan = $rowheader->idpenerimaan;
                $tglpenerimaan = $rowheader->tglpenerimaan;
                $deskripsi = $rowheader->deskripsi;  
                $idgudang = $rowheader->idgudang;  
                $jenispenerimaan = $rowheader->jenispenerimaan;  

                $jumlahpenerimaan = $this->db->query("select sum(totalharga) as jumlahpenerimaan from penerimaan_tempdetail where idpenerimaan=".$rowheader->idpenerimaan)->row()->jumlahpenerimaan;

                $idpenerimaan = $this->db->query("select create_idpenerimaan('".date('Y-m-d')."') as idpenerimaan ")->row()->idpenerimaan;

                $arrayhead = array(
                        'idpenerimaan' => $idpenerimaan,
                        'tglpenerimaan' => $tglpenerimaan,
                        'deskripsi' => $deskripsi,
                        'idgudang' => $idgudang,
                        'jenispenerimaan' => $jenispenerimaan,
                        'jumlahpenerimaan' => $jumlahpenerimaan,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'idpengguna' => $idpengguna
                        );


                $rsTempDetail = $this->db->query("select * from penerimaan_tempdetail where idpenerimaan=".$rowheader->idpenerimaan);
                if ($rsTempDetail->num_rows()>0) 
                {
                    $i=0;
                    $arraydetail=array();       
                    foreach ($rsTempDetail->result() as $rowdetail) 
                    {
                        
                        $detail = array(
                                        'idpenerimaan' => $idpenerimaan,
                                        'kodeakun' => $rowdetail->kodeakun,
                                        'jumlahbarang' => $rowdetail->jumlahbarang,
                                        'hargabeli' => $rowdetail->hargabeli,
                                        'hargajual' => $rowdetail->hargajual,
                                        'totalharga' => $rowdetail->totalharga,
                                        );

                        array_push($arraydetail, $detail);              
                        $i++;

                    } //end foreach
                } //endif

                $simpan  = $this->Penerimaan_model->simpan($arrayhead, $arraydetail, $idpenerimaan);

            } //end foreaceh
        } //endif


        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }
        $this->session->set_flashdata('pesan', $pesan);
        redirect('penerimaan');
    }

}

/* End of file Penerimaan.php */
/* Location: ./application/controllers/Penerimaan.php */