<!DOCTYPE html>
<html>
<head>
    <title>PT Basyir Karya Utama</title>
</head>
<body>
<style type="text/css">
body{
    font-family: sans-serif;
}
</style>
 
 

<?php

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Lap Jurnal.xls");




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

$table = '
    <br>
    <br>
    <table style="" border="0" cellpadding="2">
        <tbody>
            <tr>
                <td style="width:100%; text-align:center;">
                    <span style="">JURNAL UMUM</span><br>
                    <span style="font-size: 14px;">PERIODE : '.$periode.'</span>
                </td>                
            </tr>
        </tbody>
    </table>
';




$table  .= '<br><table border="1" width="100%" cellpadding="5">';
$table .= ' 
            <thead>
                <tr style="background-color:#ccc;">
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="5%">NO</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="10%">TANGGAL</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="10%">KODE AKUN</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="35%">NAMA AKUN</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="20%">KETERANGAN</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="10%">DEBET</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="10%">KREDIT</th>
                </tr>
            </thead>
            <tbody>';

$tgljurnal          = '';
$tgljurnal_lama     = '';
$sumDebet           = 0;
$sumKredit          = 0;

$no=1;
$idjurnal_old ='';

if ($rslaporan->num_rows() > 0) {
    
    foreach ($rslaporan->result() as $row) {

        $debet = $row->debet;
        $kredit = $row->kredit;


        if ($debet==0 && $kredit!=0) {
            $spasi = str_repeat('&nbsp;', 10);
        }else{
            $spasi = '';
        }

        if ($idjurnal_old != $row->idjurnal) {
            
            $table .='          
                    <tr>
                        <td style="font-size:11px; text-align:center;" width="5%">'.$no++.'</td>
                        <td style="font-size:11px; text-align:center;" width="10%">'.tglindonesia($row->tgljurnal).'</td>
                        <td style="font-size:11px; text-align:center;" width="10%">'.$row->kodeakun.'</td>
                        <td style="font-size:11px; text-align:left;" width="35%">'.$spasi.$row->namaakun.'</td>
                        <td style="font-size:11px; text-align:left;" width="20%">'.$row->deskripsidetail.'</td>
                        <td style="font-size:11px; text-align:right;" width="10%">'.format_rupiah($debet).'</td>
                        <td style="font-size:11px; text-align:right;" width="10%">'.format_rupiah($kredit).'</td>
                    </tr>';
        }else{
             $table .='          
                    <tr>
                        <td style="font-size:11px; text-align:center;" width="5%"></td>
                        <td style="font-size:11px; text-align:center;" width="10%"></td>
                        <td style="font-size:11px; text-align:center;" width="10%">'.$row->kodeakun.'</td>
                        <td style="font-size:11px; text-align:left;" width="35%">'.$spasi.$row->namaakun.'</td>
                        <td style="font-size:11px; text-align:left;" width="20%">'.$row->deskripsidetail.'</td>
                        <td style="font-size:11px; text-align:right;" width="10%">'.format_rupiah($debet).'</td>
                        <td style="font-size:11px; text-align:right;" width="10%">'.format_rupiah($kredit).'</td>
                    </tr>';
            
        }

        $idjurnal_old = $row->idjurnal;
        $sumDebet       += $row->debet;
        $sumKredit      += $row->kredit;
    }

}else{

    $table .='          
                <tr>
                    <th style="font-size:11px; text-align:center;" width="100%" colspan="7">Data Tidak Ada . . .</th>                   
                </tr>';
}
            


$table .='          
                    <tr style="font-weight: bold;">
                        <td style="font-size:11px; text-align:center;" width="80%" colspan="5">TOTAL</td>
                        <td style="font-size:11px; text-align:right;" width="10%">'.format_rupiah($sumDebet).'</td>
                        <td style="font-size:11px; text-align:right;" width="10%">'.format_rupiah($sumKredit).'</td>
                    </tr>';


$table .= ' </tbody>
            </table>';




echo $table;

?>


</body>
</html>