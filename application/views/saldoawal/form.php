<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Saldo Awal</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('saldoawal')) ?>">Saldo Awal</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('saldoawal/simpan')) ?>" method="post" id="form">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-header">
                <h5 class="card-title" id="lbljudul"></h5>
              </div>
              <div class="card-body">
                  <input type="hidden" name="idsaldoawal" id="idsaldoawal">                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Tahun Periode</label>
                    <div class="col-md-2">
                      <input type="number" name="tahunanggaran" id="tahunanggaran" class="form-control" value="<?php echo date('Y') ?>">
                    </div>
                  </div>                      
                  <div class="form-group row required">
                    <label for="" class="col-md-3 col-form-label">Jenis Akun</label>
                    <div class="col-md-9">
                      <select name="jenisakun" id="jenisakun" class="form-control select2">
                        <option value="">Pilih jenis akun...</option>
                        <?php  
                          $rsjenis = $this->db->query("select * from akun where `level`=3");
                          if ($rsjenis->num_rows()>0) {
                            foreach ($rsjenis->result() as $row) {
                              echo '
                                <option value="'.$row->kodeakun.'">'.$row->kodeakun.' - '.$row->namaakun.'</option>
                              ';
                            }
                          }
                        ?>
                      </select>
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
                      <h3 class="text-center">SALDO AWAL AKUN LEVEL 4</h3>
                    </div>
                    <div class="col-12">
                      <div class="table-responsive">
                        <table class="table" id="tablebarang">
                          <thead>
                            <tr>
                              <th style="text-align: center; width: 5%;">No</th>
                              <th style="text-align: left;">Kode Akun</th>
                              <th style="text-align: left;">Nama Akun</th>
                              <th style="text-align: center; width: 15%;">Debet</th>
                              <th style="text-align: center; width: 15%;">Kredit</th>
                            </tr>
                          </thead>
                          <tbody id="tbodyAkun">
                              
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('saldoawal')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
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
  
  var idsaldoawal = "<?php echo($idsaldoawal) ?>";
  var ledit = false;

  $(document).ready(function() {

    $('.select2').select2();

    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idsaldoawal != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("saldoawal/get_edit_data") ?>', 
              data        : {idsaldoawal: idsaldoawal}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            ledit = true;
            $("#idsaldoawal").val(result.idsaldoawal);
            $("#tahunanggaran").val(result.tahunanggaran);
            $("#jenisakun").val(result.jenisakun).trigger('change');
            $("#deskripsi").val(result.deskripsi);
            $("#tahunanggaran").attr('disabled', true);
            $("#jenisakun").attr('disabled', true);
          }); 

          $("#lbljudul").html("Edit Data Saldo Awal");
          $("#lblactive").html("Edit");

    }else{
          $("#lbljudul").html("Tambah Data Saldo Awal");
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
        tahunanggaran: {
          validators:{
            notEmpty: {
                message: "tahun tidak boleh kosong"
            },
          }
        },
        jenisakun: {
          validators:{
            notEmpty: {
                message: "jenis akun tidak boleh kosong"
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
  }); //end (document).ready
  

  $('#jenisakun').change(function() {
    var jenisakun = $(this).val();
    var tahunanggaran = $('#tahunanggaran').val();

    if (tahunanggaran=='' && $jenisakun=='') {
      return false;
    }

    $.ajax({
      url: '<?php echo site_url('saldoawal/get_akun_saldoawal') ?>',
      type: 'get',
      dataType: 'json',
      data: {'jenisakun': jenisakun, 'tahunanggaran' : tahunanggaran},
    })
    .done(function(resultget_akun_saldoawal) {
      // console.log(resultget_akun_saldoawal);
      $('#tbodyAkun').empty();
      nLength = resultget_akun_saldoawal.length;

      if (resultget_akun_saldoawal.length>0) {
        var no=1;
        $.each(resultget_akun_saldoawal, function(index, val) {

          var debet = 0;
          var kredit = 0;
          if (ledit) {
            debet = val['debet'];
            kredit = val['kredit'];
          }

          var text = `<tr>
                        <td style="text-align: center;">`+ no +`</td>
                        <td><input type="hidden" name="kodeakun[]" value="`+val['kodeakun']+`">`+val['kodeakun']+`</td>
                        <td><input type="hidden" name="namaakun[]" value="`+val['namaakun']+`">`+val['namaakun']+`</td>
                        <td><input type="text" class="form-control angka persediaan-input" name="debet[]" value="`+debet+`"></td>
                        <td><input type="text" class="form-control angka persediaan-input" name="kredit[]" value="`+kredit+`"></td>
                      </tr>`;

          $('#tbodyAkun').append(text);
          if (no == nLength) {
            ledit = false; //penanda ambil data dari edit
          }
          no++;
        });
      }
    })
    .fail(function() {
      console.log("error");
    });    
  });

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
