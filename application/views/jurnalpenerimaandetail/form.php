<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Jurnalpenerimaandetail</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('Jurnalpenerimaandetail')) ?>">Jurnalpenerimaandetail</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('Jurnalpenerimaandetail/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul"></h5>
              </div>
              <div class="card-body">

                  <div class="col-md-12">
                    <?php 
                      $pesan = $this->session->flashdata("pesan");
                      if (!empty($pesan)) {
                        echo $pesan;
                      }
                    ?>
                  </div> 

                  <input type="hidden" name="" id="">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">idjurnalpenerimaan</label>
                    <div class="col-md-9">
                      <input type="text" name="idjurnalpenerimaan" id="idjurnalpenerimaan" class="form-control" placeholder="Masukkan idjurnalpenerimaan">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">kodeakun</label>
                    <div class="col-md-9">
                      <input type="text" name="kodeakun" id="kodeakun" class="form-control" placeholder="Masukkan kodeakun">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">jumlahbarang</label>
                    <div class="col-md-9">
                      <input type="text" name="jumlahbarang" id="jumlahbarang" class="form-control" placeholder="Masukkan jumlahbarang">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">hargabeli</label>
                    <div class="col-md-9">
                      <input type="text" name="hargabeli" id="hargabeli" class="form-control" placeholder="Masukkan hargabeli">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">hargajual</label>
                    <div class="col-md-9">
                      <input type="text" name="hargajual" id="hargajual" class="form-control" placeholder="Masukkan hargajual">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">totalharga</label>
                    <div class="col-md-9">
                      <input type="text" name="totalharga" id="totalharga" class="form-control" placeholder="Masukkan totalharga">
                    </div>
                  </div>
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('Jurnalpenerimaandetail')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var  = "<?php echo($) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if (  != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Jurnalpenerimaandetail/get_edit_data") ?>', 
              data        : {: }, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#").val(result.);
            $("#idjurnalpenerimaan").val(result.idjurnalpenerimaan);
            $("#kodeakun").val(result.kodeakun);
            $("#jumlahbarang").val(result.jumlahbarang);
            $("#hargabeli").val(result.hargabeli);
            $("#hargajual").val(result.hargajual);
            $("#totalharga").val(result.totalharga);
          }); 


          $("#lbljudul").html("Edit Data Jurnalpenerimaandetail");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Jurnalpenerimaandetail");
          $("#lblactive").html("Tambah");
    }     

    //----------------------------------------------------------------- > validasi
    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        idjurnalpenerimaan: {
          validators:{
            notEmpty: {
                message: "idjurnalpenerimaan tidak boleh kosong"
            },
          }
        },
        kodeakun: {
          validators:{
            notEmpty: {
                message: "kodeakun tidak boleh kosong"
            },
          }
        },
        jumlahbarang: {
          validators:{
            notEmpty: {
                message: "jumlahbarang tidak boleh kosong"
            },
          }
        },
        hargabeli: {
          validators:{
            notEmpty: {
                message: "hargabeli tidak boleh kosong"
            },
          }
        },
        hargajual: {
          validators:{
            notEmpty: {
                message: "hargajual tidak boleh kosong"
            },
          }
        },
        totalharga: {
          validators:{
            notEmpty: {
                message: "totalharga tidak boleh kosong"
            },
          }
        },      }
    });
  //------------------------------------------------------------------------> END VALIDASI DAN SIMPAN


    $("form").attr('autocomplete', 'off');
    //$("#tanggal").mask("00-00-0000", {placeholder:"hh-bb-tttt"});
    //$("#jumlah").mask("000,000,000,000", {reverse: true});
  }); //end (document).ready
  

</script>

</body>
</html>
