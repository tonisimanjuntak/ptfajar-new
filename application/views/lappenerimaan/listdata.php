<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Laporan Penerimaan</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Laporan Penerimaan</li>
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
                <div class="form-group row">
                    <label for="tglawal" class="col-md-3 col-form-label text-right">Nama Akun Barang</label>
                    <div class="col-md-9">
                      <select name="kodeakun" id="kodeakun" class="form-control select2">
                        <option value="-">Semua akun barang...</option>
                        <?php  
                          $nlen = strlen($rowpengaturan->kodeakunbarang);

                          $rsakun = $this->db->query("select * from v_akun_level_max where left(kodeakun, ".$nlen.")  = '".$rowpengaturan->kodeakunbarang."' order by kodeakun");
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
        
        var kodeakun                 = $('#kodeakun').val();
        var tglawal                 = $('#tglawal').val();
        var tglakhir                 = $('#tglakhir').val();


        if (tglawal=='' || tglakhir=='') {
          swal("Tanggal Periode!", "Tanggal periode tidak boleh kosong!", "warning");
          return;
        }

        window.open("<?php echo site_url('lappenerimaan/cetak/pdf/') ?>" + tglawal + "/" + tglakhir + "/" + kodeakun  + "/Lap Penerimaan Barang");
    });


    $('#btnCetakExcel').click(function(e){
        // e.preventDefault();

        // fileter
        
        var kodeakun                 = $('#kodeakun').val();
        var tglawal                 = $('#tglawal').val();
        var tglakhir                 = $('#tglakhir').val();


        if (tglawal=='' || tglakhir=='') {
          swal("Tanggal Periode!", "Tanggal periode tidak boleh kosong!", "warning");
          return;
        }

        window.open("<?php echo site_url('lappenerimaan/cetak/excel/') ?>" + tglawal + "/" + tglakhir  + "/" + kodeakun + "/Lap Penerimaan Barang");


    });


</script>

</body>
</html>

