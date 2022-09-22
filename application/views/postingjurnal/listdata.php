<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Posting Jurnal</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Posting Jurnal</li>
      </ol>
      
    </div>
  </div>


  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        
        <div class="card-body">
          <div class="row">

            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h3 class="text-center mt-3 mb-5">Posting Jurnal Otomatis</h3>
                    </div>
                    <div class="col-12">
                      <div class="form-group row">
                        <label for="" class="col-md-2 text-right">Tahun</label>
                        <div class="col-md-2">
                          <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y') ?>">
                        </div>
                        <label for="" class="col-md-1 text-right">Bulan</label>
                        <div class="col-md-3">
                          <select name="bulan" id="bulan" class="form-control">
                            <option value="">Pilih bulan...</option>
                            <option value="01">01 - Januari</option>
                            <option value="02">02 - Februari</option>
                            <option value="03">03 - Maret</option>
                            <option value="04">04 - April</option>
                            <option value="05">05 - Mei</option>
                            <option value="06">06 - Juni</option>
                            <option value="07">07 - Juli</option>
                            <option value="08">08 - Agustus</option>
                            <option value="09">09 - September</option>
                            <option value="10">10 - Oktober</option>
                            <option value="11">11 - November</option>
                            <option value="12">12 - Desember</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <button class="btn btn-primary" id="btnMulaiPosting">Mulai Posting</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12">
              <h3>List Data Posting</h3>
            </div>

            <div class="col-md-12">
              <!-- datatable -->
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-condesed" id="table">
                  <thead>
                    <tr class="bg-primary" style="">
                      <th style="width: 10%; text-align: center;">Tahun</th>
                      <th style="width: 10%; text-align: center;">Bulan</th>
                      <th style="text-align: center;">Tgl Posting</th>
                      <th style="text-align: center;">Nama User</th>
                      <th style="text-align: center; width: 15%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>              
                </table>
              </div>

            </div>



          </div> <!-- /.row -->
        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
        


<?php $this->load->view("template/footer") ?>



<script type="text/javascript">

  var table;

  $(document).ready(function() {

    //defenisi datatable
    table = $("#table").DataTable({ 
        "select": true,
        "processing": true, 
        "serverSide": true, 
        "order": [], 
         "ajax": {
            "url": "<?php echo site_url('postingjurnal/datatablesource')?>",
            "type": "POST"
        },
        "columnDefs": [
                        { "targets": [ 0 ], "orderable": false, "className": "dt-body-center" },
                        { "targets": [ 1 ], "className": "dt-body-center" },
                        { "targets": [ 2 ], "className": "dt-body-center" },
                        { "targets": [ 3 ], "className": "dt-body-center" },
                        { "targets": [ 4 ], "orderable": false, "className": "dt-body-center" },
        ],
 
    });

  }); //end (document).ready

  
  $(document).on("click", "#hapus", function(e) {
    var link = $(this).attr("href");
    e.preventDefault();

    swal({
      title: "Hapus?",
      text: "Apakah anda yakin akan menghapus data ini!",
      icon: "warning",
      buttons: ["Batal", "Ya"],
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        document.location.href = link;
      }
    });

  });
  
  $('#btnMulaiPosting').click(function() {
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();

    swal({
      title: "Mulai Posting?",
      text: "Proses ini akan membutuhkan waktu beberapa saat! Silahkan tunggu sampai notifikasi selesai!",
      icon: "info",
      buttons: ["Batal", "Ok"],
    })
    .then((mulaiposting) => {
      if (mulaiposting) {
        $.ajax({
          url: '<?php echo site_url('postingjurnal/mulaiposting') ?>',
          type: 'GET',
          dataType: 'json',
          data: {'tahun': tahun, 'bulan': bulan},
        })
        .done(function(resultmulaiposting) {
          if (resultmulaiposting) {
            swal("Posting Selesai!", "Data berhasil di posting!", "success");
          }
        })
        .fail(function() {
          swal("Gagal!", "Posting Gagal!", "error");
          console.log("error");
        });
        
      }
    });
  });
  

</script>

</body>
</html>

