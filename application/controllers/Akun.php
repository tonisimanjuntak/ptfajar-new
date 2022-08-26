<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->authlogin();
        $this->load->model('Akun_model');
    }

    public function index()
    {
        $data['menu'] = 'akun';
        $this->load->view('akun/listdata', $data);
    }   

    public function tambah()
    {       
        $data['kodeakun'] = '';        
        $data['ltambah'] = '1';        
        $data['menu'] = 'akun';  
        $this->load->view('akun/form', $data);
    }

    public function edit($kodeakun)
    {       
        $kodeakun = $this->encrypt->decode($kodeakun);

        if ($this->Akun_model->get_by_id($kodeakun)->num_rows()<1) {
            $pesan = '<script>swal("Ilegal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('akun');
            exit();
        };
        $data['kodeakun'] =$kodeakun;        
        $data['ltambah'] = '0';        
        $data['menu'] = 'akun';
        $this->load->view('akun/form', $data);
    }

    public function datatablesource()
    {
        $RsData = $this->Akun_model->get_datatables();
        $no = $_POST['start'];
        $data = array();

        if ($RsData->num_rows()>0) {
            foreach ($RsData->result() as $rowdata) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $rowdata->kodeakun;
                $row[] = $rowdata->namaakun;
                $row[] = $rowdata->namaparentakun;
                $row[] = $rowdata->level;
                // $row[] = '<a href="'.site_url( 'akun/edit/'.$this->encrypt->encode($rowdata->kodeakun) ).'" class="btn btn-sm btn-warning btn-circle"><i class="fa fa-edit"></i></a> | 
                //         <a href="'.site_url('akun/delete/'.$this->encrypt->encode($rowdata->kodeakun) ).'" class="btn btn-sm btn-danger btn-circle" id="hapus"><i class="fa fa-trash"></i></a>';

                $row[] = '
                    <div class="btn-group">
                      <a href="'.site_url( 'akun/edit/'.$this->encrypt->encode($rowdata->kodeakun) ).'" class="btn btn-warning">Edit</a>
                      <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="'.site_url('akun/delete/'.$this->encrypt->encode($rowdata->kodeakun) ).'" id="hapus">Hapus</a>
                      </div>
                    </div>
                ';
                $data[] = $row;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Akun_model->count_all(),
                        "recordsFiltered" => $this->Akun_model->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete($kodeakun)
    {
        $kodeakun = $this->encrypt->decode($kodeakun);  
        $rsdata = $this->Akun_model->get_by_id($kodeakun);
        if ($rsdata->num_rows()<1) {
            $pesan = '<script>swal("Gagal!", "Data tidak ditemukan.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('akun');
            exit();
        };

        $jlhparent = $this->db->query("select count(*) as jlhparent from akun where parentakun='".$kodeakun."'")->row()->jlhparent;
        if ($jlhparent>0) {
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus karena sudah digunakan sebagai parent akun.", "error")</script>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('akun');
            exit();
        };

        $hapus = $this->Akun_model->hapus($kodeakun);
        if ($hapus) {       
            $pesan = '<script>swal("Berhasil!", "Data berhasil dihapus.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal dihapus! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('akun');        

    }

    public function simpan()
    {       
        $kodeakun             = $this->input->post('kodeakun');
        $ltambah             = $this->input->post('ltambah');
        $namaakun        = $this->input->post('namaakun');
        $parentakun        = $this->input->post('parentakun');
        $tglinsert          = date('Y-m-d H:i:s');

        if (empty($parentakun)) {
            $parentakun = NULL;
            $level = 1;
        }else{
            $levelparent = $this->Akun_model->get_by_id($parentakun)->row()->level;
            $level = $levelparent + 1;            
        }

        if ( $ltambah=='1' ) {  
            $data = array(
                            'kodeakun'   => $kodeakun, 
                            'namaakun'   => $namaakun, 
                            'parentakun'   => $parentakun, 
                            'level'   => $level, 
                            'jumlahpersediaan'   => 0, 
                        );

            $simpan = $this->Akun_model->simpan($data);      
        }else{ 
            $data = array(
                            'namaakun'   => $namaakun, 
                        );
            $simpan = $this->Akun_model->update($data, $kodeakun);
        }

        if ($simpan) {
            $pesan = '<script>swal("Berhasil!", "Data berhasil disimpan.", "success")</script>';
        }else{
            $eror = $this->db->error();         
            $pesan = '<script>swal("Gagal!", "Data gagal disimpan! Pesan Eror: '.$eror['code'].' '.$eror['message'].'", "error")</script>';
        }

        $this->session->set_flashdata('pesan', $pesan);
        redirect('akun');   
    }
    
    public function get_edit_data()
    {
        $kodeakun = $this->input->post('kodeakun');
        $RsData = $this->Akun_model->get_by_id($kodeakun)->row();

        $data = array( 
                            'kodeakun'     =>  $RsData->kodeakun,  
                            'namaakun'     =>  $RsData->namaakun,  
                            'parentakun'     =>  $RsData->parentakun,  
                            'jumlahpersediaan'     =>  $RsData->jumlahpersediaan,  
                        );

        echo(json_encode($data));
    }


}

/* End of file Akun.php */
/* Location: ./application/controllers/Akun.php */