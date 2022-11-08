<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_model extends CI_Model {

	// ------------------------- >   Ubah Data Disini Aja

    var $tabelview = 'v_penerimaan';
    var $tabel     = 'penerimaan';
    var $idpenerimaan = 'idpenerimaan';

    var $column_order = array(null,'tglpenerimaan','deskripsi','namagudang','jenispenerimaan','jumlahpenerimaan', null );
    var $column_search = array('tglpenerimaan','deskripsi','namagudang','jenispenerimaan','jumlahpenerimaan', 'namasupplier');
    var $order = array('idpenerimaan' => 'desc'); // default order 

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

    public function get_by_id($idpenerimaan)
    {
        $this->db->where('idpenerimaan', $idpenerimaan);
        return $this->db->get($this->tabelview);
    }

    public function hapus($idpenerimaan)
    {
        $this->db->trans_begin();

        $this->hapusDetail($idpenerimaan);
        $this->db->where('idpenerimaan', $idpenerimaan);      
        $this->db->delete('penerimaan');


        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function simpan($arrayhead, $arraydetail, $idpenerimaan)
    {       
        $this->db->trans_begin();
        $this->db->insert('penerimaan', $arrayhead);
        $this->simpanDetail($arraydetail, $arrayhead['tglpenerimaan']);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function update($arrayhead, $arraydetail, $idpenerimaan)
    {
        $this->db->trans_begin();
        $this->db->where('idpenerimaan', $idpenerimaan);
        $this->db->update('penerimaan', $arrayhead);
        $this->hapusDetail($idpenerimaan);
        $this->simpanDetail($arraydetail, $arrayhead['tglpenerimaan']);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function hapusDetail($idpenerimaan)
    {

        $rowPenerimaan = $this->db->query("
                select * from penerimaan where idpenerimaan='".$idpenerimaan."'
            ")->row();

        $rsPenerimaanDetail = $this->db->query("
                select * from penerimaandetail where idpenerimaan='".$idpenerimaan."'
            ");

        if ($rsPenerimaanDetail->num_rows()>0) {
            foreach ($rsPenerimaanDetail->result() as $row) {
                
                $this->db->query("
                            delete from penerimaandetail where idpenerimaan='".$idpenerimaan."' and kodeakun='".$row->kodeakun."' and jumlahbarang=".$row->jumlahbarang." 
                            ");

                //Kartu Stok
                $stokawal = $this->App->get_stok_akhir($row->kodeakun);
                $jumlahmasuk = 0;
                $jumlahkeluar = $row->jumlahbarang;
                $stokakhir = $stokawal + $jumlahmasuk - $jumlahkeluar;
                $deskripsi = 'Penerimaan Dihapus Oleh '.$this->session->userdata('namapengguna');

                $idkartustok = $this->db->query("SELECT create_idkartustok('".date('Y-m-d')."') as idkartustok")->row()->idkartustok;
                $dataKartuStok = array(
                                            'idkartustok' => $idkartustok, 
                                            'kodeakun' => $row->kodeakun, 
                                            'tglinsert' => date('Y-m-d H:i:s'), 
                                            'idtransaksi' => $row->idpenerimaan, 
                                            'tgltransaksi' => $rowPenerimaan->tglpenerimaan, 
                                            'jenistransaksi' => 'Penerimaan', 
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
            //Penerimaan Detail
            
            $dataPenerimaanDetail= array(
                                            'idpenerimaan' => $row['idpenerimaan'], 
                                            'kodeakun' => $row['kodeakun'], 
                                            'jumlahbarang' => $row['jumlahbarang'], 
                                            'hargabeli' => $row['hargabeli'], 
                                            'hargajual' => $row['hargajual'], 
                                            'totalharga' => $row['totalharga']
                                        );          
            $this->db->insert('penerimaandetail', $dataPenerimaanDetail);
            $stokawal = $this->App->get_stok_akhir($row['kodeakun']);
            $jumlahmasuk = $row['jumlahbarang'];
            $jumlahkeluar = 0;
            $stokakhir = $stokawal + $jumlahmasuk - $jumlahkeluar;
            $deskripsi = 'Penerimaan Ditambahkan Oleh '.$this->session->userdata('namapengguna');

            $idkartustok = $this->db->query("SELECT create_idkartustok('".date('Y-m-d')."') as idkartustok")->row()->idkartustok;
            $dataKartuStok = array(
                                        'idkartustok' => $idkartustok, 
                                        'kodeakun' => $row['kodeakun'], 
                                        'tglinsert' => date('Y-m-d H:i:s'), 
                                        'idtransaksi' => $row['idpenerimaan'], 
                                        'tgltransaksi' => $tgltransaksi, 
                                        'jenistransaksi' => 'Penerimaan', 
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

    public function simpan_import_temp($sheet)
    {
      $numrow = 1;
      $kosong = 0;
      
      $this->db->query("delete from penerimaan_tempdetail");
      $this->db->query("delete from penerimaan_temp");

      $jenispenerimaanAllowed = array('Pembelian', 'Barang Masuk');
      $tglpenerimaan_old = '';
      $jenispenerimaan_old = '';
      $nourut_old='';
      $idpenerimaan_old = '';
      $idpenerimaan = '';
      $arraydetail = array();

      foreach($sheet as $row){ 
        $nourut = $row[0]; 
        $tglpenerimaan = $row[1];
        $deskripsi = $row[2];  
        $idgudang = $row[3];  
        $jenispenerimaan = $row[4];  
        $kodeakun = $row[5];  
        $jumlahbarang = $row[6];  
        $hargabeli = $row[7];  
        $totalharga = (int)$jumlahbarang*(int)$hargabeli;

        if($numrow > 1){ 

            if(empty($tglpenerimaan) && empty($tglpenerimaan_old))
              continue;

            if (empty($nourut) && empty($tglpenerimaan) && empty($deskripsi) && empty($idgudang) && empty($jenispenerimaan) && empty($kodeakun) && empty($jumlahbarang) && empty($hargabeli)) 
                continue;

            // if (empty($nourut_old) && empty($nourut)) 
            //     continue;


            if ($nourut!=$nourut_old && $nourut != "") {

                if (count($arraydetail)>0) {
                    $this->db->insert_batch('penerimaan_tempdetail', $arraydetail);
                }

                $arrayhead = array(
                        'tglpenerimaan' => date('Y-m-d', strtotime($tglpenerimaan)),
                        'deskripsi' => $deskripsi,
                        'idgudang' => $idgudang,
                        'jenispenerimaan' => $jenispenerimaan,
                        'jumlahpenerimaan' => 0
                );
                $this->db->insert('penerimaan_temp', $arrayhead);
                $idpenerimaan = $this->db->insert_id();
                $arraydetail = array(); 
            }

            array_push($arraydetail, array(
                                    'idpenerimaan' => $idpenerimaan, 
                                    'kodeakun' => $kodeakun, 
                                    'jumlahbarang' => $jumlahbarang, 
                                    'hargabeli' => $hargabeli, 
                                    'hargajual' => 0, 
                                    'totalharga' => $totalharga, 
                                ));

            $tglpenerimaan_old = $tglpenerimaan;
            $jenispenerimaan_old = $jenispenerimaan;
            $nourut_old = $nourut;
        }
        
        $numrow++; 
      }

      if (count($arraydetail)>0) {
            $this->db->insert_batch('penerimaan_tempdetail', $arraydetail);
        }
    }

    public function simpan_import($data)
    {
        return $this->db->insert_batch('akun', $data);
    }

}

/* End of file Penerimaan_model.php */
/* Location: ./application/models/Penerimaan_model.php */