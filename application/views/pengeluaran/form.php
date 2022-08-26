<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Pengeluaran</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('pengeluaran')) ?>">Pengeluaran</a></li>
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


                  <input type="hidden" name="idpengeluaran" id="idpengeluaran">
                  
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">Tgl Pengeluaran</label>
                    <div class="col-md-3">
                      <input type="date" name="tglpengeluaran" id="tglpengeluaran" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                  </div>
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">Deskripsi</label>
                    <div class="col-md-10">
                      <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi"></textarea>
                    </div>
                  </div>
                  
                  <div class="form-group row required">
                    <label for="" class="col-md-2 col-form-label">Jenis Pengeluaran</label>
                    <div class="col-md-10">
                      <select name="jenispengeluaran" id="jenispengeluaran" class="form-control">
                        <option value="">Pilih jenis pengeluaran...</option>
                        <option value="Penjualan">Penjualan</option>
                        <option value="Barang Keluar">Barang Keluar</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                          <h3 class="text-muted text-center">Detail Pengeluaran</h3>
                          <hr>

                          
                          <form action="<?php echo(site_url('pengeluaran/simpan')) ?>" method="post" id="form">                      
                            <div class="row">

                              <div class="col-md-5">
                                <div class="form-group">
                                  <label for="">Kode Akun</label>
                                    <select name="kodeakun" id="kodeakun" class="form-control select2">
                                      <option value="">Pilih kode akun...</option>
                                      <?php
                                        $kodeakunbarang = $this->db->query("select kodeakunbarang from pengaturan")->row()->kodeakunbarang;
                                        $level = $this->db->query("select max(level) as level from akun")->row()->level;

                                        $rsakun = $this->db->query("select * from akun where kodeakun like '%".$kodeakunbarang."%' and level=".$level." order by kodeakun");
                                        foreach ($rsakun->result() as $row) {
                                          echo '<option value="'.$row->kodeakun.'">'.$row->kodeakun.' '.$row->namaakun.'</option>';
                                        }
                                      ?>  
                                    </select>
                                </div>
                              </div>
                              <div class="col-md-1">
                                <div class="form-group">
                                  <label for="">Qty</label>
                                  <input type="number" name="jumlahbarang" id="jumlahbarang" class="form-control" value="1" min="1">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">Harga Jual</label>
                                  <input type="text" name="hargajual" id="hargajual" class="form-control rupiah">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">Total</label>
                                  <input type="text" name="totalharga" id="totalharga" class="form-control rupiah" readonly="">
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
                                        <th style="">kodeakun</th>
                                        <th style="">Nama Akun</th>
                                        <th style="text-align: center; width: 5%;">Qty</th>
                                        <th style="text-align: right; width: 15%;">Harga Jual</th>
                                        <th style="text-align: right; width: 15%;">Sub Total</th>
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
                <a href="<?php echo(site_url('pengeluaran')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>
              </div>
            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->

      

<?php $this->load->view("template/footer") ?>




<script type="text/javascript">
  
  var idpengeluaran = "<?php echo($idpengeluaran) ?>";

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
                      "url": "<?php echo site_url('pengeluaran/datatablesourcedetail')?>",
                      "dataType": "json",
                      "type": "POST",
                      "data": {"idpengeluaran": '<?php echo($idpengeluaran) ?>'}
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
                                        .column( 5 )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );
                         
                                    // Total Halaman Terkait
                                    pageTotal = api
                                        .column( 5, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );
                                    
                                    jlhkeseluruhan = total;
                                    // Update footer
                                    $( api.column( 5 ).footer() ).html(
                                        'Rp. '+ numberWithCommas(total)                                        
                                    );
                                    $('#total').val( numberWithCommas(total) );
                                },
            "columnDefs": [
            { "targets": [ 1 ], "className": 'dt-body-center', "visible": false},
            { "targets": [ 3 ], "className": 'dt-body-center'},
            { "targets": [ 4 ], "className": 'dt-body-right'},
            { "targets": [ 5 ], "className": 'dt-body-right'},
            { "targets": [ 6 ], "orderable": false, "className": 'dt-body-center'},
            ],
     
        });



    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idpengeluaran != "" ) { 
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("pengeluaran/get_edit_data") ?>', 
              data        : {idpengeluaran: idpengeluaran}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            console.log(result);
            $('#idpengeluaran').val(result.idpengeluaran);
            $('#tglpengeluaran').val(result.tglpengeluaran);
            $('#deskripsi').val(result.deskripsi);
            $('#idsupplier').val(result.idsupplier).trigger('change');
            $('#jenispengeluaran').val(result.jenispengeluaran);
          }); 
          
          $('#lbljudul').html('Edit Data Pengeluaran');
          $('#lblactive').html('Edit');

    }else{
          $('#lbljudul').html('Tambah Data Pengeluaran');
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
        kodeakun: {
          validators:{
            notEmpty: {
                message: "kode akun tidak boleh kosong"
            },
          }
        },
        jumlahbarang: {
          validators:{
            notEmpty: {
                message: "jumlah barang tidak boleh kosong"
            },
          }
        },
        hargajual: {
          validators:{
            notEmpty: {
                message: "harga jual tidak boleh kosong"
            },
          }
        },
        totalharga: {
          validators:{
            notEmpty: {
                message: "total harga tidak boleh kosong"
            },
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
      e.preventDefault();
      $('#tambahkan').attr('disabled', false);
      
    var kodeakun           = $('#kodeakun').val();
    var jumlahbarang           = $('#jumlahbarang').val();
    var hargajual           = $('#hargajual').val();
    var totalharga           = $('#totalharga').val();

        if (hargajual=="0") {
          swal("Harga jual!", "Harga jual tidak boleh kosong.", "info");
          return false;
        }
        if (totalharga=="0") {
          swal("Total Harga!", "Total Harga tidak boleh kosong.", "info");
          return false;
        }
      
      var isicolomn = table.columns(1).data().toArray();
      for (var i = 0; i < isicolomn.length; i++) {
        for (var j = 0; j < isicolomn[i].length; j++) {            
          if (isicolomn[i][j] === kodeakun) {
              swal("Nama Akun!", "Nama akun sudah ada.", "info");
              return false;
          }
        }
      };

        nomorrow = table.page.info().recordsTotal + 1;
        table.row.add( [
                            nomorrow,
                            $("#kodeakun").val(),
                            $('#kodeakun option:selected').text(),
                            $("#jumlahbarang").val(),
                            $("#hargajual").val(),
                            $("#totalharga").val(),
                            '<span class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></span>'
                        ] ).draw( false );
        $("#kodeakun").val("").trigger('change');
        $("#jumlahbarang").val("1");
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
    // $('#hargajualsatuan').mask('000,000,000,000', {reverse: true, placeholder:"000,000,000,000"});
  }); //end (document).ready
  

  
  $('#simpan').click(function(){
    var idpengeluaran       = $("#idpengeluaran").val();
    var tglpengeluaran       = $("#tglpengeluaran").val();
    var deskripsi       = $("#deskripsi").val();
    var jenispengeluaran       = $("#jenispengeluaran").val();
    var jumlahpengeluaran       = $("#total").val();

      if (tglpengeluaran=='') {
        swal("Tanggal Pengeluaran!", "Tanggal pengeluaran tidak boleh kosong.", "info");
        return; 
      }
      if (deskripsi=='') {
        swal("Deskripsi!", "Deskripsi tidak boleh kosong.", "info");
        return; 
      }
      if (jenispengeluaran=='') {
        swal("Jenis Pengeluaran!", "Jenis pengeluaran tidak boleh kosong.", "info");
        return; 
      }
      if (jumlahpengeluaran=='') {
        swal("Jumlah Pengeluaran!", "Jumlah pengeluaran tidak boleh kosong.", "info");
        return; 
      }

    if ( ! table.data().count() ) {
          swal("Detail Pengeluaran!", "Detail pengeluaran belum ada.", "info");
          return;
      }

      var isidatatable = table.data().toArray();

      var formData = {
              "idpengeluaran"       : idpengeluaran,
              "tglpengeluaran"       : tglpengeluaran,
              "deskripsi"       : deskripsi,
              "jenispengeluaran"       : jenispengeluaran,
              "jumlahpengeluaran"       : jumlahpengeluaran,
              "isidatatable"    : isidatatable
          };

      //console.log(isidatatable);
      // console.log(formData);
      $.ajax({
                type        : 'POST', 
                url         : '<?php echo site_url("pengeluaran/simpan") ?>', 
                data        : formData, 
                dataType    : 'json', 
                encode      : true
            })
            .done(function(result){
                // console.log(result);
                if (result.success) {
                    swal("Simpan Berhasil!", "Data berhasil disimpan.", "info");
                    swal({
                      title: "Simpan Berhasil?",
                      text: "Data berhasil disimpan",
                      icon: "success",
                    })
                    .then((willDelete) => {
                      window.location.href = "<?php echo(site_url('pengeluaran')) ?>";
                    });
                    
                }else{
                  // console.log(result.msg);
                  swal("Gagal Simpan!", "Data gagal disimpan.", "info");
                }
            })
            .fail(function(){
                swal("Gagal!", "Script erorr.", "info");
            });

  })

  $('#jumlahbarang').change(function() {
    hitungsubtotal();
  });

  $('#hargajual').change(function() {
    hitungsubtotal();
  });

  function hitungsubtotal()
  {
    var jumlahbarang = $('#jumlahbarang').val();
    var hargajual = $('#hargajual').val();
    var subtotal = parseInt(untitik(jumlahbarang)) * parseInt(untitik(hargajual));
    $('#totalharga').val(numberWithCommas(subtotal));
  }


</script>


</body>
</html>
