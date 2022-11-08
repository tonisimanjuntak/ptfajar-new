<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Laporan Jurnal</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Laporan Jurnal</li>
      </ol>
      
    </div>
  </div>


  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        <div class="card-header">
          <h5 class="card-title">Periode Laporan</h5>
        </div>
        <div class="card-body">


          <div class="col-12 row">
            <div class="col-md-4"></div>
            <div class="col-md-8 text-bold">
                
                <div class="form-group row">
                    <label for="tglawal" class="col-md-3 col-form-label text-right">Periode Laporan</label>
                    <div class="col-md-4">
                        <input type="date" name="tglawal" id="tglawal" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <label for="tglakhir" class="col-md-1 col-form-label text-center">s/d</label>
                    <div class="col-md-4">
                        <input type="date" name="tglakhir" id="tglakhir" class="form-control" value="<?php echo date('Y-m-d') ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12"><hr></div>

        <div class="col-12 text-center mt-5">
            <button class="btn btn-sm btn-success" id="btnCetakExcel"><i class="fa fa-file-excel"></i> Cetak Excel</button>
            <button class="btn btn-sm btn-danger" id="btnCetakPdf"><i class="fa fa-file-pdf"></i> Cetak PDF</button>
        </div>
          


        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
        


<?php $this->load->view("template/footer") ?>


<script type="text/javascript">

    $(document).ready(function() {
        $('.select2').select2();
        $('.select2').attr({
            width: '100%'
        });
    });



    $('#btnCetakPdf').click(function(e){
        // e.preventDefault();

        // fileter
        
        var tglawal                 = $('#tglawal').val();
        var tglakhir                 = $('#tglakhir').val();


        if (tglawal=='' || tglakhir=='') {
          swal("Tanggal Periode!", "Tanggal periode tidak boleh kosong!", "warning");
          return;
        }

        window.open("<?php echo site_url('lapjurnal/cetak/pdf/') ?>" + tglawal + "/" + tglakhir  + "/Lap Jurnal");
    });


    $('#btnCetakExcel').click(function(e){
        // e.preventDefault();

        // fileter
        
        var tglawal                 = $('#tglawal').val();
        var tglakhir                 = $('#tglakhir').val();
        var kdakun3                 = $('#kdakun3').val();
        var kdakun4                 = $('#kdakun4').val();


        if (tglawal=='' || tglakhir=='') {
          swal("Tanggal Periode!", "Tanggal periode tidak boleh kosong!", "warning");
          return;
        }

        if (kdakun3=='') {
            swal("Nama Akun 3!", "Nama Akun 3 tidak boleh kosong!", "warning");
            return;
        }

        if (kdakun4=='') {
            kdakun4='-';
        }

        window.open("<?php echo site_url('lapjurnal/cetak/excel/') ?>" + tglawal + "/" + tglakhir  + "/" + kdakun3 + "/" + kdakun4 + "/Lap Jurnal");


    });


</script>

</body>
</html>

