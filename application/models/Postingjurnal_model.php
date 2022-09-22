<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postingjurnal_model extends CI_Model {

	// ------------------------- >   Ubah Data Disini Aja

    var $tabelview = 'v_postingjurnal';
    var $tabel     = 'postingjurnal';
    var $idposting = 'idposting';

    var $column_order = array(null,'tahun','bulan','tglposting', 'namapengguna', null );
    var $column_search = array('tahun','bulan', 'namapengguna');
    var $order = array('tahun' => 'desc', 'bulan' => 'asc'); // default order 

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

    public function get_by_id($idposting)
    {
        $this->db->where('idposting', $idposting);
        return $this->db->get($this->tabelview);
    }


    public function get_detail_by_id($idposting)
    {
        $this->db->where('idposting', $idposting);
        return $this->db->get('v_postingjurnaldetail');
    }

    public function hapus($tahun, $bulan, $idposting)
    {
        $this->db->trans_begin();

        $this->db->query("
        				delete from jurnaldetail where idjurnal in 
        				(select idjurnal from jurnal where jenistransaksi<>'Jurnal Penyesuaian' and month(tgljurnal)='$bulan' and year(tgljurnal)='$tahun');
        			");

        $this->db->query("
        				delete from jurnal where jenistransaksi<>'Jurnal Penyesuaian' and month(tgljurnal)='$bulan' and year(tgljurnal)='$tahun';
        			");


        $this->db->where('idposting', $idposting);      
        $this->db->delete('postingjurnal');


        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }


    public function mulaiposting($tahun, $bulan)
    {
        $this->db->trans_begin();

        $rsPenerimaan = $this->db->query("
        			select idpenerimaan, tglpenerimaan, deskripsi, parentakun, sum(totalharga) as totalharga from v_penerimaandetail2 where month(tglpenerimaan)='$bulan' and year(tglpenerimaan)='$tahun' 
        				group by idpenerimaan, tglpenerimaan, deskripsi, parentakun
        				order by tglpenerimaan, idpenerimaan, parentakun, kodeakun
        		");

        $idpenerimaan_old = '';
        $parentakun_old = '';

       	if ($rsPenerimaan->num_rows()>0) {
       		foreach ($rsPenerimaan->result() as $rowPenerimaan) {

       			if ($idpenerimaan_old != $rowPenerimaan->idpenerimaan) {
       				
	       			$dataJurnal = array(
	       								'idjurnal' => $rowPenerimaan->idpenerimaan, 
	       								'tgljurnal' => $rowPenerimaan->tglpenerimaan, 
	       								'deskripsi' => $rowPenerimaan->deskripsi, 
	       								'jumlah' => $rowPenerimaan->totalharga, 
	       								'tglinsert' => date('Y-m-d H:i:s'), 
	       								'tglupdate' => date('Y-m-d H:i:s'),  
	       								'idpengguna' => $this->session->userdata('idpengguna'), 
	       								'jenistransaksi' => 'Penerimaan Barang', 
	       								'tahunperiode' => $tahun, 
	       							);
	       			
	       			$this->db->insert('jurnal', $dataJurnal);
       			}

       			$dataJurnalDetail = array();





       			$parentakun_old = $rowPenerimaan->parentakun;
       			$idpenerimaan_old = $rowPenerimaan->idpenerimaan;
       		}
       	}


    	if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

}

/* End of file Postingjurnal_model.php */
/* Location: ./application/models/Postingjurnal_model.php */