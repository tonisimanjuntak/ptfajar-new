<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnalpenerimaan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Jurnalpenerimaan_model');
    }

    public function index()
    {
        $data['menu'] = 'Jurnalpenerimaan';
        $this->load->view('jurnalpenerimaan/listdata', $data);
    }   

    public function tambah()
    {       
        $data['idjurnalpenerimaan'] = "";     
        $data['menu'] = 'Jurnalpenerimaan';  
        $this->load->view('jurnalpenerimaan/form', $data);
    }

    public function edit($idjurnalpenerimaan)
    {       
        $idjurnalpenerimaan = $this->encrypt->decode($idjurnalpenerimaan);

        if ($this->Jurnalpenerimaan_model->get_by_id($idjurnalpenerimaan)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('Jurnalpenerimaan');
            exit();
        };
        $data['idjurnalpenerimaan'] = $idjurnalpenerimaan;      
        $data['menu'] = 'Jurnalpenerimaan';
        $this->load->view('jurnalpenerimaan/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Jurnalpenerimaan_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->tgljurnalpenerimaan;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->idsupplier;
                $row[] = $rowdata->jenispenerimaan;
                $row[] = $rowdata->jenistransaksi;
                $row[] = $rowdata->jumlahpenerimaan;
                $row[] = $rowdata->created_at;
                $row[] = $rowdata->updated_at;
                $row[] = $rowdata->idpengguna;
                $row[] = '<a href="'.site_url( 'Jurnalpenerimaan/edit/'.$this->encrypt->encode($rowdata->idjurnalpenerimaan) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('Jurnalpenerimaan/delete/'.$this->encrypt->encode($rowdata->idjurnalpenerimaan) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Jurnalpenerimaan_model->count_all(),
                        "recordsFiltered" => $this->Jurnalpenerimaan_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function datatablesourcedetail()
    {
        // query ini untuk item yang dimunculkan sesuai dengan kategori yang dipilih        

        $idjurnalpenerimaan = $this->input->post('idjurnalpenerimaan');
        $query = "select * from jurnalpenerimaandetail
                        WHERE jurnalpenerimaandetail.idjurnalpenerimaan='".$idjurnalpenerimaan."'";

        $RsData = $this->db->query($query);

        $no = 0;
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {               
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->idjurnalpenerimaan;
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

    public function delete($idjurnalpenerimaan)
    {
        $idjurnalpenerimaan = $this->encrypt->decode($idjurnalpenerimaan);  

        if ($this->Jurnalpenerimaan_model->get_by_id($idjurnalpenerimaan)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('Jurnalpenerimaan');
            exit();
        };

        $hapus = $this->Jurnalpenerimaan_model->hapus($idjurnalpenerimaan);
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
        redirect('Jurnalpenerimaan');        

    }

    public function simpan()
    {
        $isidatatable       = $_REQUEST['isidatatable'];
        $idjurnalpenerimaan           = $this->input->post('idjurnalpenerimaan');
        $tgljurnalpenerimaan           = $this->input->post('tgljurnalpenerimaan');
        $deskripsi           = $this->input->post('deskripsi');
        $idsupplier           = $this->input->post('idsupplier');
        $jenispenerimaan           = $this->input->post('jenispenerimaan');
        $jenistransaksi           = $this->input->post('jenistransaksi');
        $jumlahpenerimaan           = $this->input->post('jumlahpenerimaan');
        $created_at           = $this->input->post('created_at');
        $updated_at           = $this->input->post('updated_at');
        $idpengguna           = $this->input->post('idpengguna');
        //jika session berakhir
        if (empty($id_pegawai)) { 
            echo json_encode(array('msg'=>"Session telah berakhir, Silahkan refresh halaman!"));
            exit();
        }               

        if ($idjurnalpenerimaan=='') {
            
            $idjurnalpenerimaan = $this->db->query("select create_idjurnalpenerimaan('2022-07-13') as idjurnalpenerimaan ")->row()->idjurnalpenerimaan;

            $arrayhead = array(
                                'idjurnalpenerimaan' => $idjurnalpenerimaan,
                                'tgljurnalpenerimaan' => $tgljurnalpenerimaan,
                                'deskripsi' => $deskripsi,
                                'idsupplier' => $idsupplier,
                                'jenispenerimaan' => $jenispenerimaan,
                                'jenistransaksi' => $jenistransaksi,
                                'jumlahpenerimaan' => $jumlahpenerimaan,
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
                                'idjurnalpenerimaan' => $idjurnalpenerimaan,
                                'kodeakun' => $kodeakun,
                                'jumlahbarang' => $jumlahbarang,
                                'hargabeli' => $hargabeli,
                                'hargajual' => $hargajual,
                                'totalharga' => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }


            $simpan  = $this->Jurnalpenerimaan_model->simpan($arrayhead, $arraydetail, $idjurnalpenerimaan);
        }else{


            $arrayhead = array(
                                'idjurnalpenerimaan' => $idjurnalpenerimaan,
                                'tgljurnalpenerimaan' => $tgljurnalpenerimaan,
                                'deskripsi' => $deskripsi,
                                'idsupplier' => $idsupplier,
                                'jenispenerimaan' => $jenispenerimaan,
                                'jenistransaksi' => $jenistransaksi,
                                'jumlahpenerimaan' => $jumlahpenerimaan,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'idpengguna' => $idpengguna,
                                );

            //-------------------------------- >> simpan dari datatable 
            $i=0;
            $arraydetail=array();       
            foreach ($isidatatable as $item) {
                $idjurnalpenerimaan              = $item[1];
                $kodeakun              = $item[1];
                $jumlahbarang              = $item[1];
                $hargabeli              = $item[1];
                $hargajual              = $item[1];
                $totalharga              = $item[1];
                $i++;

                $detail = array(
                                'idjurnalpenerimaan'             => $idjurnalpenerimaan,
                                'kodeakun'             => $kodeakun,
                                'jumlahbarang'             => $jumlahbarang,
                                'hargabeli'             => $hargabeli,
                                'hargajual'             => $hargajual,
                                'totalharga'             => $totalharga,
                                );

                array_push($arraydetail, $detail);              
            }

            $simpan  = $this->Jurnalpenerimaan_model->update($arrayhead, $arraydetail, $idjurnalpenerimaan);

        }


        if (!$simpan) { //jika gagal
            $eror = $this->db->error(); 
            echo json_encode(array('msg'=>'Kode Eror: '.$eror['code'].' '.$eror['message']));
            exit();
        }

        // jika berhasil akan sampai ke tahap ini       
        echo json_encode(array('success' => true, 'idjurnalpenerimaan' => $idjurnalpenerimaan));
    }
    
    public function get_edit_data()
    {
        $idjurnalpenerimaan = $this->input->post('idjurnalpenerimaan');
        $RsData = $this->Jurnalpenerimaan_model->get_by_id($idjurnalpenerimaan)->row();

        $data = array(
                    'idjurnalpenerimaan'     =>  $RsData->idjurnalpenerimaan,
                    'tgljurnalpenerimaan'     =>  $RsData->tgljurnalpenerimaan,
                    'deskripsi'     =>  $RsData->deskripsi,
                    'idsupplier'     =>  $RsData->idsupplier,
                    'jenispenerimaan'     =>  $RsData->jenispenerimaan,
                    'jenistransaksi'     =>  $RsData->jenistransaksi,
                    'jumlahpenerimaan'     =>  $RsData->jumlahpenerimaan,
                    'created_at'     =>  $RsData->created_at,
                    'updated_at'     =>  $RsData->updated_at,
                    'idpengguna'     =>  $RsData->idpengguna,
                    );
        echo(json_encode($data));
    }

}

/* End of file Jurnalpenerimaan.php */
/* Location: ./application/controllers/Jurnalpenerimaan.php */