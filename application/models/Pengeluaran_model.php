<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_model extends CI_Model {

	// ------------------------- >   Ubah Data Disini Aja

    var $tabelview = 'v_pengeluaran';
    var $tabel     = 'pengeluaran';
    var $idpengeluaran = 'idpengeluaran';

    var $column_order = array(null,'tglpengeluaran','deskripsi', 'namagudang','jenispengeluaran','jumlahpengeluaran', null );
    var $column_search = array('tglpengeluaran','deskripsi', 'namagudang','jenispengeluaran','jumlahpengeluaran');
    var $order = array('idpengeluaran' => 'desc'); // default order 

    // ----------------------------


    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get();        
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->tabelview);
        $i = 0;
     
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0) {
                    $this->db->group_start(); // Untuk Menggabung beberapa kondisi "AND"
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }
        
        // -------------------------> Proses Order by        
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }

    function count_filtered()
    {
        $this->db->select('count(*) as jlh');
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->row()->jlh;
    }
 
    public function count_all()
    {
        $this->db->select('count(*) as jlh');
        return $this->db->get($this->tabelview)->row()->jlh;
    }

    public function get_all()
    {
        return $this->db->get($this->tabelview);
    }

    public function get_by_id($idpengeluaran)
    {
        $this->db->where('idpengeluaran', $idpengeluaran);
        return $this->db->get($this->tabelview);
    }

    public function hapus($idpengeluaran)
    {
        $this->db->trans_begin();

        $this->hapusDetail($idpengeluaran);        
        $this->db->where('idpengeluaran', $idpengeluaran);      
        $this->db->delete('pengeluaran');


        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function simpan($arrayhead, $arraydetail, $idpengeluaran)
    {       
        $this->db->trans_begin();
        $this->db->insert('pengeluaran', $arrayhead);
        $this->simpanDetail($arraydetail, $arrayhead['tglpengeluaran']);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function update($arrayhead, $arraydetail, $idpengeluaran)
    {
        $this->db->trans_begin();
        $this->db->where('idpengeluaran', $idpengeluaran);
        $this->db->update('pengeluaran', $arrayhead);
        $this->hapusDetail($idpengeluaran);
        $this->simpanDetail($arraydetail, $arrayhead['tglpengeluaran']);


        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }


    public function hapusDetail($idpengeluaran)
    {

        $rowPengeluaran = $this->db->query("
                select * from pengeluaran where idpengeluaran='".$idpengeluaran."'
            ")->row();

        $rsPengeluaranDetail = $this->db->query("
                select * from pengeluarandetail where idpengeluaran='".$idpengeluaran."'
            ");

        if ($rsPengeluaranDetail->num_rows()>0) {
            foreach ($rsPengeluaranDetail->result() as $row) {
                
                $this->db->query("
                            delete from pengeluarandetail where idpengeluaran='".$idpengeluaran."' and kodeakun='".$row->kodeakun."' and jumlahbarang=".$row->jumlahbarang." 
                            ");

                //Kartu Stok
                $stokawal = $this->App->get_stok_akhir($row->kodeakun);
                $jumlahmasuk = $row->jumlahbarang;
                $jumlahkeluar = 0;
                $stokakhir = $stokawal + $jumlahmasuk - $jumlahkeluar;
                $deskripsi = 'Pengeluaran Dihapus Oleh '.$this->session->userdata('namapengguna');

                $idkartustok = $this->db->query("SELECT create_idkartustok('".date('Y-m-d')."') as idkartustok")->row()->idkartustok;
                $dataKartuStok = array(
                                            'idkartustok' => $idkartustok, 
                                            'kodeakun' => $row->kodeakun, 
                                            'tglinsert' => date('Y-m-d H:i:s'), 
                                            'idtransaksi' => $row->idpengeluaran, 
                                            'tgltransaksi' => $rowPengeluaran->tglpengeluaran, 
                                            'jenistransaksi' => 'Pengeluaran', 
                                            'aksi' => 'Delete', 
                                            'stokawal' => $stokawal, 
                                            'jumlahmasuk' => $jumlahmasuk, 
                                            'jumlahkeluar' => $jumlahkeluar, 
                                            'stokakhir' => $stokakhir,
                                            'deskripsi' => $deskripsi
                                        );
                $this->db->insert('kartustok', $dataKartuStok);
            }
        }
    }

    public function simpanDetail($arraydetail, $tgltransaksi)
    {

        foreach ($arraydetail as $key => $row) {          
            //pengeluaran Detail
            
            $dataPengeluarandetail= array(
                                            'idpengeluaran' => $row['idpengeluaran'], 
                                            'kodeakun' => $row['kodeakun'], 
                                            'jumlahbarang' => $row['jumlahbarang'], 
                                            'hargabeli' => $row['hargabeli'], 
                                            'hargajual' => $row['hargajual'], 
                                            'totalharga' => $row['totalharga']
                                        );          
            $this->db->insert('pengeluarandetail', $dataPengeluarandetail);

            //Kartu Stok
            $stokawal = $this->App->get_stok_akhir($row['kodeakun']);
            $jumlahmasuk = 0;
            $jumlahkeluar = $row['jumlahbarang'];
            $stokakhir = $stokawal + $jumlahmasuk - $jumlahkeluar;
            $deskripsi = 'Pengeluaran Ditambahkan Oleh '.$this->session->userdata('namapengguna');

            $idkartustok = $this->db->query("SELECT create_idkartustok('".date('Y-m-d')."') as idkartustok")->row()->idkartustok;
            $dataKartuStok = array(
                                        'idkartustok' => $idkartustok, 
                                        'kodeakun' => $row['kodeakun'], 
                                        'tglinsert' => date('Y-m-d H:i:s'), 
                                        'idtransaksi' => $row['idpengeluaran'], 
                                        'tgltransaksi' => $tgltransaksi, 
                                        'jenistransaksi' => 'Pengeluaran', 
                                        'aksi' => 'Insert', 
                                        'stokawal' => $stokawal, 
                                        'jumlahmasuk' => $jumlahmasuk, 
                                        'jumlahkeluar' => $jumlahkeluar, 
                                        'stokakhir' => $stokakhir,
                                        'deskripsi' => $deskripsi
                                    );
            $this->db->insert('kartustok', $dataKartuStok);
        }
    }


}

/* End of file Pengeluaran_model.php */
/* Location: ./application/models/Pengeluaran_model.php */