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
                    <span style="">NERACA SALDO</span><br>
                    <span style="font-size: 14px;">PERIODE : '.$periode.'</span>
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


$table .= '<br><br><table border="0" cellpadding="5">';
$table .= '
      <thead>
        <tr style="font-size:12px; font-weight:bold;">
          <th width="20%" style="text-align:center;" class="add-border-top add-border-bottom">Kode Akun</th>
          <th width="50%" style="text-align:left;" class="add-border-top add-border-bottom">Nama Akun</th>
          <th width="15%" style="text-align:right;" class="add-border-top add-border-bottom">DEBET</th>
          <th width="15%" style="text-align:right;" class="add-border-top add-border-bottom">KREDIT</th>
        </tr>
      </thead>
      <tbody>';



$saldonormal = get_saldo_normal($kodeakun);
$subtotaldebet = 0;
$subtotalkredit = 0;
$saldo = 0;
$no = 1;

if ($rsAkun->num_rows()>0) {
  foreach ($rsAkun->result() as $rowakun) {
    

    $saldonormal = get_saldo_normal($rowakun->kodeakun);

    $debet = 0;
    $kredit =0;

    if ($saldonormal=='D') {
      $saldo = $rowakun->debet - $rowakun->kredit;      
      if ($saldo<0) {
        $kredit = abs($saldo);
      }else{
        $debet = $saldo;
      }
    }else{
      $saldo = $rowakun->kredit - $rowakun->debet;
      if ($saldo<0) {
        $debet = abs($saldo);
      }else{
        $kredit = $saldo;
      }
    }

    $table .= '
          <tr style="font-size:12px;">
            <td width="20%" style="text-align:center;" class="add-border-top">'.$rowakun->kodeakun.'</td>
            <td width="50%" style="text-align:left;" class="add-border-top">'.$rowakun->namaakun.'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($debet).'</td>
            <td width="15%" style="text-align:right;" class="add-border-top">'.format_rupiah($kredit).'</td>
          </tr>
    ';

    $subtotaldebet += $debet;
    $subtotalkredit += $kredit;
  }
}else{
    $table .= '
            <tr style="font-size:12px;">
              <td width="100%" style="text-align:center;" class="add-border-top add-border-bottom" colspan="4">Data tidak ditemukan..</td>
            </tr>
      ';
}

$table .= ' </tbody>
      </table>';



echo $table;

?>


</body>
</html>