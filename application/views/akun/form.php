<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Akun</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('akun')) ?>">Akun</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('akun/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul"></h5>
              </div>
              <div class="card-body">

                  <input type="hidden" name="ltambah" id="ltambah" value="<?php echo $ltambah ?>">
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Parent Akun</label>
                    <div class="col-md-9">
                      <select name="parentakun" id="parentakun" class="form-control select2">
                        <option value="">Pilih parent akun</option>
                        <?php  
                          $rsakun = $this->db->query("select * from akun order by kodeakun");
                          if ($rsakun->num_rows()>0) {
                            foreach ($rsakun->result() as $row) {
                              echo '
                                  <option value="'.$row->kodeakun.'">'.$row->namaakun.'</option>
                              ';
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Kode Akun</label>
                    <div class="col-md-3">
                      <input type="text" name="kodeakun" id="kodeakun" class="form-control" placeholder="Masukkan kode akun">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Nama Akun</label>
                    <div class="col-md-9">
                      <input type="text" name="namaakun" id="namaakun" class="form-control" placeholder="Masukkan namaakun">
                    </div>
                  </div>                      
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('akun')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var kodeakun = "<?php echo($kodeakun) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( kodeakun != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("akun/get_edit_data") ?>', 
              data        : {kodeakun: kodeakun}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#parentakun").val(result.parentakun).trigger('change');
            $('#parentakun').prop('disabled', true);
            $("#kodeakun").val(result.kodeakun);
            $("#kodeakun").prop('readonly', true);
            $("#namaakun").val(result.namaakun);
            console.log(result.parentakun);
          }); 


          $("#lbljudul").html("Edit Data Akun");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Akun");
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
        namaakun: {
          validators:{
            notEmpty: {
                message: "namaakun tidak boleh kosong"
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
  

  $('#parentakun').change(function() {
    var parentakun = $(this).val();
    $('#kodeakun').val(parentakun);
  });
</script>

</body>
</html>
