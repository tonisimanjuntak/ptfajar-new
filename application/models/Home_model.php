<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	public function get_akun_barang($kodeakun='')
	{
		$where_akun = '';
		if (!empty($kodeakun)) {
			$where_akun = ' and kodeakun="'.$kodeakun.'" ';
		}

		$kodeakunbarang = $this->db->query("select kodeakunbarang from pengaturan")->row()->kodeakunbarang;
		$nlen = strlen($kodeakunbarang);

        $level = $this->db->query("select max(level) as level from akun")->row()->level;
        $rsakun = $this->db->query("select * from akun where left(kodeakun, ".$nlen.")  = '".$kodeakunbarang."' and level=".$level.$where_akun." order by kodeakun limit 5");
        return $rsakun;
	}

	public function getchartakun($tahun='', $kodeakun='')
	{
		if (empty($tahun)) {
			$tahun = date('Y');
		}
		$where_akun = '';
		if (!empty($kodeakun)) {
			$where_akun = " where kodeakun='$kodeakun' ";
		}
        $query = "SELECT SUM( CASE WHEN MONTH(tglpenerimaan)=1 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpenerimaan)=1 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpenerimaan)=2 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln02,
		SUM( CASE WHEN MONTH(tglpenerimaan)=3 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln03,
		SUM( CASE WHEN MONTH(tglpenerimaan)=4 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln04,
		SUM( CASE WHEN MONTH(tglpenerimaan)=5 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln05,
		SUM( CASE WHEN MONTH(tglpenerimaan)=6 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln06,
		SUM( CASE WHEN MONTH(tglpenerimaan)=7 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln07,
		SUM( CASE WHEN MONTH(tglpenerimaan)=8 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln08,
		SUM( CASE WHEN MONTH(tglpenerimaan)=9 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln09,
		SUM( CASE WHEN MONTH(tglpenerimaan)=10 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln10,
		SUM( CASE WHEN MONTH(tglpenerimaan)=11 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln11,
		SUM( CASE WHEN MONTH(tglpenerimaan)=12 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln12	
	FROM v_penerimaandetail ".$where_akun;
       return $this->db->query($query);
	}	



	public function getchartakunpertanggal($tglawal='', $tglakhir='', $kodeakun='')
	{
		$and_where = '';
		$and_tanggal = '';

		if (!empty($tglawal) and !empty($tglakhir)) {
			$tahun = date('Y', strtotime($tglawal));
			$and_tanggal .= " and tglpenerimaan between '".date('Y-m-d', strtotime($tglawal))."' and '".date('Y-m-d', strtotime($tglakhir))."' ";
		}else{
			$tahun = date('Y');
		}

		if (!empty($kodeakun)) {
			$and_where .= " and akun.kodeakun='$kodeakun' ";
		}

        $query = "
        SELECT akun.kodeakun, akun.namaakun,
    	SUM( CASE WHEN MONTH(tglpenerimaan)=1 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpenerimaan)=1 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpenerimaan)=2 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln02,
		SUM( CASE WHEN MONTH(tglpenerimaan)=3 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln03,
		SUM( CASE WHEN MONTH(tglpenerimaan)=4 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln04,
		SUM( CASE WHEN MONTH(tglpenerimaan)=5 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln05,
		SUM( CASE WHEN MONTH(tglpenerimaan)=6 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln06,
		SUM( CASE WHEN MONTH(tglpenerimaan)=7 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln07,
		SUM( CASE WHEN MONTH(tglpenerimaan)=8 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln08,
		SUM( CASE WHEN MONTH(tglpenerimaan)=9 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln09,
		SUM( CASE WHEN MONTH(tglpenerimaan)=10 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln10,
		SUM( CASE WHEN MONTH(tglpenerimaan)=11 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln11,
		SUM( CASE WHEN MONTH(tglpenerimaan)=12 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln12	
		FROM akun LEFT JOIN v_penerimaandetail ON akun.kodeakun=v_penerimaandetail.kodeakun $and_tanggal
			WHERE LEFT(akun.kodeakun,2)='13' AND akun.level=4 ".$and_where." GROUP BY akun.kodeakun, akun.namaakun";
       return $this->db->query($query);
	}	

	public function getchartakunpertanggal2($tglawal='', $tglakhir='', $kodeakun='')
	{
		$and_where = '';
		$and_tanggal = '';

		if (!empty($tglawal) and !empty($tglakhir)) {
			$tahun = date('Y', strtotime($tglawal));
			$and_tanggal .= " and tglpenerimaan between '".date('Y-m-d', strtotime($tglawal))."' and '".date('Y-m-d', strtotime($tglakhir))."' ";
		}else{
			$tahun = date('Y');
		}

		if (!empty($kodeakun)) {
			$and_where .= " and akun.kodeakun='$kodeakun' ";
		}

        $query = " SELECT SUM( CASE WHEN MONTH(tglpenerimaan)=1 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpenerimaan)=1 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln01,
		SUM( CASE WHEN MONTH(tglpenerimaan)=2 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln02,
		SUM( CASE WHEN MONTH(tglpenerimaan)=3 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln03,
		SUM( CASE WHEN MONTH(tglpenerimaan)=4 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln04,
		SUM( CASE WHEN MONTH(tglpenerimaan)=5 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln05,
		SUM( CASE WHEN MONTH(tglpenerimaan)=6 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln06,
		SUM( CASE WHEN MONTH(tglpenerimaan)=7 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln07,
		SUM( CASE WHEN MONTH(tglpenerimaan)=8 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln08,
		SUM( CASE WHEN MONTH(tglpenerimaan)=9 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln09,
		SUM( CASE WHEN MONTH(tglpenerimaan)=10 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln10,
		SUM( CASE WHEN MONTH(tglpenerimaan)=11 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln11,
		SUM( CASE WHEN MONTH(tglpenerimaan)=12 AND YEAR(tglpenerimaan)='$tahun' THEN hargabeli ELSE 0 END ) AS bln12	
		FROM akun LEFT JOIN v_penerimaandetail ON akun.kodeakun=v_penerimaandetail.kodeakun $and_tanggal
			WHERE LEFT(akun.kodeakun,2)='13' AND akun.level=4 ".$and_where."";
       return $this->db->query($query);
	}	



}

/* End of file Home_model.php */
/* Location: ./application/models/Home_model.php */