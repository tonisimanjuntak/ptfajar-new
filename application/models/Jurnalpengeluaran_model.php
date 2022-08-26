<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnalpengeluaran_model extends CI_Model {

    // ------------------------- >   Ubah Data Disini Aja

    var $tabelview = 'jurnalpengeluaran';
    var $tabel     = 'jurnalpengeluaran';
    var $idjurnalpengeluaran = 'idjurnalpengeluaran';

    var $column_order = array(null,'tgljurnalpengeluaran','deskripsi','jenispengeluaran','jenistransaksi','jumlahpengeluaran','created_at','updated_at','idpengguna' );
    var $column_search = array('tgljurnalpengeluaran','deskripsi','jenispengeluaran','jenistransaksi','jumlahpengeluaran','created_at','updated_at','idpengguna');
    var $order = array('idjurnalpengeluaran' => 'desc'); // default order 

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

    public function get_by_id($idjurnalpengeluaran)
    {
        $this->db->where('idjurnalpengeluaran', $idjurnalpengeluaran);
        return $this->db->get($this->tabelview);
    }

    public function hapus($idjurnalpengeluaran)
    {
        $this->db->trans_begin();

        $this->db->query('delete from jurnalpengeluarandetail where idjurnalpengeluaran="'.$idjurnalpengeluaran.'"');
        $this->db->where('idjurnalpengeluaran', $idjurnalpengeluaran);      
        $this->db->delete('jurnalpengeluaran');


        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function simpan($arrayhead, $arraydetail, $idjurnalpengeluaran)
    {       
        $this->db->trans_begin();

        $this->db->insert('jurnalpengeluaran', $arrayhead);
        $this->db->query('delete from jurnalpengeluarandetail where idjurnalpengeluaran="'.$idjurnalpengeluaran.'"');
        $this->db->insert_batch('jurnalpengeluarandetail', $arraydetail);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function update($arrayhead, $arraydetail, $idjurnalpengeluaran)
    {
        $this->db->trans_begin();
        $this->db->where('idjurnalpengeluaran', $idjurnalpengeluaran);
        $this->db->update('jurnalpengeluaran', $arrayhead);

        $this->db->query('delete from jurnalpengeluarandetail where idjurnalpengeluaran="'.$idjurnalpengeluaran.'"');
        $this->db->insert_batch('jurnalpengeluarandetail', $arraydetail);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

}

/* End of file Jurnalpengeluaran_model.php */
/* Location: ./application/models/Jurnalpengeluaran_model.php */