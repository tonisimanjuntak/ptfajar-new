<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldoawal_model extends CI_Model {

	var $tabelview = 'v_saldoawal';
    var $tabel     = 'saldoawal';
    var $idsaldoawal = 'idsaldoawal';

    var $column_order = array(null,'tahunanggaran', 'namajenisakun', 'deskripsi', 'namapengguna', null );
    var $column_search = array('tahunanggaran', 'namajenisakun', 'deskripsi', 'namapengguna');
    var $order = array('tahunanggaran' => 'desc', 'jenisakun' => 'asc'); // default order 


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
                    $this->db->group_start(); 
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

    public function get_by_id($idsaldoawal)
    {
        $this->db->where('idsaldoawal', $idsaldoawal);
        return $this->db->get($this->tabelview);
    }

    public function hapus($idsaldoawal)
    {
    	$this->db->query("delete from saldoawaldetail where idsaldoawal='$idsaldoawal'");
        $this->db->where('idsaldoawal', $idsaldoawal);      
        return $this->db->delete($this->tabel);
    }

    public function simpan($data, $arrDetail)
    {       
        $this->db->trans_begin();

        $this->db->insert($this->tabel, $data);

        $totaldebet = 0;
        $totalkredit = 0;

        foreach ($arrDetail as $key => $row) {        	
        	//SO Detail
        	$datasaldoawaldetail= array(
        									'idsaldoawal' => $row['idsaldoawal'], 
        									'kodeakun' => $row['kodeakun'], 
        									'debet' => $row['debet'], 
        									'kredit' => $row['kredit']
        								);        	
        	$totaldebet += $row['debet'];
        	$totalkredit += $row['kredit'];

	        $this->db->insert('saldoawaldetail', $datasaldoawaldetail);
        }

        $this->db->query("update saldoawal set totaldebet=$totaldebet, totalkredit=$totalkredit where idsaldoawal='".$data['idsaldoawal']."'");

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }    

    public function update($data, $arrDetail, $idsaldoawal)
    {       
        $this->db->trans_begin();

        $this->db->query("
        		delete from saldoawaldetail where idsaldoawal='$idsaldoawal'
        	");

        $this->db->where('idsaldoawal', $idsaldoawal);
        $this->db->update($this->tabel, $data);


        $totaldebet = 0;
        $totalkredit = 0;

        foreach ($arrDetail as $key => $row) {        	
        	//SO Detail
        	$datasaldoawaldetail= array(
        									'idsaldoawal' => $row['idsaldoawal'], 
        									'kodeakun' => $row['kodeakun'], 
        									'debet' => $row['debet'], 
        									'kredit' => $row['kredit']
        								);        	
        	$totaldebet += $row['debet'];
        	$totalkredit += $row['kredit'];

	        $this->db->insert('saldoawaldetail', $datasaldoawaldetail);
        }

        $this->db->query("update saldoawal set totaldebet=$totaldebet, totalkredit=$totalkredit where idsaldoawal='".$data['idsaldoawal']."'");

        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
                $this->db->trans_commit();
                return true;
        }
    }    

    public function get_saldo_barang($tahunanggaran='', $jenisakun='')
    {

    	$nlenjenisakun = strlen($jenisakun);

    	if (!empty($tahunanggaran)) {
    		
	    	$rssaldoawal = $this->db->query("
	    			SELECT
					  `akun`.`kodeakun`           AS `kodeakun`,
					  `akun`.`namaakun`           AS `namaakun`,
					  `akun`.`parentakun`         AS `parentakun`,
					  `akun`.`level`              AS `level`,
					  `saldoawal`.`tahunanggaran` AS `tahunanggaran`,
					  CASE WHEN `saldoawaldetail`.`debet` IS NULL THEN 0 ELSE `saldoawaldetail`.`debet` END  AS `debet`,
					  CASE WHEN `saldoawaldetail`.`kredit` IS NULL THEN 0 ELSE `saldoawaldetail`.`kredit` END  AS `kredit`
					FROM (`akun`
					    LEFT JOIN `saldoawaldetail`
					      ON (`saldoawaldetail`.`kodeakun` = `akun`.`kodeakun`)
					   LEFT JOIN `saldoawal`
					     ON (`saldoawaldetail`.`idsaldoawal` = `saldoawal`.`idsaldoawal`) and `saldoawal`.tahunanggaran='".$tahunanggaran."'  )
					   where `level` = 4 and akun.parentakun='$jenisakun' order by akun.kodeakun
	    		");

    	}else{
    		$rssaldoawal = $this->db->query("
	    			SELECT
					  `akun`.`kodeakun`           AS `kodeakun`,
					  `akun`.`namaakun`           AS `namaakun`,
					  `akun`.`parentakun`         AS `parentakun`,
					  `akun`.`level`              AS `level`,
					  '' AS `tahunanggaran`,
					  0   AS `debet`,
					  0  AS `kredit`
					FROM `akun` and akun.parentakun='$jenisakun' ");
    	}
    	return $rssaldoawal;
    }

}

/* End of file Saldoawal_model.php */
/* Location: ./application/models/Saldoawal_model.php */