<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penerimaan</title>
</head>
<body>
<style type="text/css">
body{
    font-family: sans-serif;
}
</style>
 
 

<?php

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Kartu Stok.xls");


if ($tglawal==$tglakhir) {
    $periode = strtoupper(tglindonesialengkap($tglawal));
}else{
    $periode = strtoupper(tglindonesialengkap($tglawal)).' S/D '.strtoupper(tglindonesialengkap($tglakhir));
}


if (!empty($rowpengaturan->logoperusahaan)) {
    $logoperusahaan = base_url('uploads/pengaturan/'.$rowpengaturan->logoperusahaan);
}else{
    $logoperusahaan = base_url('images/logo-default.jpg');
}

$table = '';

$table .= '
    <br>
    <br>
    <table style="" border="0" cellpadding="2">
        <tbody>
            <tr>
                <td style="width:100%; text-align:center;">
                    <span style="">RIWAYAT STOK</span><br>
                    <span style="font-size: 14px;">PERIODE : '.$periode.'</span>
                </td>                
            </tr>
        </tbody>
    </table>
';



$table  .= '<br>
            <table border="0" width="100%" cellpadding="5">
                <thead>
                    <tr style="font-size: 14px; font-weight: bold;">
                        <th style="width: 20%;">KODE AKUN BARANG</th>
                        <th style="width: 5%; text-align: center;">:</th>
                        <th style="width: 75%;">'.$rowakun->kodeakun.'</th>
                    </tr>
                    <tr style="font-size: 14px; font-weight: bold;">
                        <th style="width: 20%;">NAMA AKUN BARANG</th>
                        <th style="width: 5%; text-align: center;">:</th>
                        <th style="width: 75%;">'.$rowakun->namaakun.'</th>
                    </tr>
                </thead>
            </table>
            ';


$table  .= '<br><br><table border="1" width="100%" cellpadding="5">';
$table .= ' 
            <thead>
                <tr style="background-color:#ccc; font-size:11px; font-weight:bold;">
                    <th style="text-align:center;" width="5%">NO</th>
                    <th style="text-align:center;" width="15%">TANGGAL</th>
                    <th style="text-align:center;" width="10%">NO BUKTI</th>
                    <th style="text-align:center;" width="10%">TGL BUKTI</th>
                    <th style="text-align:center;" width="8%">STOK AWAL</th>
                    <th style="text-align:center;" width="8%">PENERIMAAN</th>
                    <th style="text-align:center;" width="8%">PENGELUARAN</th>
                    <th style="text-align:center;" width="10%">STOK AKHIR</th>
                    <th style="text-align:center;" width="26%">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>';


$nomor = $this->db->query("select count(*) as nomor from kartustok where kodeakun='$kodeakun' and CONVERT(tglinsert, DATE) < '".$tglawal."'")->row()->nomor;
$nomor++;
if ($rskartustok->num_rows() > 0) {
    
    foreach ($rskartustok->result() as $row) {

        

        $table .= '
                <tr style="font-size:10px;">
                    <td style="text-align:center;" width="5%">'.$nomor++.'</td>
                    <td style="text-align:center;" width="15%">'.date('d-m-Y H:i:s', strtotime($row->tglinsert)).'</td>
                    <td style="text-align:center;" width="10%">'.$row->idtransaksi.'</td>
                    <td style="text-align:center;" width="10%">'.$row->tgltransaksi.'</td>
                    <td style="text-align:center;" width="8%">'.number_format($row->stokawal).'</td>
                    <td style="text-align:center;" width="8%">'.number_format($row->jumlahmasuk).'</td>
                    <td style="text-align:center;" width="8%">'.number_format($row->jumlahkeluar).'</td>
                    <td style="text-align:center;" width="10%">'.number_format($row->stokakhir).'</td>
                    <td style="text-align:left;" width="26%">'.$row->deskripsi.'</td>
                </tr>
        ';

    }

}else{

    $table .='          
                <tr>
                    <th style="font-size:11px; text-align:center;" width="100%" colspan="8">Data Tidak Ada . . .</th>                   
                </tr>';
}
            



$table .= ' </tbody>
            </table>';


echo $table;

?>


</body>
</html>