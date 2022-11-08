<?php
$this->load->view("template/header");
$this->load->view("template/topmenu");
$this->load->view("template/sidemenu");
?>


  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Laporan Buku Besar</h4>
    </div>
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo (site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Laporan Buku Besar</li>
      </ol>

    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        <div class="card-body p-5" >


          <form action="<?php echo (site_url('lapbukubesar/cetak')) ?>" method="post">
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12" style="text-align: center;">
                    <h3>Pilih Periode Laporan</h3><br>
                  </div>
                  <div class="col-md-5">
                    <input type="date" id="tglawal" name="tglawal" class="form-control" value="<?php echo ($tglawal) ?>">
                  </div>
                  <div class="col-md-2 text-center">
                    <label for="" class="col-form-label">S/D</label>
                  </div>
                  <div class="col-md-5">
                    <input type="date" id="tglakhir" name="tglakhir" class="form-control" value="<?php echo ($tglakhir) ?>">
                  </div>
                  
                  <div class="col-md-12 mt-5">
                    <div class="form-group row">
                      <label for="" class="col-md-4">Jenis Akun</label>
                      <div class="col-md-8">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input optakun" type="radio" name="optradio" id="optakun3" value="3">
                          <label class="form-check-label" for="optakun3">Akun Level 3</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input optakun" type="radio" name="optradio" id="optakun4" value="4" checked="">
                          <label class="form-check-label" for="optakun4">Akun Level 4</label>
                        </div>                        
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group row">
                      <label for="" class="col-md-4 col-form-label">Nama Akun</label>
                      <div class="col-md-8">
                        <select name="kodeakun" id="kodeakun" class="form-control select2">
                          <option value="-">Pilih nama akun</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 mt-3">
                    <span class="btn btn-danger float-right" id="cetakpdf"><i class="fa fa-file-pdf"></i> Cetak PDF</span>
                    <span class="btn btn-success float-right mr-2" id="cetakexcel"><i class="fa fa-file-excel"></i> Cetak Excel</span>
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer")?>

<script>
  $(document).ready(function() {
    $('.select2').select2();

    get_akun4();

  });


  $('#cetakpdf').click(function(){
        cetak('pdf');
    });

  $('#cetakexcel').click(function(){
        cetak('excel');
    });

  function cetak(jeniscetakan)
  {
        var kodeakun       = $('#kodeakun').val();
        var tglawal       = $('#tglawal').val();
        var tglakhir      = $('#tglakhir').val();

        if ($('#optakun3').prop('checked')) {
          var jenisakun = '3';
        }else{
          var jenisakun = '4';
        }

        if (tglawal==='' || tglakhir==='') {
            alert('Pilih Periode!');
            return;
        }

        if (kodeakun==='-') {
            alert('Pilih nama akun!');
            return;
        }

        if (jeniscetakan=='pdf') {
          window.open("<?php echo site_url('lapbukubesar/cetak/pdf/') ?>" + jenisakun + "/" + kodeakun + "/" + tglawal + "/" + tglakhir + "/Lap Buku Besar");          
        }else{
          window.open("<?php echo site_url('lapbukubesar/cetak/excel/') ?>" + jenisakun + "/" + kodeakun + "/" + tglawal + "/" + tglakhir + "/Lap Buku Besar");          
        }
  }

  $('.optakun').click(function() {
    var akun = $(this).val();
    if (akun=='3') {
      get_akun3();
    }else{
      get_akun4();
    }
  });



  function get_akun4()
  {

      $("#kodeakun").empty();
      $("#kodeakun").append( new Option('Pilih nama akun', '-') );

      $.ajax({
        url: '<?php echo(site_url('lapbukubesar/get_akun4')) ?>',
        type: 'GET',
        dataType: 'json',
      })
      .done(function(rsakun) {
        console.log(rsakun)
        if (rsakun.length>0 ) {

          $.each(rsakun, function(index, val) {
             $("#kodeakun").append( new Option(rsakun[index]['kodeakun']+' - '+rsakun[index]['namaakun'], rsakun[index]['kodeakun']) );
          });
        }
      })
      .fail(function() {
        console.log("error get akun4");
      });

  }

  function get_akun3()
  {

      $("#kodeakun").empty();
      $("#kodeakun").append( new Option('Pilih nama akun', '-') );

      $.ajax({
        url: '<?php echo(site_url('lapbukubesar/get_akun3')) ?>',
        type: 'GET',
        dataType: 'json',
      })
      .done(function(rsakun) {
        console.log(rsakun)
        if (rsakun.length>0 ) {

          $.each(rsakun, function(index, val) {
             $("#kodeakun").append( new Option(rsakun[index]['kodeakun']+' - '+rsakun[index]['namaakun'], rsakun[index]['kodeakun']) );
          });
        }
      })
      .fail(function() {
        console.log("error get akun4");
      });

  }
</script>

</body>
</html>



