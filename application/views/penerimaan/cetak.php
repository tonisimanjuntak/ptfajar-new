<?php

class MYPDF extends TCPDF {
 
    //Page header
    public function Header() {
    
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


        // set margins
        //$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetMargins(PDF_MARGIN_LEFT, 3, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default header data


    // $cop = '
    //  <div></div>
    //      <table border="0">
    //          <tr>
                   
    //              <td width="100%">
    //                  <div style="text-align:center; font-size:24px; font-weight:bold; padding-top:10px;">PT. Swara Rodja Pontianak</div>                 
    //              </td>
                    
    //          </tr>
    //      </table>
            
    //      ';
 
    //  $this->writeHTML($cop, true, false, false, false, '');
    //  // set margins
    //  //$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //  $this->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
    //  $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        // set default header data

                    
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 5, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

        
// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

$pdf->AddPage();

$pdf->SetTopMargin(10);

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
    <table style="" border="0" cellpadding="2">
        <tbody>
            <tr>
                <td style="width:10%; text-align:left;">
                    <img src="'.$logoperusahaan.'" style="height:60px; width:auto; display:block; margin:0 auto;">
                </td>
                <td style="width:90%; text-align:left;">
                    <span style="">'.$rowpengaturan->namaperusahaan.'</span><br>
                    <span style="font-size: 14px;">'.$rowpengaturan->alamatperusahaan.'</span>
                </td>                
            </tr>
        </tbody>
    </table>
';
$pdf->SetFont('times', '', 17);
$pdf->writeHTML($title, true, false, false, false, '');
$pdf->SetTopMargin(1);

$table = '';

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

$table  .= '<table border="0" width="100%" cellpadding="5">
                <thead>
                    <tr>
                        <th style="width: 15%;">No Penerimaan</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 50%">'.$rowpenerimaan->idpenerimaan.'</th>
                        <th style="width: 30%; font-weight: bold; font-size: 18px;">'.$namagudang.'</th>
                    </tr>
                    <tr>
                        <th style="width: 15%;">Tgl Penerimaan</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 50%">'.tglindonesia($rowpenerimaan->tglpenerimaan).'</th>
                        <th style="width: 30%;">'.$alamatgudang.'</th>
                    </tr>
                    <tr>
                        <th style="width: 15%;">Deskripsi</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 50%">'.$rowpenerimaan->deskripsi.'</th>
                        <th style="width: 30%;">'.$notelpgudang.'</th>
                    </tr>
                </thead>
            </table>';

$table .= '<H1 style="text-align: center;">BUKTI PENERIMAAN BARANG</H1>';

$table  .= '<br><br><table border="1" width="100%" cellpadding="5">';
$table .= ' 
            <thead>
                <tr style="font-size:12px; font-weight:bold;">
                    <th style="text-align:center;" width="5%">No</th>
                    <th style="text-align:left;" width="30%">Keterangan Barang</th>
                    <th style="text-align:center;" width="10%">Merk</th>
                    <th style="text-align:center;" width="5%">Qty</th>
                    <th style="text-align:right;" width="15%">Harga Satuan</th>
                    <th style="text-align:right;" width="15%">Jumlah</th>
                    <th style="text-align:center;" width="10%">Estate</th>
                    <th style="text-align:center;" width="10%">No PP</th>
                </tr>
            </thead>
            <tbody>';

$total = 0;
$spasi = str_repeat('&nbsp;', 10);
$no=1;

if ($rsdetail->num_rows() > 0) {
    
    foreach ($rsdetail->result() as $row) {

        $table .= '
                <tr style="font-size:11px;">
                    <td style="text-align:center;" width="5%">'.$no++.'</td>
                    <td style="text-align:left;" width="30%">'.$row->namaakun.'</td>
                    <td style="text-align:center;" width="10%"></td>
                    <td style="text-align:center;" width="5%">'.number_format($row->jumlahbarang).'</td>
                    <td style="text-align:right;" width="15%">'.format_rupiah($row->hargabeli).'</td>
                    <td style="text-align:right;" width="15%">'.format_rupiah($row->jumlahbarang*$row->hargabeli).'</td>
                    <td style="text-align:center;" width="10%"></td>
                    <td style="text-align:center;" width="10%"></td>
                </tr>
        ';
        

        $total += $row->totalharga;
    }

}else{

    $table .='          
                <tr style="font-size:11px;">
                    <td style="text-align:center;" width="100%" colspan="8">Data Tidak Ada . . .</td>                   
                </tr>';
}
            


$table .= ' </tbody>
            </table>';



$table  .= '<br><br><table border="0" width="100%" cellpadding="5">
                <tbody>
                    <tr style="font-size: 11px;">
                        <td style="width: 30%; font-size: 10px;">Lembar 1 : Keuangan</td>
                        <td style="width: 15%; text-align:center;">Disetujui Oleh</td>
                        <td style="width: 15%; text-align:center;">Diperiksa Oleh</td>
                        <td style="width: 15%; text-align:center;">Dibuat Oleh</td>
                        <td style="width: 10%; font-weight: bold;">Jumlah</td>
                        <td style="width: 4%; font-weight: bold;">Rp</td>
                        <td style="width: 11%; font-weight: bold; text-align:right;">'.format_rupiah($total).'</td>                        
                    </tr>
                    <tr style="font-size: 11px;">
                        <td style="width: 30%; font-size: 10px;">Lembar 2 : Accounting</td>
                        <td style="width: 15%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;"></td>
                        <td style="width: 10%; font-weight: bold;">Discount</td>
                        <td style="width: 4%; font-weight: bold;">Rp</td>
                        <td style="width: 11%; font-weight: bold; text-align:right;">0</td>                        
                    </tr>
                    <tr style="font-size: 11px;">
                        <td style="width: 30%; font-size: 10px;">Lembar 3 : Arsip</td>
                        <td style="width: 15%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;"></td>
                        <td style="width: 10%; font-weight: bold;">PPN 10%</td>
                        <td style="width: 4%; font-weight: bold;">Rp</td>
                        <td style="width: 11%; font-weight: bold; text-align:right;">0</td>                        
                    </tr>
                    <tr style="font-size: 11px;">
                        <td style="width: 30%; font-size: 10px;"></td>
                        <td style="width: 15%; text-align:center;" class="add-border-top">Purchasing <br>Manager</td>
                        <td style="width: 15%; text-align:center;" class="add-border-top">Purchasing <br>Distributor</td>
                        <td style="width: 15%; text-align:center;" class="add-border-top">Purchasing <br>Staff</td>
                        <td style="width: 10%; font-weight: bold;">Total</td>
                        <td style="width: 4%; font-weight: bold;">Rp</td>
                        <td style="width: 11%; font-weight: bold; text-align:right;" class="add-border-top">'.format_rupiah($total).'</td>                        
                    </tr>
                </tbody>
            </table>';


$pdf->SetTopMargin(35);
$pdf->SetFont('times', '', 9);
$pdf->writeHTML($table, true, false, false, false, '');

$tglcetak = date('d-m-Y');



$pdf->Output();
?>
