<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	public function get_akun_barang()
	{
		$kodeakunbarang = $this->db->query("select kodeakunbarang from pengaturan")->row()->kodeakunbarang;
        $level = $this->db->query("select max(level) as level from akun")->row()->level;
        $rsakun = $this->db->query("select * from akun where kodeakun like '%".$kodeakunbarang."%' and level=".$level." order by kodeakun limit 5");
        return $rsakun;
	}

	public function getchartakun($tahun='', $kodeakun)
	{
		if (empty($tahun)) {
			$tahun = date('Y');
		}
        $query = "
        SELECT 
    	SUM( CASE WHEN MONTH(tglpengeluaran)=1 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpengeluaran)=1 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpengeluaran)=2 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln02,
		SUM( CASE WHEN MONTH(tglpengeluaran)=3 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln03,
		SUM( CASE WHEN MONTH(tglpengeluaran)=4 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln04,
		SUM( CASE WHEN MONTH(tglpengeluaran)=5 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln05,
		SUM( CASE WHEN MONTH(tglpengeluaran)=6 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln06,
		SUM( CASE WHEN MONTH(tglpengeluaran)=7 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln07,
		SUM( CASE WHEN MONTH(tglpengeluaran)=8 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln08,
		SUM( CASE WHEN MONTH(tglpengeluaran)=9 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln09,
		SUM( CASE WHEN MONTH(tglpengeluaran)=10 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln10,
		SUM( CASE WHEN MONTH(tglpengeluaran)=11 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln11,
		SUM( CASE WHEN MONTH(tglpengeluaran)=12 AND YEAR(tglpengeluaran)='$tahun' THEN jumlahbarang ELSE 0 END ) AS bln12	
	FROM v_pengeluarandetail where kodeakun='$kodeakun'
        	";
       return $this->db->query($query);
	}	

}

/* End of file Home_model.php */
/* Location: ./application/models/Home_model.php */