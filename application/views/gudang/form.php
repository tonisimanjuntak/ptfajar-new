<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Gudang</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('gudang')) ?>">Gudang</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('gudang/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul"></h5>
              </div>
              <div class="card-body">


                  <input type="hidden" name="idgudang" id="idgudang">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Gudang</label>
                    <div class="col-md-9">
                      <input type="text" name="namagudang" id="namagudang" class="form-control" placeholder="Masukkan nama gudang">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Alamat Gudang</label>
                    <div class="col-md-9">
                      <textarea name="alamatgudang" id="alamatgudang" class="form-control" rows="3" placeholder="Masukkan alamat"></textarea>
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">No. Telp</label>
                    <div class="col-md-9">
                      <input type="text" name="notelpgudang" id="notelpgudang" class="form-control" placeholder="Masukkan nomor telpon gudang">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Email Gudang</label>
                    <div class="col-md-9">
                      <input type="text" name="emailgudang" id="emailgudang" class="form-control" placeholder="Masukkan email gudang">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Status Aktif</label>
                    <div class="col-md-9">
                      <select name="statusaktif" id="statusaktif" class="form-control">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                      </select>
                    </div>
                  </div>  
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('gudang')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idgudang = "<?php echo($idgudang) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idgudang != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("gudang/get_edit_data") ?>', 
              data        : {idgudang: idgudang}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idgudang").val(result.idgudang);
            $("#namagudang").val(result.namagudang);
            $("#alamatgudang").val(result.alamatgudang);
            $("#notelpgudang").val(result.notelpgudang);
            $("#emailgudang").val(result.emailgudang);
            $("#statusaktif").val(result.statusaktif);
          }); 


          $("#lbljudul").html("Edit Data Gudang");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Gudang");
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
        namagudang: {
          validators:{
            notEmpty: {
                message: "nama gudang tidak boleh kosong"
            },
          }
        },
        alamatgudang: {
          validators:{
            notEmpty: {
                message: "alamat gudang tidak boleh kosong"
            },
          }
        },
        notelpgudang: {
          validators:{
            notEmpty: {
                message: "no telp gudang tidak boleh kosong"
            },
          }
        },
        emailgudang: {
          validators:{
            notEmpty: {
                message: "email gudang tidak boleh kosong"
            },
          }
        },
        statusaktif: {
          validators:{
            notEmpty: {
                message: "status aktif tidak boleh kosong"
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
