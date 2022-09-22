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
                <td style="width:25%; text-align:center;">
                    <img src="'.$logoperusahaan.'" style="height:80px; width:auto; display:block; margin:0 auto;">
                </td>
                <td style="width:50%; text-align:center;">
                    <span style="">NERACA SALDO</span><br>
                    <span style="font-size: 14px;">PERIODE : '.$periode.'</span>
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

$pdf->SetTopMargin(35);
$pdf->SetFont('times', '', 10);
$pdf->writeHTML($table, true, false, false, false, '');

$tglcetak = date('d-m-Y');

$pdf->Output();
