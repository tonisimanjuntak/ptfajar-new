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
header("Content-Disposition: attachment; filename=Stok Barang.xls");



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
                <td style="width:50%; text-align:center;">
                    <span style="">LAPORAN STOK BARANG</span><br>
                </td>                
            </tr>
        </tbody>
    </table>
';


$table .= '<table border="1" width="100%" cellpadding="5">';
$table .= ' 
            <thead>
                <tr style="background-color:#ccc; font-size:11px; font-weight:bold;">
                    <th style="text-align:center;" width="5%">NO</th>
                    <th style="text-align:center;" width="15%">KODE BARANG</th>
                    <th style="text-align:center;" width="65%">NAMA BARANG</th>
                    <th style="text-align:center;" width="15%">STOK</th>
                </tr>
            </thead>
            <tbody>';



$nomor = 1;
if ($rsstok->num_rows() > 0) {
    
    foreach ($rsstok->result() as $row) {

        $table .= '
                <tr style="font-size:10px;">
                    <td style="text-align:center;" width="5%">'.$nomor++.'</td>
                    <td style="text-align:center;" width="15%">'.$row->kodeakun.'</td>
                    <td style="text-align:left;" width="65%">'.$row->namaakun.'</td>
                    <td style="text-align:center;" width="15%">'.number_format($row->jumlahpersediaan).'</td>
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