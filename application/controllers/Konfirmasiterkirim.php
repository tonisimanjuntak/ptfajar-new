<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasiterkirim extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Konfrimasiterkirim_model');
    }

    public function index()
    {
        $data['menu'] = 'konfirmasiterkirim';
        $this->load->view('konfirmasiterkirim/listdata', $data);
    }   

    public function konfirmasi($idpengeluaran)
    {       
        $idpengeluaran = $this->encrypt->decode($idpengeluaran);
        $rspengeluaran = $this->Konfrimasiterkirim_model->get_by_id($idpengeluaran);
        if ($rspengeluaran->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('konfirmasiterkirim');
            exit();
        };
        
        if (empty($rspengeluaran->row()->tglstatusterkirim)) {
        	$tglstatusterkirim = date('Y-m-d');
        }else{
        	$tglstatusterkirim = date('Y-m-d', strtotime($rspengeluaran->row()->tglstatusterkirim));
        }
        $data['idpengeluaran'] = $idpengeluaran;      
        $data['tglstatusterkirim'] = $tglstatusterkirim;      
        $data['rowpengeluaran'] = $rspengeluaran->row();      
        $data['menu'] = 'konfirmasiterkirim';
        $this->load->view('konfirmasiterkirim/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Konfrimasiterkirim_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {

            	if ($rowdata->statusterkirim=='Sudah Terkirim') {
            		$statusterkirim = '<span class="badge badge-success">'.$rowdata->statusterkirim.'</span><br><span>Tgl. '.tglindonesia($rowdata->tglstatusterkirim).'</span>';
            	}else{
            		$statusterkirim = '<span class="badge badge-danger">'.$rowdata->statusterkirim.'</span>';
            	}
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = tglindonesia($rowdata->tglpengeluaran).'<br>'.$rowdata->idpengeluaran;
                $row[] = $rowdata->deskripsi;
                $row[] = $rowdata->namagudang;
                $row[] = 'Rp. '.format_rupiah($rowdata->jumlahpengeluaran);
                $row[] = $statusterkirim;


                $row[] = '<a href="'.site_url( 'konfirmasiterkirim/konfirmasi/'.$this->encrypt->encode($rowdata->idpengeluaran) ).'" class="btn btn-info"><i class="fa fa-check-circle"></i> Konfirmasi</a>
                ';

                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Konfrimasiterkirim_model->count_all(),
                        "recordsFiltered" => $this->Konfrimasiterkirim_model->count_filtered(),
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
                $data[] = $row;
            }
        }

        $output = array(
                        "data" => $data,
                        );

        //output to json format
        echo json_encode($output);
    }

    public function simpan()
    {
        $idpengeluaran           = $this->input->post('idpengeluaran');
        $idstatusterkirim           = $this->input->post('idstatusterkirim');
        $tglstatusterkirim           = $this->input->post('tglstatusterkirim');
        $diterimaoleh           = $this->input->post('diterimaoleh');
        $statusterkirim           = $this->input->post('statusterkirim');
        $created_at           = date('Y-m-d H:i:s');
        $updated_at           = date('Y-m-d H:i:s');
        $idpengguna           = $this->session->userdata('idpengguna');

        //jika session berakhir
        if (empty($idpengguna)) { 
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Session telah berakhir", "error")</script>';        
	        $this->session->set_flashdata('pesan', $pesan);
	        redirect('login'); 
        }               

        if ($statusterkirim=='Sudah Terkirim') {        	
	        if ($idstatusterkirim=='') {            
	            $idstatusterkirim = $this->db->query("select create_idstatusterkirim('".date('Y-m-d')."') as idstatusterkirim ")->row()->idstatusterkirim;            
	        }
	        $query = "
					INSERT INTO pengeluaranstatusterkirim(idstatusterkirim, idpengeluaran, tglstatusterkirim, diterimaoleh, created_at, updated_at, idpengguna) 
					VALUES('$idstatusterkirim', '$idpengeluaran', '$tglstatusterkirim', '$diterimaoleh', '$created_at', '$updated_at', '$idpengguna')
					ON DUPLICATE KEY UPDATE  idstatusterkirim='$idstatusterkirim', idpengeluaran='$idpengeluaran', tglstatusterkirim='$tglstatusterkirim', diterimaoleh='$diterimaoleh', updated_at='$updated_at', idpengguna='$idpengguna'
	        		";
	        $simpan1  = $this->db->query($query);	        
        }else{
        	$query = "
					delete from pengeluaranstatusterkirim where idpengeluaran='$idpengeluaran'
	        		";
	        $simpan1  = $this->db->query($query);	        
        }

        $simpan2 = false;
        if ($simpan1) {
	        $arrayhead = array(
	                                'statusterkirim' => $statusterkirim,
	                                );
	        $simpan2  = $this->Konfrimasiterkirim_model->update($arrayhead, $idpengeluaran);
        }


        if ($simpan2) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('konfirmasiterkirim');  
    }

}

/* End of file Konfirmasiterkirim.php */
/* Location: ./application/controllers/Konfirmasiterkirim.php */