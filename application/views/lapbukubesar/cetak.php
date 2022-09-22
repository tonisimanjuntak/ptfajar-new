<?php

class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {

        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $this->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set margins
        //$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default header data
        $cop = '
    <div></div>
      <table border="0">
          <tr>

            <td width="100%">
            <div style="text-align:left; font-size:20px; font-weight:bold; padding-top:10px;">' . $namaskpd . '</div>

            <i style="text-align:left; font-weight:bold; font-size:14px;">Cabang Pontianak </i>
              </td>

          </tr>
      </table>
      <hr>
      ';

        // $this->writeHTML($cop, true, false, false, false, '');
        // // set margins
        // $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set default header data

    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->AddPage();


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


$title = '
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
$pdf->SetFont('times', '', 17);
$pdf->writeHTML($title, true, false, false, false, '');
$pdf->SetTopMargin(1);



$table = '

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

$pdf->SetTopMargin(35);
$pdf->SetFont('times', '', 10);
$pdf->writeHTML($table, true, false, false, false, '');

$tglcetak = date('d-m-Y');

$pdf->Output();
