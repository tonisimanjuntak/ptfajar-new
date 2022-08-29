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
  .add-border-left {
    border-left: 1px solid black; 
  }
  .add-border-right {
    border-right: 1px solid black; 
  }
  .add-border-all {
    border-top: 1px solid black; 
    border-bottom: 1px solid black;
    border-left: 1px solid black;
    border-right: 1px solid black;
  }
</style>
';

$table  .= '<table border="0" width="100%" cellpadding="5">
                <thead>
                    <tr>
                        <th style="width: 15%;">Divisi/ Bagian</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 40%">'.$namagudang.'</th>
                        <th style="width: 20%;">Tanggal Pengeluaran</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 15%;">'.$rowpengeluaran->tglpengeluaran.'</th>
                    </tr>
                    <tr>
                        <th style="width: 15%;">Tanggal Permintaan</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 40%"></th>
                        <th style="width: 20%;">Nomor</th>
                        <th style="width: 5%;">:</th>
                        <th style="width: 15%;">'.$rowpengeluaran->idpengeluaran.'</th>
                    </tr>
                </thead>
            </table>';

$table .= '<H1 style="text-align: center;">BON PERMINTAAN DAN PENGELUARAN BARANG</H1>';

$table  .= '<br><br><table border="1" width="100%" cellpadding="5">';
$table .= ' 
            <thead>
                <tr style="font-size:12px; font-weight:bold;">
                    <th style="text-align:center;" width="5%" rowspan="2">No</th>
                    <th style="text-align:center;" width="25%">Kode</th>
                    <th style="text-align:center;" width="20%" rowspan="2">Jenis/ Spesifikasi</th>
                    <th style="text-align:center;" width="10%" rowspan="2">Satuan</th>
                    <th style="text-align:center;" width="20%">Jumlah</th>
                    <th style="text-align:center;" width="20%" rowspan="2">Keterangan</th>
                </tr>
                <tr style="font-size:12px; font-weight:bold;">
                    <th style="text-align:center;" width="10%">Pembe-banan</th>
                    <th style="text-align:center;" width="15%">Barang</th>
                    <th style="text-align:center;" width="10%">Diminta</th>
                    <th style="text-align:center;" width="10%">Dike-luarkan</th>
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
                    <td style="text-align:center;" width="10%"></td>
                    <td style="text-align:center;" width="15%">'.$row->kodeakun.'</td>
                    <td style="text-align:left;" width="20%">'.$row->namaakun.'</td>
                    <td style="text-align:center;" width="10%"></td>
                    <td style="text-align:center;" width="10%"></td>
                    <td style="text-align:center;" width="10%">'.format_rupiah($row->jumlahbarang).'</td>
                    <td style="text-align:left;" width="20%"></td>
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
                        <td style="width: 15%; text-align:center;" class="add-border-all">Disetujui</td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Diperiksa</td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Dibuat</td>
                        <td style="width: 25%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Dikeluarkan</td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Diterima</td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td style="width: 15%; text-align:center;" class="add-border-all"><br><br><br></td>
                        <td style="width: 15%; text-align:center;" class="add-border-all"></td>
                        <td style="width: 15%; text-align:center;" class="add-border-all"></td>
                        <td style="width: 25%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;" class="add-border-all"></td>
                        <td style="width: 15%; text-align:center;" class="add-border-all"></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td style="width: 15%; text-align:center;" class="add-border-all">Manager</td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Asst. Administrasi</td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Asisten</td>
                        <td style="width: 25%; text-align:center;"></td>
                        <td style="width: 15%; text-align:center;" class="add-border-all">Logistik</td>
                        <td style="width: 15%; text-align:center;" class="add-border-all"></td>
                    </tr>
                </tbody>
            </table>';

$table .= '
    <br><br><br>
    <span>Catatan:</span><br>
    <span>- Penanggung jawab penerima barang adalah Asisten</span><br>
    <span>- Dalam penerimaan barang dapat diwakilkan Krani/ Mandor</span><br>
    <span>- Jika terdapat permintaan pembelian lokal, maka format ini</span><br>
    <span>   berfungsi sebagai permohonan PPL</span><br>
';


$pdf->SetTopMargin(35);
$pdf->SetFont('times', '', 9);
$pdf->writeHTML($table, true, false, false, false, '');

$tglcetak = date('d-m-Y');



$pdf->Output();
?>
