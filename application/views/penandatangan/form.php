<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Penandatangan</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('Penandatangan')) ?>">Penandatangan</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('Penandatangan/simpan')) ?>" method="post" id="form">                      
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

                  <input type="hidden" name="idpenandatangan" id="idpenandatangan">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">namapenandatangan</label>
                    <div class="col-md-9">
                      <input type="text" name="namapenandatangan" id="namapenandatangan" class="form-control" placeholder="Masukkan namapenandatangan">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">nip</label>
                    <div class="col-md-9">
                      <input type="text" name="nip" id="nip" class="form-control" placeholder="Masukkan nip">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">jabatan</label>
                    <div class="col-md-9">
                      <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Masukkan jabatan">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">statusaktif</label>
                    <div class="col-md-9">
                      <input type="text" name="statusaktif" id="statusaktif" class="form-control" placeholder="Masukkan statusaktif">
                    </div>
                  </div>
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('Penandatangan')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idpenandatangan = "<?php echo($idpenandatangan) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idpenandatangan != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Penandatangan/get_edit_data") ?>', 
              data        : {idpenandatangan: idpenandatangan}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idpenandatangan").val(result.idpenandatangan);
            $("#namapenandatangan").val(result.namapenandatangan);
            $("#nip").val(result.nip);
            $("#jabatan").val(result.jabatan);
            $("#statusaktif").val(result.statusaktif);
          }); 


          $("#lbljudul").html("Edit Data Penandatangan");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Penandatangan");
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
        namapenandatangan: {
          validators:{
            notEmpty: {
                message: "namapenandatangan tidak boleh kosong"
            },
          }
        },
        nip: {
          validators:{
            notEmpty: {
                message: "nip tidak boleh kosong"
            },
          }
        },
        jabatan: {
          validators:{
            notEmpty: {
                message: "jabatan tidak boleh kosong"
            },
          }
        },
        statusaktif: {
          validators:{
            notEmpty: {
                message: "statusaktif tidak boleh kosong"
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
