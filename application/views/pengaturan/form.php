<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Pengaturan</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Pengaturan</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('pengaturan/simpan')) ?>" method="post" id="form" enctype="multipart/form-data">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">

              <div class="card-body">

                  

                  <div class="row">
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-body">
                            
                            <div class="form-group row text center">
                              <label for="" class="col-md-12 col-form-label">Logo Perusahaan <span style="color: red; font-size: 12px; font-weight: bold;"><i> Max ukuran file 2MB</i></span></label>
                              <div class="col-md-12 mt-3 text-center">
                                <?php  
                                  if (empty($rowpengaturan->logoperusahaan)) {
                                    $logoperusahaan = base_url('images/logo-default.jpg');
                                  }else{
                                    $logoperusahaan = base_url('uploads/pengaturan/'.$rowpengaturan->logoperusahaan);
                                  }
                                ?>
                                <img src="<?php echo $logoperusahaan; ?>" id="output1" class="img-thumbnail" style="width:60%;max-height:60%;">
                                <div class="form-group">
                                    <span class="btn btn-primary btn-file btn-block;" style="width:60%;">
                                      <span class="fileinput-new"><span class="fa fa-camera"></span> Upload Logo</span>
                                      <input type="file" name="file" id="file" accept="image/*" onchange="loadFile1(event)">
                                      <input type="hidden" name="file_lama" id="file_lama" class="form-control" value="<?php echo $rowpengaturan->logoperusahaan ?>" />
                                    </span>
                                </div>
                                <script type="text/javascript">
                                    var loadFile1 = function(event) {
                                        var output1 = document.getElementById('output1');
                                        output1.src = URL.createObjectURL(event.target.files[0]);
                                    };
                                </script>

                                
                              </div>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="col-md-8 pt-5">
                      
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Nama Perusahaan</label>
                        <div class="col-md-9">
                          <input type="text" name="namaperusahaan" id="namaperusahaan" class="form-control" placeholder="Masukkan nama perusahaan" value="<?php echo $rowpengaturan->namaperusahaan ?>">
                        </div>
                      </div>                      
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">Alamat Perusahaan</label>
                        <div class="col-md-9">
                          <textarea name="alamatperusahaan" id="alama" class="form-control" rows="3" placeholder="Masukkan alamat perusahaan"><?php echo $rowpengaturan->alamatperusahaan ?></textarea>
                        </div>
                      </div>                      
                      <div class="form-group row required">
                        <label for="" class="col-md-3 col-form-label">No Telepon</label>
                        <div class="col-md-9">
                          <input type="text" name="notelp" id="notelp" class="form-control" placeholder="Masukkan nomor telepon perusahaan" value="<?php echo $rowpengaturan->notelp ?>">
                        </div>
                      </div>


                    </div>
                  </div>     


              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
              </div>
            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
      </form>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>



<script type="text/javascript">
  
  $(document).ready(function() {

    $('.select2').select2();

    //----------------------------------------------------------------- > validasi
    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        namaperusahaan: {
          validators:{
            notEmpty: {
                message: "namaperusahaan tidak boleh kosong"
            },
          }
        },
        alamatperusahaan: {
          validators:{
            notEmpty: {
                message: "alamatperusahaan tidak boleh kosong"
            },
          }
        },
        notelp: {
          validators:{
            notEmpty: {
                message: "notelp tidak boleh kosong"
            },
          }
        },      
      }
    });
  //------------------------------------------------------------------------> END VALIDASI DAN SIMPAN


    $("form").attr('autocomplete', 'off');
    //$("#tanggal").mask("00-00-0000", {placeholder:"hh-bb-tttt"});
    //$("#jumlah").mask("000,000,000,000", {reverse: true});
  }); //end (document).ready
  

</script>

</body>
</html>
