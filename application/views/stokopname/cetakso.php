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

$pdf->SetTopMargin(0);


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
                    <span style="">LAPORAN STOK OPNAME</span><br>
                    <span style="font-size: 14px;">PERIODE : '.tglindonesia($rowstokopname->tglstokopname).'</span>
                </td>                
            </tr>
        </tbody>
    </table>
';
$pdf->SetFont('times', '', 17);
$pdf->writeHTML($title, true, false, false, false, '');
$pdf->SetTopMargin(1);

$table = '';
$table  .= '<br>
            <table border="0" width="100%" cellpadding="5">
                <thead>
                    <tr>
                        <th style="width: 20%;">ID STOKOPNAME</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 75%;">'.$rowstokopname->idstokopname.'</th>
                    </tr>
                    <tr>
                        <th style="width: 20%;">NAMA PEGAWAI</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 75%;">'.$rowstokopname->namapengguna.'</th>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Deskripsi</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 75%;">'.$rowstokopname->deskripsi.'</th>
                    </tr>
                </thead>
            </table>

';



$table  .= '<br><table border="1" width="100%" cellpadding="5">';
$table .= ' 
            <thead>
                <tr style="background-color:#ccc;">
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="5%">NO</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="50%">NAMA BARANG</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="15%">STOK SISTEM</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="15%">STOK SEKARANG</th>
                    <th style="font-size:12px; font-weight:bold; text-align:center;" width="15%">SELISIH</th>
                </tr>
            </thead>
            <tbody>';


$no=1;
if ($rsstokopnamedetail->num_rows() > 0) {
    
    foreach ($rsstokopnamedetail->result() as $row) {


        $table .='          
                    <tr>
                        <td style="font-size:11px; text-align:center;" width="5%">'.$no++.'</td>
                        <td style="font-size:11px; text-align:center;" width="50%">'.$row->namaakun.'</td>
                        <td style="font-size:11px; text-align:left;" width="15%">'.number_format($row->jumlahpersediaansistem).'</td>
                        <td style="font-size:11px; text-align:left;" width="15%">'.number_format($row->jumlahpersediaaninput).'</td>
                        <td style="font-size:11px; text-align:left;" width="15%">'.number_format($row->selisih).'</td>
                    </tr>
                    ';

    }

}else{

    $table .='          
                <tr>
                    <th style="font-size:11px; text-align:center;" width="100%" colspan="5">Data Tidak Ada . . .</th>                   
                </tr>';
}
            


$table .= ' </tbody>
            </table>';



$pdf->SetTopMargin(35);
$pdf->SetFont('times', '', 9);
$pdf->writeHTML($table, true, false, false, false, '');

$tglcetak = date('d-m-Y');



$pdf->Output();
?>
