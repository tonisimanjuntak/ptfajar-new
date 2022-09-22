<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>


  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Home</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">Home</li>
      </ol>
      
    </div>
  </div>
  
  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        <div class="card-body">

            <div class="row">
              <div class="col-lg-6 col-6">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><i class="fa fa-warehouse"></i> <span id="jumlahstokhabis">0</span></h3>
                    <p>Stok Barang Habis</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3><i class="fa fa-paper-plane"></i> <span id="jumlahbarangbelumterkirim">0</span></h3>

                    <p>Barang Belum Terkirim</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                  <a href="<?php echo site_url('konfirmasiterkirim') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>


              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h3 class="card-title">Moving Average Harga Jual Barang Tahun Ini</h3>
                      <!-- <a href="javascript:void(0);">View Report</a> -->
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row" style="font-size: 12px;">
                      <div class="col-md-5">
                        
                        <div class="form-group row">
                            <label for="" class="col-md-2 col-form-label">Akun</label>
                            <div class="col-md-10">                              
                              <select name="kodeakun" id="kodeakun" class="form-control select2">
                                <option value="">Semua barang...</option>
                                <?php

                                  $cekakun = $this->db->query("select * from akun");
                                  if ($cekakun->num_rows()>0) {
                                    
                                    $kodeakunbarang = $this->db->query("select kodeakunbarang from pengaturan")->row()->kodeakunbarang;
                                    $nlen = strlen($kodeakunbarang);
                                    $level = $this->db->query("select max(level) as level from akun")->row()->level;
                                    $rsakun = $this->db->query("select * from akun where left(kodeakun, ".$nlen.")  = '".$kodeakunbarang."' and level=".$level." order by kodeakun");
                                    if ($rsakun->num_rows()>0) {
                                      
                                      foreach ($rsakun->result() as $row) {
                                        echo '<option value="'.$row->kodeakun.'">'.$row->kodeakun.' '.$row->namaakun.'</option>';
                                      }

                                    }
                                    
                                  }

                                ?>  
                              </select>
                            </div>
                        </div>

                      </div>

                      <div class="col-7">
                        <div class="form-group row">
                          <label for="" class="col-md-3 col-form-label text-right">Periode</label>
                          <div class="col-md-4">
                            <input type="date" name="tglawalMA" id="tglawalMA" class="form-control" value="<?php echo date('Y-01-01') ?>">
                          </div>
                          <label for="" class="col-md-1 col-form-label text-center">s/d</label>
                          <div class="col-md-4">
                            <input type="date" name="tglakhirMA" id="tglakhirMA" class="form-control" value="<?php echo date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-12 text-center mb-5">
                            <button class="btn btn-info" id="btnRefresh">Refresh</button>
                            <a href="" class="btn btn-primary" id="btnCetakMA">Cetak</a>
                        
                      </div>

                      <div class="col-12">
                        
                        <div class="position-relative mb-4">
                          <canvas id="visitors-chart" height="300"></canvas>
                        </div>

                      </div>
                    </div>


                  </div>
                </div>
              </div>



            </div>
            <!-- <h1 class="text-center">Selamat Datang Kembali <?php echo $this->session->userdata("namapengguna") ?></h1> -->

        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>


<script>

  $(document).ready(function() {
    
    $('.select2').select2();


    $.ajax({
        url: '<?php echo site_url("home/getinfobox") ?>',
        type: 'GET',
        dataType: 'json',
      })
      .done(function(resultinfo) {
        console.log(resultinfo);
        $('#jumlahstokhabis').html(resultinfo.jumlahstokhabis);
        $('#jumlahbarangbelumterkirim').html(resultinfo.jumlahbarangbelumterkirim);
      })
      .fail(function() {
        console.log("error");
      });
    
    getchartbarangkeluar();
        
  });





function getchartbarangkeluar()
{
    var kodeakun = $('#kodeakun').val();
    var tglawalMA = $('#tglawalMA').val();
    var tglakhirMA = $('#tglakhirMA').val();

    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true
    
    // =============================== Moving Average Tahun INI ======================================
    
    $.ajax({
      url: '<?php echo site_url('home/getchartbarangkeluar') ?>',
      type: 'GET',
      dataType: 'json',
      data: {'kodeakun': kodeakun, 'tglawalMA': tglawalMA, 'tglakhirMA': tglakhirMA},
    })
    .done(function(resulttahunini) {
      console.log(resulttahunini);
      $('#totalsemuatahunini').html('$'+resulttahunini.totalsemua);
      $('#averagetahunini').html('$'+resulttahunini.averagetahunini)
      var $visitorsChart = $('#visitors-chart')
      var visitorsChart = new Chart($visitorsChart, {
        data: {
          labels: resulttahunini.bulanbarangkeluar,
          datasets: resulttahunini.dataSet
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            mode: mode,
            intersect: intersect
          },
          hover: {
            mode: mode,
            intersect: intersect
          },
          legend: {
            display: true
          },
          scales: {
            yAxes: [{
              // display: false,
              gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
              },
              ticks: $.extend({
                beginAtZero: true,
                suggestedMax: 200
              }, ticksStyle)
            }],
            xAxes: [{
              display: true,
              gridLines: {
                display: false
              },
              ticks: ticksStyle
            }]
          }
        }
      })
    })
    .fail(function() {
      console.log("error get chart tahun ini");
    });

}

  $('#btnRefresh').click(function() {
    getchartbarangkeluar();
  });


  $('#btnCetakMA').click(function(e) {
    e.preventDefault();
    var tglawalMA = $('#tglawalMA').val();
    var tglakhirMA = $('#tglakhirMA').val();
    var kodeakun = $('#kodeakun').val();
  
    if (kodeakun=="") {
      kodeakun = "-";
    }
    window.open("<?php echo site_url('home/cetakMA/') ?>"+tglawalMA+'/'+tglakhirMA+'/'+kodeakun);    
  });
</script>

</body>
</html>

