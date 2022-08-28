<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Konfirmasi Barang Terkirim</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('konfirmasiterkirim')) ?>">Konfirmasi Barang Terkirim</a></li>
        <li class="breadcrumb-item active" id="lblactive"></li>
      </ol>
      
    </div>
  </div>


  <div class="row" id="toni-content">
    <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">

              <div class="card-body">


                  <div class="row">

                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <h3 class="text-muted">Info Pengeluaran</h3>
                          <hr>
                          <table class="table">
                            <tbody>
                              <tr>
                                <td style="width: 25%;">ID Pengeluaran Barang</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 70%;"><?php echo $rowpengeluaran->idpengeluaran ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Tgl Pengeluaran Barang</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 70%;"><?php echo tglindonesia($rowpengeluaran->tglpengeluaran) ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Jenis Pengeluaran</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 70%;"><?php echo $rowpengeluaran->jenispengeluaran ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Nama Gudang</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 70%;"><?php echo $rowpengeluaran->namagudang ?></td>
                              </tr>
                              <tr>
                                <td style="width: 25%;">Deskripsi</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 70%;"><?php echo $rowpengeluaran->deskripsi ?></td>
                              </tr>
                            </tbody>
                          </table>

                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                            <h3 class="text-muted">Info Detail Pengeluaran</h3>
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
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                  <tfoot>
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

                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <h3 class="text-muted">Konfirmasi barang terkirim</h3>
                          <hr>
                          <form action="<?php echo site_url('konfirmasiterkirim/simpan') ?>" method="post" id="form">

                            <input type="hidden" name="idpengeluaran" id="idpengeluaran" value="<?php echo $rowpengeluaran->idpengeluaran ?>">
                            <input type="hidden" name="idstatusterkirim" id="idstatusterkirim" value="<?php echo $rowpengeluaran->idstatusterkirim ?>">
                            
                            <div class="form-group row">
                              <label for="" class="col-md-3 col-form-label">Tgl Konfirmasi</label>
                              <div class="col-md-3">
                                <input type="date" name="tglstatusterkirim" id="tglstatusterkirim" class="form-control" value="<?php echo $tglstatusterkirim ?>">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label for="" class="col-md-3 col-form-label">Diterima Oleh</label>
                              <div class="col-md-9">
                                <textarea name="diterimaoleh" id="diterimaoleh" class="form-control" rows="2" placeholder="Diterima oleh"><?php echo $rowpengeluaran->diterimaoleh ?></textarea>
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class="col-12">
                                <input type="submit" name="statusterkirim" class="btn btn-md btn-success float-right" value="Sudah Terkirim">
                                <input type="submit" name="statusterkirim" class="btn btn-md btn-danger float-right mr-1" value="Belum Terkirim">
                                
                              </div>
                            </div>
                          
                          </form>
                        </div>
                      </div>
                    </div>

                  </div>

              </div> <!-- ./card-body -->

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
    $('#dividgudang').hide();

    table = $('#table').DataTable({ 
        "select": true,
            "processing": true, 
            "ordering": false,
            "bPaginate": false,      
            "searching": false,  
            "bInfo" : false, 
             "ajax"  : {
                      "url": "<?php echo site_url('konfirmasiterkirim/datatablesourcedetail')?>",
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
                                        .column( 4 )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );
                         
                                    // Total Halaman Terkait
                                    pageTotal = api
                                        .column( 4, { page: 'current'} )
                                        .data()
                                        .reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        }, 0 );
                                    
                                    jlhkeseluruhan = total;
                                    // Update footer
                                    $( api.column( 4 ).footer() ).html(
                                        'Rp. '+ numberWithCommas(total)                                        
                                    );
                                    $('#total').val( numberWithCommas(total) );
                                },
            "columnDefs": [
            { "targets": [ 1 ], "className": 'dt-body-center', "visible": false},
            { "targets": [ 3 ], "className": 'dt-body-center'},
            { "targets": [ 4 ], "className": 'dt-body-right'},
            { "targets": [ 5 ], "className": 'dt-body-right'},
            ],
     
        });

    //----------------------------------------------------------------- > validasi
    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        tglstatusterkirim: {
          validators:{
            notEmpty: {
                message: "tanggal terkirim tidak boleh kosong"
            },
          }
        },
        diterimaoleh: {
          validators:{
            notEmpty: {
                message: "diterima oleh tidak boleh kosong"
            },
          }
        },     
      }
    });
  //------------------------------------------------------------------------> END VALIDASI DAN SIMPAN


    $("form").attr('autocomplete', 'off');
    // $('#tglterima').mask('00-00-0000', {placeholder:"hh-bb-tttt"});
    // $('#hargajualsatuan').mask('000,000,000,000', {reverse: true, placeholder:"000,000,000,000"});
  }); //end (document).ready
  
  
</script>


</body>
</html>
