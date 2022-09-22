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
header("Content-Disposition: attachment; filename=".$namafile.".xls");


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


$table = '
    <br>
    <br>
    <table style="" border="0" cellpadding="2">
        <tbody>
            <tr>
                <td style="width:100%; text-align:center;">
                    <span style="">BUKU BESAR</span>
                </td>                
            </tr>
        </tbody>
    </table>
';

$table .= '

<style>
  .no-border-bottom {
    border-bottom: 1px solid #eee;
  }
  .no-border-top {
    border-top: 1px solid #eee;
  }
  .add-border-top {
    border-top: 1px solid black; 
  }
  .add-border-bottom {
    border-bottom: 1px solid black; 
  }
</style>
';


$table .= '<table border="0" cellpadding="5">
              <thead>
                <tr style="font-size:12px; font-weight:bold;">
                  <th width="15%">Tanggal Periode</th>
                  <th width="5%">:</th>
                  <th width="80%">'.strtoupper(tglindonesia($tglawal)).' S/D '.strtoupper(tglindonesia($tglakhir)).'</th>
                </tr>
                <tr style="font-size:12px; font-weight:bold;">
                  <th width="15%">Nama Akun</th>
                  <th width="5%">:</th>
                  <th width="80%">'.$kodeakun.' - '.$namaakun.'</th>
                </tr> 
              </thead>
            </table>';



$table .= '<br><br><table border="0" cellpadding="5">';
$table .= '
      <thead>
        <tr style="font-size:12px; font-weight:bold;">
          <th width="5%" style="text-align:center;" class="add-border-top add-border-bottom">NO</th>
          <th width="15%" style="text-align:center;" class="add-border-top add-border-bottom">TANGGAL</th>
          <th width="35%" style="text-align:left;" class="add-border-top add-border-bottom">KETERANGAN</th>
          <th width="15%" style="text-align:right;" class="add-border-top add-border-bottom">DEBET</th>
          <th width="15%" style="text-align:right;" class="add-border-top add-border-bottom">KREDIT</th>
          <th width="15%" style="text-align:right;" class="add-border-top add-border-bottom">SALDO</th>
        </tr>
      </thead>
      <tbody>';



$saldonormal = get_saldo_normal($kodeakun);
$subtotaldebet = 0;
$subtotalkredit = 0;
$saldo = 0;


if ($saldonormal=='D') {
      $saldo += $rowjurnal_lalu->debet - $rowjurnal_lalu->kredit;
    }else{
      $saldo += $rowjurnal_lalu->kredit - $rowjurnal_lalu->debet;
    }
$subtotaldebet = $rowjurnal_lalu->debet;
$subtotalkredit = $rowjurnal_lalu->kredit;

$table .= '
          <tr style="font-size:12px;">
            <td width="5%" style="text-align:center;" class="add-border-top"></td>
            <td width="15%" style="text-align:center;" class="add-border-top"></td>
            <td width="35%" style="text-align:left;" class="add-border-top">Saldo Awal Tgl '.tglindonesia($tglawal).'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($rowjurnal_lalu->debet).'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($rowjurnal_lalu->kredit).'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($saldo).'</td>
          </tr>
    ';


$no = 1;

if ($rsjurnal->num_rows()>0) {
  foreach ($rsjurnal->result() as $rowjurnal) {
    

    if ($saldonormal=='D') {
      $saldo += $rowjurnal->debet - $rowjurnal->kredit;
    }else{
      $saldo += $rowjurnal->kredit - $rowjurnal->debet;
    }

    $table .= '
          <tr style="font-size:12px;">
            <td width="5%" style="text-align:center;" class="add-border-top">'.$no++.'</td>
            <td width="15%" style="text-align:center;" class="add-border-top">'.tglindonesia($rowjurnal->tgljurnal).'</td>
            <td width="35%" style="text-align:left;" class="add-border-top">'.$rowjurnal->deskripsi.'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($rowjurnal->debet).'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($rowjurnal->kredit).'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($saldo).'</td>
          </tr>
    ';

    $subtotaldebet += $rowjurnal->debet;
    $subtotalkredit += $rowjurnal->kredit;

  }
}else{
    $table .= '
            <tr style="font-size:12px;">
              <td width="100%" style="text-align:center;" class="add-border-top add-border-bottom" colspan="6">Data tidak ditemukan..</td>
            </tr>
      ';
}


if ($subtotaldebet>0 || $subtotalkredit>0) {
          
    $table .= '
      <tr style="font-size:12px; font-weight: bold;">
        <td width="55%" style="text-align:right;" class="add-border-top add-border-bottom" colspan="3">TOTAL</td>
        <td width="15%" style="text-align:right;" class="add-border-top add-border-bottom">'.format_rupiah($subtotaldebet).'</td>
        <td width="15%" style="text-align:right;" class="add-border-top add-border-bottom">'.format_rupiah($subtotalkredit).'</td>
        <td width="15%" style="text-align:right;" class="add-border-top add-border-bottom">'.format_rupiah($saldo).'</td>
      </tr>
    ';
}

$table .= ' </tbody>
      </table>';


echo $table;

?>


</body>
</html>