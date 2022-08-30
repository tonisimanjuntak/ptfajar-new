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
        <li class="breadcrumb-item"><a href="<?php echo(site_url('akun')) ?>">Akun</a></li>
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

                
                <form action="<?php echo(site_url('akun/importdata')) ?>" method="post" id="form" enctype="multipart/form-data">                      
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
                      <a href="<?php echo(base_url('uploads/templateexcel/akun.xlsx')) ?>" class="btn btn-sm btn-success float-right mr-2" download><i class="fa fa-file-excel" target="_blank"></i> Download Format Excel</a>
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
                    <form method="post" action="<?php echo site_url('akun/simpan_import') ?>">
                      
                      <h3>Preview Data</h3>
                      <div class="alert alert-danger" role="alert" style="display: none;" id="kosong">
                        Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum diisi (Yang berwarna Merah wajib diisi).
                      </div>
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th style='width: 5%; text-align: center;'>Kode Akun</th>
                              <th>Nama Akun</th>
                              <th>Parent Akun</th>
                            </tr>                            
                          </thead>
                          <tbody>
                            


                          <?php
                              $numrow = 1;
                              $kosong = 0;
                              
                              foreach($sheet as $row){ 
                                $kodeakun = $row[0]; 
                                $namaakun = $row[1];
                                $parentakun = $row[2];  

                                if(empty($kodeakun))
                                  continue; 
                                
                                if($numrow > 1){ 
                                  $kodeakun_td = ( ! empty($kodeakun))? '' : 'class="bg-danger"'; 
                                  $namaakun_td = ( ! empty($namaakun))? '' : 'class="bg-danger"'; 
                                  $parentakun_td = '';
                                  if (strlen($kodeakun)>1) {
                                    $parentakun_td = ( ! empty($parentakun))? '' : 'class="bg-danger"';
                                  }

                                  if(empty($kodeakun) or empty($namaakun) or (empty($parentakun) and strlen($kodeakun)>1) ){
                                    $kosong++;  // Tambah 1 variabel $kosong
                                  }
                                  
                                  echo "<tr>
                                            <td ".$kodeakun_td.">".$kodeakun."</td>
                                            <td ".$namaakun_td.">".$namaakun."</td>
                                            <td ".$parentakun_td.">".$parentakun."</td>
                                        </tr>";
                                }
                                
                                $numrow++; 
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
                        <a href="<?php echo(site_url('akun')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
