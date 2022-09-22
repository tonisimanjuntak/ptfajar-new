<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postingjurnal extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Postingjurnal_model');
    }

    public function index()
    {
        $data['menu'] = 'postingjurnal';
        $this->load->view('postingjurnal/listdata', $data);
    }   


    public function datatablesource()
    {
        $RsData = $this->Postingjurnal_model->get_datatables();
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
                $row[] = '<a href="'.site_url( 'postingjurnal/edit/'.$this->encrypt->encode($rowdata->idposting) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                        <a href="'.site_url('postingjurnal/delete/'.$this->encrypt->encode($rowdata->idposting) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Postingjurnal_model->count_all(),
                        "recordsFiltered" => $this->Postingjurnal_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function datatablesourcedetail()
    {
        // query ini untuk item yang dimunculkan sesuai dengan kategori yang dipilih        

        $idposting = $this->input->post('idposting');
        $query = "select * from jurnalpengeluarandetail
                        WHERE jurnalpengeluarandetail.idposting='".$idposting."'";

        $RsData = $this->db->query($query);

        $no = 0;
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {               
                $no++;
                $row = array();
                $row[] = $rowdata->tahun;
                $row[] = bulan($rowdata->bulan);
                $row[] = tglindonesia($rowdata->tglposting);
                $row[] = $rowdata->namapengguna;
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

    public function delete($idposting)
    {
        $idposting = $this->encrypt->decode($idposting);  

        if ($this->Postingjurnal_model->get_by_id($idposting)->num_rows()<1) {
            $pesan = '<div>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <strong>Ilegal!</strong> Data tidak ditemukan! 
                        </div>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('postingjurnal');
            exit();
        };

        $hapus = $this->Postingjurnal_model->hapus($idposting);
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
        redirect('postingjurnal');        

    }


    public function mulaiposting()
    {
    	$tahun = $this->input->get('tahun');
    	$bulan = $this->input->get('bulan');

    	// $status = $this->Postingjurnal_model->mulaiposting($tahun, $bulan);
    	// if ($status) {
    	// 	echo json_encode(array('success' => true));
    	// }else{
    	// 	echo json_encode(array('msg' => "Data gagal di posting!"));
    	// }

		echo json_encode(array('success' => true));
    	
    }



}

/* End of file Postingjurnal.php */
/* Location: ./application/controllers/Postingjurnal.php */