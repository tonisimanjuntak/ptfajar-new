<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Jurnal Penyesuaian</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('jurnalpenyesuaian')) ?>">Jurnal Penyesuaian</a></li>
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


                  <form action="<?php echo(site_url('Jurnalpenyesuaian/simpan')) ?>" id="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card bg-light">
                          <div class="card-body p-2">
                            <div class="row">
                              <div class="col-md-2">
                                <div class="form-group">
                                  <label for="">Id Jurnal</label>
                                  <input type="text" id="idjurnal" name="idjurnal" class="form-control form-control-sm" placeholder="Id Jurnal (Otomatis)" readonly="">
                                </div>
                              </div>
                              <div class="col-md-2">
                                <div class="form-group required">
                                  <label for="">Tgl Jurnal</label>
                                  <input type="text" id="tgljurnal" name="tgljurnal" class="form-control form-control-sm" value="<?php echo(date('d-m-Y')) ?>">
                                </div>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-7">
                                <div class="row">
                                  <label for="" class="col-md-3 text-right">Deskripsi</label>
                                  <div class="col-md-9">
                                    <textarea name="deskripsi" id="deskripsi" class="form-control form-control-sm" rows="2" autofocus=""></textarea>
                                    
                                  </div>
                                  
                                </div>
                              </div>                                  
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-md-12 mt-3">
                        <div class="table-responsive">
                            <table id="table" class="display" style="width:100%;">
                              <thead class="text-light" style="background-color:#055F93;">
                                  <tr class="th-jurnal">
                                      <th style="text-align: center;">Akun</th>
                                      <th style="text-align: center; width: 25%;">Deskripsi</th>
                                      <th style="text-align: center; width: 10%;">Debet (Rp.)</th>
                                      <th style="text-align: center; width: 10%;">Kredit (Rp.)</th>
                                      <th style="text-align: center; width: 5%;">#</th>                        
                                  </tr>
                              </thead>
                              <tbody>
                                  
                                  
                              </tbody>
                              </table>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-12"><hr></div>
                          <div class="col-md-4">
                              <span class="btn btn-sm btn-success" id="addrow"><i class="fa fa-plus"></i> Tambah Baris (F2)</span>
                          </div>
                          <div class="col-md-8">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                  <div class="input-group-prepend" style="font-weight: bold;">
                                    <span class="input-group-text text-light" style="background-color:#055F93;">Total Debet (Rp.)</span>
                                  </div>
                                  <input type="text" class="form-control text-right font-weight-bold" name="totaldebet" readonly="" id="totaldebet">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                  <div class="input-group-prepend" style="font-weight: bold;">
                                    <span class="input-group-text text-light" style="background-color:#055F93;">Total Kredit (Rp.)</span>
                                  </div>
                                  <input type="text" class="form-control text-right font-weight-bold" name="totalkredit" readonly="" id="totalkredit">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12 text-right d-block p-3 mt-4">
                          
                                <button class="btn btn-info float-right" id="simpan"><i class="fa fa-save"></i> Simpan</button>
                                <a href="<?php echo(site_url('jurnalpenyesuaian')) ?>" class="btn btn-default float-right mr-1 ml-1"><i class="fa fa-chevron-circle-left"></i> Kembali</a>

                      </div>


                    </div>
                  </form>    


              </div> <!-- ./card-body -->

              
            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->

      

<?php $this->load->view("template/footer") ?>







<script type="text/javascript">

var idjurnal = "<?php echo($idjurnal) ?>";
  
  $(document).ready(function() {
    //---------------------------------------------------------> JIKA EDIT DATA
    if ( idjurnal != "" ) { 
          //console.log(idjurnal);
          $.ajax({
              type        : 'POST', 
              url         : '<?php echo site_url("Jurnalpenyesuaian/get_edit_data") ?>', 
              data        : {idjurnal: idjurnal}, 
              dataType    : 'json', 
              encode      : true
          })      
          .done(function(result) {
            console.log(result);

            $('#idjurnal').val(result.idjurnal);
            $('#deskripsi').val(result.deskripsi);
            $('#tgljurnal').val(result.tgljurnal);
            $('#file_lama2').val(result.filelampiran);
            if (result.filelampiran!='' && result.filelampiran!= null) {
              $('#lblfilelama').html('File terlampir : '+result.filelampiran);
            }


            var counter=1;
            $.each(result.RsDataDetail, function(key, value){

              tambahrow();
              // $('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html('');
              $('#kodeakun'+counter).val(value['kodeakun']);
              $('#namaakun'+counter).val(value['namaakun']);
              $('#deskripsidetail'+counter).val(value['deskripsidetail']);
              $('#debet'+counter).val(numberWithCommas(value['debet']));
              $('#kredit'+counter).val(numberWithCommas(value['kredit']));
              counter += 1;
            })
            hitungtotal();
          }); // end ajax.done
          $('#lblactive').html('Edit Data');
    }else{
          $('#lblactive').html('Tambah Data');
          tambahrow();
          tambahrow();
    } 


    $('#addrow').click(function(){
      tambahrow();
    })

    $("form").attr('autocomplete', 'off');
  }); //end (document).ready

  $('#form').submit(function(e) {


        if ($('#table tbody tr').length=0) {
            swal("Required!", "Tabel jurnal tidak boleh kosong!", "info")
            e.preventDefault();
            $('#simpan').prop('readonly', false);
            $('#simpan').prop('disabled', false);
            return false; 
        }

      if ($('#totaldebet').val()=='') {
            swal("Required!", "Total debet tidak boleh kosong!", "info")
            e.preventDefault();
            return false;
        }

        if ($('#totalkredit').val()=='') {
            swal("Required!", "Total kredit tidak boleh kosong!", "info")
            e.preventDefault();
            return false;
        }

        

        if ( $('#totaldebet').val() != $('#totalkredit').val()) {
            swal("Required!", "Total debet dan Total kredit harus sama!", "info")
            e.preventDefault();
            return false;
        }

  });

  function tambahrow()
  {
    var counter = $('#table tbody tr').length + 1;
    var addrow = '<tr>';
        addrow += '<td><input type="text" name="namaakun[]" id="namaakun'+counter+'" class="form-control form-control-sm akunautocomplate" value=""><input type="hidden" name="kodeakun[]" id="kodeakun'+counter+'"></td>';
        addrow += '<td><input type="text" name="deskripsidetail[]" id="deskripsidetail'+counter+'" class="form-control form-control-sm" value=""></td>';
        addrow += '<td><input type="text" name="debet[]" id="debet'+counter+'" class="form-control form-control-sm text-right" onchange="hitungtotal()"></td>';
        addrow += '<td><input type="text" name="kredit[]" id="kredit'+counter+'" class="form-control form-control-sm text-right" onchange="hitungtotal()"></td>';
        addrow += '<td style="text-align: center;"><a href="" id="removerow"><i class="fa fa-trash"></i></a></td>';
        addrow += '</tr>';


    $('#table tbody').append(addrow);
    $( "#namaakun"+counter ).autocomplete({
      minLength: 0,
      source: function( request, response ){
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('Jurnalpenyesuaian/akun4_autocomplate'); ?>",
            dataType: "json",
            data:{term: request.term},
            success: function(data){
              response( data );
            }
          });
      },
      focus: function( event, ui ) {
        $('#table tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(1) input#kodeakun'+counter).val(ui.item.kodeakun);
        $('#table tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(1) input#namaakun'+counter).val(ui.item.namaakun);
        return false;
      },
      select: function( event, ui ) {
        var kodeakun = ui.item.kodeakun;

        for (var i = 1; i < $('#table tbody tr').length+1; i++) {
          if ($('#kodeakun'+i).val()!='' && kodeakun==$('#kodeakun'+i).val() && i!=counter) {
                alert('Maaf, Akun ini sudah ada');
                $('#kodeakun'+counter).val('')
                $('#namaakun'+counter).val('')
                return false;   
          }
        };

        $('#table tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(1) input#kodeakun'+counter).val(ui.item.kodeakun);
        $('#table tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(1) input#namaakun'+counter).val(ui.item.namaakun);

        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<div><b>"+item.kodeakun +" "+ item.namaakun + "</b></div>" )
        .appendTo( ul );
    };

    $('#debet'+counter).mask('000,000,000,000', {reverse: true});
    $('#kredit'+counter).mask('000,000,000,000', {reverse: true});

    if (counter>2) {
      $('#table tbody tr').each(function(){
        $(this).find('td:nth-child(1) input#namaakun'+counter).focus();
      });
    }
    
  }

  $(document).on('click', '#removerow', function(e){
    e.preventDefault();
    $(this).parent().parent().remove();

    // Nomor Urut
    // var Nomor = 1;
    // $('#table tbody tr').each(function(){
    //   $(this).find('td:nth-child(1)').html(Nomor);
    //   Nomor++;
    // });

    hitungtotal();
  });



  function hitungtotal()
  {
    var totaldebet = 0;
    var totalkredit = 0;
    for (var i = 1; i < $('#table tbody tr').length+1; i++) {
      var debet = untitik($('#debet'+i).val());
      var kredit = untitik($('#kredit'+i).val());

      if (debet=='') {
        debet=0;
      }else{
        debet = parseInt(debet);
      }
      if (kredit=='') {
        kredit=0;
      }else{
        kredit = parseInt(kredit);
      }

      totaldebet += debet;
      totalkredit += kredit;
    };

      $('#totaldebet').val(numberWithCommas(totaldebet));
      $('#totalkredit').val(numberWithCommas(totalkredit));
  }


  $(document).on('keydown', 'body', function(e){
    var charCode = ( e.which ) ? e.which : event.keyCode;
    // console.log(charCode);

    if(charCode == 113) //F2
    {
      tambahrow();
      return false;
    }

  });

  $('#tgljurnal').mask('00-00-0000', {placeholder:"__-__-____"});

</script>



<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script
  src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"
  integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk="
  crossorigin="anonymous"></script>

<script>

</body>
</html>
