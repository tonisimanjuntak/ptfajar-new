<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengeluaran_model extends CI_Model {

	// ------------------------- >   Ubah Data Disini Aja

    var $tabelview = 'v_pengeluaran';
    var $tabel     = 'pengeluaran';
    var $idpengeluaran = 'idpengeluaran';

    var $column_order = array(null,'tglpengeluaran','deskripsi','jenispengeluaran','jumlahpengeluaran', null );
    var $column_search = array('tglpengeluaran','deskripsi','jenispengeluaran','jumlahpengeluaran');
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

        $this->db->query('delete from pengeluarandetail where idpengeluaran="'.$idpengeluaran.'"');
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
        $this->db->query('delete from pengeluarandetail where idpengeluaran="'.$idpengeluaran.'"');
        $this->db->insert_batch('pengeluarandetail', $arraydetail);

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

        $this->db->query('delete from pengeluarandetail where idpengeluaran="'.$idpengeluaran.'"');
        $this->db->insert_batch('pengeluarandetail', $arraydetail);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

}

/* End of file Pengeluaran_model.php */
/* Location: ./application/models/Pengeluaran_model.php */