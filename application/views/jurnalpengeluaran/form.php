<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Jurnalpengeluaran</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('Jurnalpengeluaran')) ?>">Jurnalpengeluaran</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>


  <div class="row" id="toni-content">
    <div class="col-md-12">
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


                  <input type="hidden" name="idjurnalpengeluaran" id="idjurnalpengeluaran">
                  
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">tgljurnalpengeluaran</label>
                    <div class="col-md-10">
                      <input type="text" name="tgljurnalpengeluaran" id="tgljurnalpengeluaran" class="form-control" placeholder="Masukkan tgljurnalpengeluaran">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">deskripsi</label>
                    <div class="col-md-10">
                      <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukkan deskripsi">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">jenispengeluaran</label>
                    <div class="col-md-10">
                      <input type="text" name="jenispengeluaran" id="jenispengeluaran" class="form-control" placeholder="Masukkan jenispengeluaran">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">jenistransaksi</label>
                    <div class="col-md-10">
                      <input type="text" name="jenistransaksi" id="jenistransaksi" class="form-control" placeholder="Masukkan jenistransaksi">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">jumlahpengeluaran</label>
                    <div class="col-md-10">
                      <input type="text" name="jumlahpengeluaran" id="jumlahpengeluaran" class="form-control" placeholder="Masukkan jumlahpengeluaran">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">created_at</label>
                    <div class="col-md-10">
                      <input type="text" name="created_at" id="created_at" class="form-control" placeholder="Masukkan created_at">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">updated_at</label>
                    <div class="col-md-10">
                      <input type="text" name="updated_at" id="updated_at" class="form-control" placeholder="Masukkan updated_at">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">idpengguna</label>
                    <div class="col-md-10">
                      <input type="text" name="idpengguna" id="idpengguna" class="form-control" placeholder="Masukkan idpengguna">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                          <h3 class="text-muted text-center">Detail Jurnalpengeluaran</h3>
                          <hr>

                          
                          <form action="<?php echo(site_url('Jurnalpengeluaran/simpan')) ?>" method="post" id="form">                      
                            <div class="row">

                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="">kodeakun</label>
                                    <select name="kodeakun" id="kodeakun" class="form-control">
                                      <option value="">Pilih kodeakun</option>
                                      <?php
                                        $rs = $this->db->query("select * from akun order by kodeakun");
                                        foreach ($rs->result() as $row) {
                                          echo '<option value="'.$row->kodeakun.'">'.$row->namaakun.'</option>';
                                        }
                                      ?>  
                                    </select>
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">jumlahbarang</label>
                                  <input type="text" name="jumlahbarang" id="jumlahbarang" class="form-control">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">hargabeli</label>
                                  <input type="text" name="hargabeli" id="hargabeli" class="form-control">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">hargajual</label>
                                  <input type="text" name="hargajual" id="hargajual" class="form-control">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">totalharga</label>
                                  <input type="text" name="totalharga" id="totalharga" class="form-control">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <button class="btn btn-primary mt-4" type="submit" id="tambahkan">Tambahkan</button>
                              </div>

                            </div>
                          </form>

                            <hr>

                          <div class="table-responsive">
                            <table id="table" class="display" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 5%; text-align: center;">No</th>
                                        <th style="">idjurnalpengeluaran</th>
                                        <th style="">kodeakun</th>
                                        <th style="">jumlahbarang</th>
                                        <th style="">hargabeli</th>
                                        <th style="">hargajual</th>
                                        <th style="">totalharga</th>
                                        <th style="width: 5%; text-align: center;">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th style="text-align: right; font-weight: bold; font-size: 20px;">TOTAL: </th>
                                  <th style="text-align: right; font-weight: bold; font-size: 20px" colspan="2"></th>
                                </tfoot>   
                            </table>
                          </div>

                      </div>
                    </div>
                    <input type="hidden" id="total">
                  </div>
                  

              </div> <!-- ./card-body -->

              <div class="card-footer">
                <button class="btn btn-info float-right" id="simpan"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo(site_url('Jurnalpengeluaran')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
              </div>
            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->

      

<?php $this->load->view("template/footer") ?>




<script type="text/javascript">
  
  var idjurnalpengeluaran = "<?php echo($idjurnalpengeluaran) ?>";

  $(document).ready(function() {

    $('.select2').select2();
    

    table = $('#table').DataTable({ 
        "select": true,
            "processing": true, 
            "ordering": false,
            "bPaginate": false,      
            "searching": false,  
            "bInfo" : false, 
             "ajax"  : {
                      "url": "<?php echo site_url('Jurnalpengeluaran/datatablesourcedetail')?>",
                      "dataType": "json",
                      "type": "POST",
                      "data": {"idjurnalpengeluaran": '<?php echo($idjurnalpengeluaran) ?>'}
                  },
                "footerCallback": function ( row, data, start, end, display ) {
                                    var api = this.api(), data;
                         
                                    // Hilangkan format number untuk menghitung sum
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                            i.replace(/[\$,.]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };
                         
                                    // Total Semua Halaman
                                    total = api
                                        .column( 6 )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );
                         
                                    // Total Halaman Terkait
                                    pageTotal = api
                                        .column( 6, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );
                                    
                                    jlhkeseluruhan = total;
                                    // Update footer
                                    $( api.column( 6 ).footer() ).html(
                                        'Rp. '+ numberWithCommas(total)                                        
                                    );
                                    $('#total').val( numberWithCommas(total) );
                                },
            "columnDefs": [
            { "targets": [ 1 ], "className": 'dt-body-center', "visible": false},
            { "targets": [ 7 ], "orderable": false, "className": 'dt-body-center'},
            ],
     
        });



    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idjurnalpengeluaran != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Jurnalpengeluaran/get_edit_data") ?>', 
              data        : {idjurnalpengeluaran: idjurnalpengeluaran}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            console.log(result);
            $('#idjurnalpengeluaran').val(result.idjurnalpengeluaran);
            $('#tgljurnalpengeluaran').val(result.tgljurnalpengeluaran);
            $('#deskripsi').val(result.deskripsi);
            $('#jenispengeluaran').val(result.jenispengeluaran);
            $('#jenistransaksi').val(result.jenistransaksi);
            $('#jumlahpengeluaran').val(result.jumlahpengeluaran);
            $('#created_at').val(result.created_at);
            $('#updated_at').val(result.updated_at);
            $('#idpengguna').val(result.idpengguna);
          }); 
          
          $('#lbljudul').html('Edit Data Jurnalpengeluaran');
          $('#lblactive').html('Edit');

    }else{
          $('#lbljudul').html('Tambah Data Jurnalpengeluaran');
          $('#lblactive').html('Tambah');
    }     

    //----------------------------------------------------------------- > validasi
    $('#form').bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        idjurnalpengeluaran: {
          validators:{
            notEmpty: {
                message: "idjurnalpengeluaran tidak boleh kosong"
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
        },
      }
    })
    .on('success.form.bv', function(e) {
      e.preventDefault();
      $('#tambahkan').attr('disabled', false);
      
    var idjurnalpengeluaran           = $('#idjurnalpengeluaran').val();
    var kodeakun           = $('#kodeakun').val();
    var jumlahbarang           = $('#jumlahbarang').val();
    var hargabeli           = $('#hargabeli').val();
    var hargajual           = $('#hargajual').val();
    var totalharga           = $('#totalharga').val();
        if (kodeakun=="") {
          alert("kodeakun tidak boleh kosong!!");
          return false;
        }
        if (jumlahbarang=="") {
          alert("jumlahbarang tidak boleh kosong!!");
          return false;
        }
        if (hargabeli=="") {
          alert("hargabeli tidak boleh kosong!!");
          return false;
        }
        if (hargajual=="") {
          alert("hargajual tidak boleh kosong!!");
          return false;
        }
        if (totalharga=="") {
          alert("totalharga tidak boleh kosong!!");
          return false;
        }
      
      var isicolomn = table.columns(1).data().toArray();
      for (var i = 0; i < isicolomn.length; i++) {
        for (var j = 0; j < isicolomn[i].length; j++) {            
          if (isicolomn[i][j] === kodeakun) {
              alert("kodeakun sudah ada!!");
              return false;
          }
        }
      };

        nomorrow = table.page.info().recordsTotal + 1;
        table.row.add( [
                            nomorrow,
                            $("#idjurnalpengeluaran").val(),
                            $("#kodeakun").val(),
                            $("#jumlahbarang").val(),
                            $("#hargabeli").val(),
                            $("#hargajual").val(),
                            $("#totalharga").val(),
                            '<span class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></span>'
                        ] ).draw( false );
        $("#idjurnalpengeluaran").val("");
        $("#kodeakun").val("");
        $("#jumlahbarang").val("");
        $("#hargabeli").val("");
        $("#hargajual").val("");
        $("#totalharga").val("");
    });
  //------------------------------------------------------------------------> END VALIDASI DAN SIMPAN

    $('#table tbody').on( 'click', 'span', function () {
        table
            .row( $(this).parents('tr') )
            .remove()
            .draw();
    });


    $("form").attr('autocomplete', 'off');
    // $('#tglterima').mask('00-00-0000', {placeholder:"hh-bb-tttt"});
    // $('#hargabelisatuan').mask('000,000,000,000', {reverse: true, placeholder:"000,000,000,000"});
  }); //end (document).ready
  

  
  $('#simpan').click(function(){
    var idjurnalpengeluaran       = $("#idjurnalpengeluaran").val();
    var tgljurnalpengeluaran       = $("#tgljurnalpengeluaran").val();
    var deskripsi       = $("#deskripsi").val();
    var jenispengeluaran       = $("#jenispengeluaran").val();
    var jenistransaksi       = $("#jenistransaksi").val();
    var jumlahpengeluaran       = $("#jumlahpengeluaran").val();
    var created_at       = $("#created_at").val();
    var updated_at       = $("#updated_at").val();
    var idpengguna       = $("#idpengguna").val();
      if (idjurnalpengeluaran=='') {
        alert("idjurnalpengeluaran tidak boleh kosong!!");
        return; 
      }
      if (tgljurnalpengeluaran=='') {
        alert("tgljurnalpengeluaran tidak boleh kosong!!");
        return; 
      }
      if (deskripsi=='') {
        alert("deskripsi tidak boleh kosong!!");
        return; 
      }
      if (jenispengeluaran=='') {
        alert("jenispengeluaran tidak boleh kosong!!");
        return; 
      }
      if (jenistransaksi=='') {
        alert("jenistransaksi tidak boleh kosong!!");
        return; 
      }
      if (jumlahpengeluaran=='') {
        alert("jumlahpengeluaran tidak boleh kosong!!");
        return; 
      }
      if (created_at=='') {
        alert("created_at tidak boleh kosong!!");
        return; 
      }
      if (updated_at=='') {
        alert("updated_at tidak boleh kosong!!");
        return; 
      }
      if (idpengguna=='') {
        alert("idpengguna tidak boleh kosong!!");
        return; 
      }
    if ( ! table.data().count() ) {
          alert("Detail Jurnalpengeluaran belum ada!!");
          return;
      }

      var isidatatable = table.data().toArray();

      var formData = {
              "idjurnalpengeluaran"       : idjurnalpengeluaran,
              "tgljurnalpengeluaran"       : tgljurnalpengeluaran,
              "deskripsi"       : deskripsi,
              "jenispengeluaran"       : jenispengeluaran,
              "jenistransaksi"       : jenistransaksi,
              "jumlahpengeluaran"       : jumlahpengeluaran,
              "created_at"       : created_at,
              "updated_at"       : updated_at,
              "idpengguna"       : idpengguna,
              "total"           : total,
              "isidatatable"    : isidatatable
          };

      //console.log(isidatatable);
      // console.log(formData);
      $.ajax({
                type        : 'POST', 
                url         : '<?php echo site_url("Jurnalpengeluaran/simpan") ?>', 
                data        : formData, 
                dataType    : 'json', 
                encode      : true
            })
            .done(function(result){
                // console.log(result);
                if (result.success) {
                    alert("Berhasil simpan data!");
                    window.location.href = "<?php echo(site_url('Jurnalpengeluaran')) ?>";
                    
                }else{
                  // console.log(result.msg);
                  alert("Gagal simpan data!");
                }
            })
            .fail(function(){
                alert("Gagal script simpan data!");
            });

  })

</script>


</body>
</html>
