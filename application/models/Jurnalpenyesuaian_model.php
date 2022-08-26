<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnalpenyesuaian_model extends CI_Model {

	// ------------------------- >   Ubah Data Disini Aja

    var $tabelview = 'v_jurnal';
    var $tabel     = 'jurnal';
    var $idjurnal = 'idjurnal';

    var $column_order = array(null,'tgljurnal','deskripsi','jumlah', null );
    var $column_search = array('tgljurnal','deskripsi','jumlah');
    var $order = array('idjurnal' => 'desc'); // default order 

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

    public function get_by_id($idjurnal)
    {
        $this->db->where('idjurnal', $idjurnal);
        return $this->db->get($this->tabelview);
    }


    public function get_detail_by_id($idjurnal)
    {
        $this->db->where('idjurnal', $idjurnal);
        return $this->db->get('v_jurnaldetail');
    }

    public function hapus($idjurnal)
    {
        $this->db->trans_begin();

        $this->db->query('delete from jurnaldetail where idjurnal="'.$idjurnal.'"');
        $this->db->where('idjurnal', $idjurnal);      
        $this->db->delete('jurnal');


        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function simpan($arrayhead, $arraydetail, $idjurnal)
    {       
        $this->db->trans_begin();

        $this->db->insert('jurnal', $arrayhead);
        $this->db->query('delete from jurnaldetail where idjurnal="'.$idjurnal.'"');
        $this->db->insert_batch('jurnaldetail', $arraydetail);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

    public function update($arrayhead, $arraydetail, $idjurnal)
    {
        $this->db->trans_begin();
        $this->db->where('idjurnal', $idjurnal);
        $this->db->update('jurnal', $arrayhead);

        $this->db->query('delete from jurnaldetail where idjurnal="'.$idjurnal.'"');
        $this->db->insert_batch('jurnaldetail', $arraydetail);

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }

}

/* End of file Jurnalpenyesuaian_model.php */
/* Location: ./application/models/Jurnalpenyesuaian_model.php */