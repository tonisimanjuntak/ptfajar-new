<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Stokopname</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('stokopname')) ?>">Stokopname</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('stokopname/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul"></h5>
              </div>
              <div class="card-body">
                  <input type="hidden" name="idstokopname" id="idstokopname">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Tanggal</label>
                    <div class="col-md-3">
                      <input type="date" name="tglstokopname" id="tglstokopname" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Deskripsi</label>
                    <div class="col-md-9">
                      <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi"></textarea>
                    </div>
                  </div>                 

                  <div class="row">
                    <div class="col-12 mt-3">
                      <h3 class="text-center">DETAIL BARANG STOKOPNAME</h3>
                    </div>
                    <div class="col-12">
                      <div class="table-responsive">
                        <table class="table" id="tablebarang">
                          <thead>
                            <tr>
                              <th style="text-align: center; width: 5%;">No</th>
                              <th style="text-align: left;">Nama Barang</th>
                              <th style="text-align: center; width: 15%;">Stok Sistem</th>
                              <th style="text-align: center; width: 15%;">Stok Sekarang</th>
                              <th style="text-align: center; width: 15%;">Selisih</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php  
                              if ($rsbarang->num_rows()>0) {
                                $no = 1;
                                foreach ($rsbarang->result() as $row) {
                                  echo '
                                      <tr>
                                        <td style="text-align: center;">'.$no++.'</td>
                                        <td><input type="hidden" name="kodeakun[]" value="'.$row->kodeakun.'">'.$row->namaakun.'</td>
                                        <td class="" style="text-align: center;"><input type="hidden" name="jumlahpersediaan[]" value="'.$row->jumlahpersediaan.'">'.$row->jumlahpersediaan.'</td>
                                        <td><input type="text" class="form-control angka persediaan-input" name="jumlahpersediaaninput[]" data-jumlahpersediaan="'.$row->jumlahpersediaan.'" value="'.$row->jumlahpersediaan.'"></td>
                                        <td style="text-align: center;"><input type="hidden" class="form-control angka" name="selisih[]"><span id="">0</span></td>
                                      </tr>
                                  ';
                                }
                              }
                            ?> 
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('stokopname')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idstokopname = "<?php echo($idstokopname) ?>";

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idstokopname != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("stokopname/get_edit_data") ?>', 
              data        : {idstokopname: idstokopname}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            $("#idstokopname").val(result.idstokopname);
            $("#tglstokopname").val(result.tglstokopname);
            $("#deskripsi").val(result.deskripsi);
          }); 


          $("#lbljudul").html("Edit Data Stokopname");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Stokopname");
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
        tglstokopname: {
          validators:{
            notEmpty: {
                message: "Tanggal stokopname tidak boleh kosong"
            },
          }
        },
        deskripsi: {
          validators:{
            notEmpty: {
                message: "deskripsi tidak boleh kosong"
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
  

  $('.persediaan-input').change(function() {
    var jumlahpersediaaninput = $(this).val();
    var jumlahpersediaan = $(this).attr('data-jumlahpersediaan');
    var selisih = parseInt(untitik(jumlahpersediaaninput)) - parseInt(untitik(jumlahpersediaan));
    // console.log(selisih);
    $('#tablebarang tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(5) input').val(selisih);    
    $('#tablebarang tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(5) span').html(selisih);    
  });
</script>

</body>
</html>
