<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnalpengeluaran extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Jurnalpengeluaran_model');
    }

    public function index()
    {
        $data['menu'] = 'Jurnalpengeluaran';
        $this->load->view('jurnalpengeluaran/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idjurnalpengeluaran'] = "";     
        $data['menu'] = 'Jurnalpengeluaran';  
        $this->load->view('jurnalpengeluaran/form', $data);
    }

    public function edit($idjurnalpengeluaran)
    {       
        $idjurnalpengeluaran = $this->encrypt->decode($idjurnalpengeluaran);

        if ($this->Jurnalpengeluaran_model->get_by_id($idjurnalpengeluaran)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('Jurnalpengeluaran');
            exit();
        };
        $data['idjurnalpengeluaran'] = $idjurnalpengeluaran;      
        $data['menu'] = 'Jurnalpengeluaran';
        $this->load->view('jurnalpengeluaran/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Jurnalpengeluaran_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->tgljurnalpengeluaran;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->jenispengeluaran;
                $row[] = $rowdata->jenistransaksi;
                $row[] = $rowdata->jumlahpengeluaran;
                $row[] = $rowdata->created_at;
                $row[] = $rowdata->updated_at;
                $row[] = $rowdata->idpengguna;
                $row[] = '<a href="'.site_url( 'Jurnalpengeluaran/edit/'.$this->encrypt->encode($rowdata->idjurnalpengeluaran) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('Jurnalpengeluaran/delete/'.$this->encrypt->encode($rowdata->idjurnalpengeluaran) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Jurnalpengeluaran_model->count_all(),
                        "recordsFiltered" => $this->Jurnalpengeluaran_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function datatablesourcedetail()
    {
        // query ini untuk item yang dimunculkan sesuai dengan kategori yang dipilih        

        $idjurnalpengeluaran = $this->input->post('idjurnalpengeluaran');
        $query = "select * from jurnalpengeluarandetail
                        WHERE jurnalpengeluarandetail.idjurnalpengeluaran='".$idjurnalpengeluaran."'";

        $RsData = $this->db->query($query);

        $no = 0;
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {               
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->idjurnalpengeluaran;
                $row[] = $rowdata->kodeakun;
                $row[] = $rowdata->jumlahbarang;
                $row[] = $rowdata->hargabeli;
                $row[] = $rowdata->hargajual;
                $row[] = $rowdata->totalharga;
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

    public function delete($idjurnalpengeluaran)
    {
        $idjurnalpengeluaran = $this->encrypt->decode($idjurnalpengeluaran);  

        if ($this->Jurnalpengeluaran_model->get_by_id($idjurnalpengeluaran)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('Jurnalpengeluaran');
            exit();
        };

        $hapus = $this->Jurnalpengeluaran_model->hapus($idjurnalpengeluaran);
        if ($hapus) {           
            $pesan = '<div>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Berhasil!</strong> Data berhasil dihapus!
                        </div>
                    </div>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Gagal!</strong> Data gagal dihapus karena sudah digunakan! <br>
                        </div>
                    </div>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('Jurnalpengeluaran');        

    }

    public function simpan()
    {
        $isidatatable       = $_REQUEST['isidatatable'];
        $idjurnalpengeluaran           = $this->input->post('idjurnalpengeluaran');
        $tgljurnalpengeluaran           = $this->input->post('tgljurnalpengeluaran');
        $deskripsi           = $this->input->post('deskripsi');
        $jenispengeluaran           = $this->input->post('jenispengeluaran');
        $jenistransaksi           = $this->input->post('jenistransaksi');
        $jumlahpengeluaran           = $this->input->post('jumlahpengeluaran');
        $created_at           = $this->input->post('created_at');
        $updated_at           = $this->input->post('updated_at');
        $idpengguna           = $this->input->post('idpengguna');
        //jika session berakhir
        if (empty($id_pegawai)) { 
            echo json_encode(array('msg'=>"Session telah berakhir, Silahkan refresh halaman!"));
            exit();
        }               

        if ($idjurnalpengeluaran=='') {
            
            $idjurnalpengeluaran = $this->db->query("select create_idjurnalpengeluaran('2022-07-13') as idjurnalpengeluaran ")->row()->idjurnalpengeluaran;

            $arrayhead = array(
                                'idjurnalpengeluaran' => $idjurnalpengeluaran,
                                'tgljurnalpengeluaran' => $tgljurnalpengeluaran,
                                'deskripsi' => $deskripsi,
                                'jenispengeluaran' => $jenispengeluaran,
                                'jenistransaksi' => $jenistransaksi,
                                'jumlahpengeluaran' => $jumlahpengeluaran,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                        $kodeakun              = $item[2];
                        $jumlahbarang              = $item[3];
                        $hargabeli              = $item[4];
                        $hargajual              = $item[5];
                        $totalharga              = $item[6];
                $i++;

                $detail = array(
                                'idjurnalpengeluaran' => $idjurnalpengeluaran,
                                'kodeakun' => $kodeakun,
                                'jumlahbarang' => $jumlahbarang,
                                'hargabeli' => $hargabeli,
                                'hargajual' => $hargajual,
                                'totalharga' => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }


            $simpan  = $this->Jurnalpengeluaran_model->simpan($arrayhead, $arraydetail, $idjurnalpengeluaran);
        }else{


            $arrayhead = array(
                                'idjurnalpengeluaran' => $idjurnalpengeluaran,
                                'tgljurnalpengeluaran' => $tgljurnalpengeluaran,
                                'deskripsi' => $deskripsi,
                                'jenispengeluaran' => $jenispengeluaran,
                                'jenistransaksi' => $jenistransaksi,
                                'jumlahpengeluaran' => $jumlahpengeluaran,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                $idjurnalpengeluaran              = $item[1];
                $kodeakun              = $item[1];
                $jumlahbarang              = $item[1];
                $hargabeli              = $item[1];
                $hargajual              = $item[1];
                $totalharga              = $item[1];
                $i++;

                $detail = array(
                                'idjurnalpengeluaran'             => $idjurnalpengeluaran,
                                'kodeakun'             => $kodeakun,
                                'jumlahbarang'             => $jumlahbarang,
                                'hargabeli'             => $hargabeli,
                                'hargajual'             => $hargajual,
                                'totalharga'             => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }

            $simpan  = $this->Jurnalpengeluaran_model->update($arrayhead, $arraydetail, $idjurnalpengeluaran);

        }


        if (!$simpan) { //jika gagal
            $eror = $this->db->error(); 
            echo json_encode(array('msg'=>'Kode Eror: '.$eror['code'].' '.$eror['message']));
            exit();
        }

        // jika berhasil akan sampai ke tahap ini       
        echo json_encode(array('success' => true, 'idjurnalpengeluaran' => $idjurnalpengeluaran));
    }
    
    public function get_edit_data()
    {
        $idjurnalpengeluaran = $this->input->post('idjurnalpengeluaran');
        $RsData = $this->Jurnalpengeluaran_model->get_by_id($idjurnalpengeluaran)->row();

        $data = array(
                    'idjurnalpengeluaran'     =>  $RsData->idjurnalpengeluaran,
                    'tgljurnalpengeluaran'     =>  $RsData->tgljurnalpengeluaran,
                    'deskripsi'     =>  $RsData->deskripsi,
                    'jenispengeluaran'     =>  $RsData->jenispengeluaran,
                    'jenistransaksi'     =>  $RsData->jenistransaksi,
                    'jumlahpengeluaran'     =>  $RsData->jumlahpengeluaran,
                    'created_at'     =>  $RsData->created_at,
                    'updated_at'     =>  $RsData->updated_at,
                    'idpengguna'     =>  $RsData->idpengguna,
                    );
        echo(json_encode($data));
    }

}

/* End of file Jurnalpengeluaran.php */
/* Location: ./application/controllers/Jurnalpengeluaran.php */