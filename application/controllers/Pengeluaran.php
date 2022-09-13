<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pengeluaran extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Pengeluaran_model');
        $this->load->model('App');
    }

    public function index()
    {
        $data['menu'] = 'pengeluaran';
        $this->load->view('pengeluaran/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idpengeluaran'] = "";     
        $data['menu'] = 'pengeluaran';  
        $this->load->view('pengeluaran/form', $data);
    }

    public function edit($idpengeluaran)
    {       
        $idpengeluaran = $this->encrypt->decode($idpengeluaran);
        $rspengeluaran = $this->Pengeluaran_model->get_by_id($idpengeluaran);
        if ($rspengeluaran->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengeluaran');
            exit();
        };
        $statusterkirim = $rspengeluaran->row()->statusterkirim;
        if ($statusterkirim=='Sudah Terkirim') {
           $pesan = '<script>swal("Upss!", "Barang ini sudah terkirim tidak dapat diedit lagi.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengeluaran');
            exit(); 
        }
        $data['idpengeluaran'] = $idpengeluaran;      
        $data['menu'] = 'pengeluaran';
        $this->load->view('pengeluaran/form', $data);
    }

    public function cetaknota($idpengeluaran)
    {       
        $idpengeluaran = $this->encrypt->decode($idpengeluaran);
        $rspengeluaran = $this->Pengeluaran_model->get_by_id($idpengeluaran);
        if ($rspengeluaran->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengeluaran');
            exit();
        };

        error_reporting(0);
        $this->load->library('Pdf');
        $rsdetail = $this->db->query("select * from v_pengeluarandetail where idpengeluaran='$idpengeluaran'");
        $rowpengaturan = $this->db->query("select * from pengaturan")->row();
        $rsgudang = $this->db->query("select * from gudang where idgudang='".$rspengeluaran->row()->idgudang."'");

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

        $data['idpengeluaran'] = $idpengeluaran;      
        $data['rowpengeluaran'] = $rspengeluaran->row();      
        $data['rowpengaturan'] = $rowpengaturan;
        $data['rsdetail'] = $rsdetail;
        $this->load->view('pengeluaran/cetak', $data);
    }   

    public function datatablesource()
    {
        $RsData = $this->Pengeluaran_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tglpengeluaran).'<br>'.$rowdata->idpengeluaran;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->namagudang;
                $row[] = $rowdata->jenispengeluaran;
                $row[] = 'Rp. '.format_rupiah($rowdata->jumlahpengeluaran);


                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'pengeluaran/edit/'.$this->encrypt->encode($rowdata->idpengeluaran) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('pengeluaran/delete/'.$this->encrypt->encode($rowdata->idpengeluaran) ).'" id="hapus">Hapus</a>
                        <a class="dropdown-item" href="'.site_url('pengeluaran/cetaknota/'.$this->encrypt->encode($rowdata->idpengeluaran) ).'" target="_blank">Cetak Nota</a>
                      </div>
                    </div>
                ';

                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pengeluaran_model->count_all(),
                        "recordsFiltered" => $this->Pengeluaran_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function datatablesourcedetail()
    {
        // query ini untuk item yang dimunculkan sesuai dengan kategori yang dipilih        

        $idpengeluaran = $this->input->post('idpengeluaran');
        $query = "select * from v_pengeluarandetail
                        WHERE v_pengeluarandetail.idpengeluaran='".$idpengeluaran."'";

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
                $row[] = format_rupiah($rowdata->hargajual);
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

    public function delete($idpengeluaran)
    {
        $idpengeluaran = $this->encrypt->decode($idpengeluaran);  
        $rspengeluaran = $this->Pengeluaran_model->get_by_id($idpengeluaran);
        if ($rspengeluaran->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengeluaran');
            exit();
        };

        $statusterkirim = $rspengeluaran->row()->statusterkirim;
        if ($statusterkirim=='Sudah Terkirim') {
           $pesan = '<script>swal("Upss!", "Barang ini sudah terkirim tidak dapat dihapus lagi.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('pengeluaran');
            exit(); 
        }

        $hapus = $this->Pengeluaran_model->hapus($idpengeluaran);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('pengeluaran');        

    }

    public function simpan()
    {
        $isidatatable       = $_REQUEST['isidatatable'];
        $idpengeluaran           = $this->input->post('idpengeluaran');
        $tglpengeluaran           = $this->input->post('tglpengeluaran');
        $deskripsi           = $this->input->post('deskripsi');
        $idgudang           = $this->input->post('idgudang');
        $jenispengeluaran           = $this->input->post('jenispengeluaran');
        $jumlahpengeluaran           = untitik($this->input->post('jumlahpengeluaran'));
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

        $statusterkirim = 'Belum Terkirim';


        if ($idpengeluaran=='') {
            
            $idpengeluaran = $this->db->query("select create_idpengeluaran('".date('Y-m-d')."') as idpengeluaran ")->row()->idpengeluaran;

            $arrayhead = array(
                                'idpengeluaran' => $idpengeluaran,
                                'tglpengeluaran' => $tglpengeluaran,
                                'deskripsi' => $deskripsi,
                                'idgudang' => $idgudang,
                                'jenispengeluaran' => $jenispengeluaran,
                                'jumlahpengeluaran' => $jumlahpengeluaran,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                'statusterkirim' => $statusterkirim,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                        $kodeakun              = $item[1];
                        $jumlahbarang              = $item[3];
                        $hargajual              = untitik($item[4]);
                        $hargabeli              = 0;
                        $totalharga              = untitik($item[5]);
                $i++;

                $detail = array(
                                'idpengeluaran' => $idpengeluaran,
                                'kodeakun' => $kodeakun,
                                'jumlahbarang' => $jumlahbarang,
                                'hargabeli' => $hargabeli,
                                'hargajual' => $hargajual,
                                'totalharga' => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }


            $simpan  = $this->Pengeluaran_model->simpan($arrayhead, $arraydetail, $idpengeluaran);
        }else{


            $arrayhead = array(
                                'tglpengeluaran' => $tglpengeluaran,
                                'deskripsi' => $deskripsi,
                                'idgudang' => $idgudang,
                                'jenispengeluaran' => $jenispengeluaran,
                                'jumlahpengeluaran' => $jumlahpengeluaran,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                'statusterkirim' => $statusterkirim,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                        $kodeakun              = $item[1];
                        $jumlahbarang              = $item[3];
                        $hargajual              = untitik($item[4]);
                        $hargabeli              = 0;
                        $totalharga              = untitik($item[5]);
                $i++;

                $detail = array(
                                'idpengeluaran' => $idpengeluaran,
                                'kodeakun' => $kodeakun,
                                'jumlahbarang' => $jumlahbarang,
                                'hargabeli' => $hargabeli,
                                'hargajual' => $hargajual,
                                'totalharga' => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }

            $simpan  = $this->Pengeluaran_model->update($arrayhead, $arraydetail, $idpengeluaran);

        }


        if (!$simpan) { //jika gagal
            $eror = $this->db->error(); 
            echo json_encode(array('msg'=>'Kode Eror: '.$eror['code'].' '.$eror['message']));
            exit();
        }

        // jika berhasil akan sampai ke tahap ini       
        echo json_encode(array('success' => true, 'idpengeluaran' => $idpengeluaran));
    }
    
    public function get_edit_data()
    {
        $idpengeluaran = $this->input->post('idpengeluaran');
        $RsData = $this->Pengeluaran_model->get_by_id($idpengeluaran)->row();

        $data = array(
                    'idpengeluaran'     =>  $RsData->idpengeluaran,
                    'tglpengeluaran'     =>  $RsData->tglpengeluaran,
                    'deskripsi'     =>  $RsData->deskripsi,
                    'idgudang'     =>  $RsData->idgudang,
                    'jenispengeluaran'     =>  $RsData->jenispengeluaran,
                    'jumlahpengeluaran'     =>  $RsData->jumlahpengeluaran,
                    'created_at'     =>  $RsData->created_at,
                    'updated_at'     =>  $RsData->updated_at,
                    'idpengguna'     =>  $RsData->idpengguna,
                    );
        echo(json_encode($data));
    }

    public function get_hargajual_ma()
    {
        $kodeakun = $this->input->get('kodeakun');
        $tahun = date('Y');
        $hargajual = $this->App->get_hargajual_ma($kodeakun, $tahun);
        echo json_encode($hargajual);
    }

    public function get_jumlahpersediaan()
    {
        $kodeakun = $this->input->get('kodeakun');
        $jumlahpersediaan = $this->db->query("select jumlahpersediaan from akun where kodeakun='$kodeakun'")->row()->jumlahpersediaan;
        echo json_encode($jumlahpersediaan);
    }

    public function importexcel()
    {
        $data['menu'] = 'pengeluaran';  
        $this->load->view('pengeluaran/importexcel', $data);
    }

    public function importdata()
    {
        $filename = 'pengeluaran';
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
            $this->Pengeluaran_model->simpan_import_temp($arrSheet);
          }else{ 
            $data['upload_error'] = $upload['error']; 
          }
        }
        
        $data['menu'] = 'pengeluaran';  
        $this->load->view('pengeluaran/importexcel', $data);
    }

    public function simpan_import(){

        $idpengguna = $this->session->userdata('idpengguna');
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');


        $rsTemp = $this->db->query("select * from pengeluaran_temp");
        $no = 1;
        if ($rsTemp->num_rows()>0) 
        {
            foreach ($rsTemp->result() as $rowheader) 
            {
          
                $idpengeluaran = $rowheader->idpengeluaran;
                $tglpengeluaran = $rowheader->tglpengeluaran;
                $deskripsi = $rowheader->deskripsi;  
                $idgudang = $rowheader->idgudang;  
                $jenispengeluaran = $rowheader->jenispengeluaran;  
                $statusterkirim = 'Belum Terkirim';

                $jumlahpengeluaran = $this->db->query("select sum(totalharga) as jumlahpengeluaran from pengeluaran_tempdetail where idpengeluaran=".$rowheader->idpengeluaran)->row()->jumlahpengeluaran;

                $idpengeluaran = $this->db->query("select create_idpengeluaran('".date('Y-m-d')."') as idpengeluaran ")->row()->idpengeluaran;

                $arrayhead = array(
                        'idpengeluaran' => $idpengeluaran,
                        'tglpengeluaran' => $tglpengeluaran,
                        'deskripsi' => $deskripsi,
                        'idgudang' => $idgudang,
                        'jenispengeluaran' => $jenispengeluaran,
                        'jumlahpengeluaran' => $jumlahpengeluaran,
                        'statusterkirim' => $statusterkirim,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'idpengguna' => $idpengguna
                        );


                $rsTempDetail = $this->db->query("select * from pengeluaran_tempdetail where idpengeluaran=".$rowheader->idpengeluaran);
                if ($rsTempDetail->num_rows()>0) 
                {
                    $i=0;
                    $arraydetail=array();       
                    foreach ($rsTempDetail->result() as $rowdetail) 
                    {
                        
                        $detail = array(
                                        'idpengeluaran' => $idpengeluaran,
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

                $simpan  = $this->Pengeluaran_model->simpan($arrayhead, $arraydetail, $idpengeluaran);

            } //end foreaceh
        } //endif


        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }
        $this->session->set_flashdata('pesan', $pesan);
        redirect('pengeluaran');
    }


}

/* End of file Pengeluaran.php */
/* Location: ./application/controllers/Pengeluaran.php */