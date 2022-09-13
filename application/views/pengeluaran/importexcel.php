<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>
<style>
  .sub-test {
    margin-top: -10px;
    margin-bottom: 20px;
    color: #5D62C1;
  }
</style>
<script src="<?php echo(base_url()) ?>assets/adminlte/plugins/jquery/jquery.min.js"></script>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Akun</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('pengeluaran')) ?>">Akun</a></li>
        <li class="breadcrumb-item active" id="lblactive">Import Data Excel</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-body">

                
                <form action="<?php echo(site_url('pengeluaran/importdata')) ?>" method="post" id="form" enctype="multipart/form-data">                      
                  <div class="row">
                    <div class="col-md-6">
                      <div class="row">
                        
                        <div class="col-12">
                          <h5>Pilih File Excel Yang Akan di Upload</h5>
                        </div>
                        <div class="col-12 sub-test">
                          <span class="">Penting! Format excel yang akan diupload harus sesuai dengan kebutuhan sistem!</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <a href="<?php echo(base_url('uploads/templateexcel/pengeluaran.xlsx')) ?>" class="btn btn-sm btn-success float-right mr-2" download><i class="fa fa-file-excel" target="_blank"></i> Download Format Excel</a>
                    </div>

                    <div class="col-12">
                      <input type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="col-12 mt-3">
                      <input type="submit" name="preview" value="Tampilkan Data" class="btn btn-sm btn-info">
                    </div>
                  </div>
                </form>


                <?php
                  if(isset($_POST['preview'])){ 
                    if(isset($upload_error)){ 
                      echo "<div style='color: red;'>".$upload_error."</div>"; 
                      die; 
                    }
                ?>
                    
                    <hr class="mt-5">
                    <form method="post" action="<?php echo site_url('pengeluaran/simpan_import') ?>">
                      
                      <h3>Preview Data</h3>
                      <div class="alert alert-danger" role="alert" style="display: none;" id="kosong">
                        Semua data belum diisi dengan benar, Ada <span id='jumlah_kosong'></span> data yang belum diisi dengan benar (Yang berwarna Merah wajib diisi).
                      </div>
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th style='width: 5%; text-align: center;'>No</th>
                              <th>Tgl Pengeluaran</th>
                              <th>Deskripsi</th>
                              <th>idgudang</th>
                              <th>JenisPengeluaran</th>
                              <th>KodeAkun</th>
                              <th>JumahBarang</th>
                              <th>HargaBeli</th>
                              <th>TotalHarga</th>
                            </tr>                            
                          </thead>
                          <tbody>
                            


                          <?php
                              $numrow = 1;
                              $kosong = 0;
                              
                              $jenispengeluaranAllowed = array('Penjualan', 'Barang Keluar');
                              $tglpengeluaran_old = '';
                              $jenispengeluaran_old = '';
                              $idgudang_old='';
                              $idpengeluaran_old='';

                              $rsGudang = $this->db->query("select idgudang from gudang");
                              $arrGudang = array();
                              if ($rsGudang->num_rows()>0) {
                                foreach ($rsGudang->result() as $row) {
                                  $arrGudang[]=$row->idgudang;
                                }
                              }

                              $rsAkunBarang = $this->App->get_akun_barang();
                              $arrAkunBarang = array();
                              if ($rsAkunBarang->num_rows()>0) {
                                foreach ($rsAkunBarang->result() as $row) {
                                  $arrAkunBarang[] = $row->kodeakun;
                                }
                              }

                              $rsTemp = $this->db->query("select * from pengeluaran_temp");
                              $no = 1;
                              if ($rsTemp->num_rows()>0) {
                                foreach ($rsTemp->result() as $rowheader) {
                                  
                                  $idpengeluaran = $rowheader->idpengeluaran;
                                  $tglpengeluaran = $rowheader->tglpengeluaran;
                                  $deskripsi = $rowheader->deskripsi;  
                                  $idgudang = $rowheader->idgudang;  
                                  $jenispengeluaran = $rowheader->jenispengeluaran;  
                                  $rsTempDetail = $this->db->query("select * from pengeluaran_tempdetail where idpengeluaran=".$rowheader->idpengeluaran);
                                  if ($rsTempDetail->num_rows()>0) {
                                    foreach ($rsTempDetail->result() as $rowdetail) {
                                      
                                      $kodeakun = $rowdetail->kodeakun;  
                                      $jumlahbarang = $rowdetail->jumlahbarang;  
                                      $hargabeli = $rowdetail->hargabeli;  

                                      if(empty($kodeakun))
                                        continue; 
                                      

                                        $tglpengeluaran_td = '';
                                        $jenispengeluaran_td = '';
                                        $kodeakun_td = '';
                                        $jumlahbarang_td = '';
                                        $hargabeli_td = '';
                                        $idgudang_td = '';

                                        //header boleh kosong

                                        if (empty($tglpengeluaran) && empty($tglpengeluaran_old)) {
                                          $tglpengeluaran_td = 'class="bg-danger"';   
                                          $kosong++;
                                        }

                                        if (empty($idgudang) && empty($idgudang_old)) {
                                          $idgudang_td = 'class="bg-danger"';   
                                          $kosong++;
                                        }else{
                                          if (!in_array($idgudang, $arrGudang)) {
                                            $idgudang_td = 'class="bg-danger"';   
                                            $kosong++;
                                          }
                                        }

                                        if (empty($jenispengeluaran) && empty($jenispengeluaran_old)) {
                                          $jenispengeluaran_td = 'class="bg-danger"';   
                                          $kosong++;
                                        }

                                        if (!in_array($jenispengeluaran, $jenispengeluaranAllowed)) {
                                          $jenispengeluaran_td = 'class="bg-danger"';   
                                          $kosong++; 
                                        }

                                        if (empty($kodeakun)) {
                                          $kodeakun_td = 'class="bg-danger"';   
                                          $kosong++; 
                                        }else{
                                          $rsakun= $this->db->query("select * from akun where kodeakun='$kodeakun'");
                                          if ($rsakun->num_rows()==0) {
                                            $kodeakun_td = 'class="bg-danger"';           
                                            $kosong++; 
                                          }
                                        }

                                        if ($jumlahbarang=='' || $jumlahbarang==0) {
                                          $jumlahbarang_td = 'class="bg-danger"';   
                                          $kosong++;
                                        }

                                        if ($hargabeli=='' || $hargabeli==0) {
                                          $hargabeli_td = 'class="bg-danger"';   
                                          $kosong++;
                                        }
                                        
                                        
                                        if ($idpengeluaran!= $idpengeluaran_old) {
                                          
                                          echo "
                                          <tr>
                                            <td style='text-align: center;'>".$no++."</td>
                                            <td ".$tglpengeluaran_td.">".$tglpengeluaran."</td>
                                            <td>".$deskripsi."</td>
                                            <td ".$idgudang_td.">".$idgudang."</td>
                                            <td ".$jenispengeluaran_td.">".$jenispengeluaran."</td>
                                            <td ".$kodeakun_td.">".$kodeakun."</td>
                                            <td ".$jumlahbarang_td.">".$jumlahbarang."</td>
                                            <td ".$hargabeli_td.">".$hargabeli."</td>
                                            <td>".$jumlahbarang*$hargabeli."</td>
                                          </tr>
                                          ";


                                        }else{
                                          echo "
                                          <tr>
                                            <td style='text-align: center;'></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td ".$kodeakun_td.">".$kodeakun."</td>
                                            <td ".$jumlahbarang_td.">".$jumlahbarang."</td>
                                            <td ".$hargabeli_td.">".$hargabeli."</td>
                                            <td>".$jumlahbarang*$hargabeli."</td>
                                          </tr>
                                          ";
                                        }
                                      
                                      $numrow++; 
                                      $idpengeluaran_old = $idpengeluaran;
                                      $tglpengeluaran_old = $tglpengeluaran;
                                      $jenispengeluaran_old = $jenispengeluaran;
                                      $idgudang_old=$idgudang;
                                    } // end foreach
                                  } //end id numrows()


                                  $idpengeluaran_old = $idpengeluaran;
                                  $tglpengeluaran_old = $tglpengeluaran;
                                  $jenispengeluaran_old = $jenispengeluaran;
                                  $idgudang_old=$idgudang;
                                  $no++;
                                } // end foreach header
                              }

                            ?>
                          </tbody>                        
                      </table>
                    </div>
                    
                    <?php  if($kosong > 0) { ?>  

                        <script>
                          $(document).ready(function(){
                            $("#jumlah_kosong").html('<?php echo $kosong; ?>');                          
                            $("#kosong").show();
                          });
                        </script>

                    <?php }else{ ?>

                        <hr>
                        <button type="submit" name="import" class="btn btn-success float-right"><i class="fa fa-file-import"></i> Import Data</button>
                        <a href="<?php echo(site_url('pengeluaran')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
                    <?php } ?>
                    
                    </form>

                <?php  } ?>
                                        
              </div> <!-- ./card-body -->

            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>



<script type="text/javascript">
  

  $(document).ready(function() {

    $('.select2').select2();

    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        namaakun: {
          validators:{
            notEmpty: {
                message: "namaakun tidak boleh kosong"
            },
          }
        },
      }
    });


    $("form").attr('autocomplete', 'off');
  }); //end (document).ready
  
</script>

</body>
</html>
