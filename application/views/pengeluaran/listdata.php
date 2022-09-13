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
        <li class="breadcrumb-item active">Pengeluaran</li>
      </ol>
      
    </div>
  </div>


  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        <div class="card-header">
          <h5 class="card-title">List Data Pengeluaran</h5>
          <a href="<?php echo(site_url('pengeluaran/tambah')) ?>" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus-circle"></i> Tambah Data</a>
          <a href="<?php echo(site_url('pengeluaran/importexcel')) ?>" class="btn btn-sm btn-success float-right mr-2"><i class="fa fa-cloud-upload-alt" target="_blank"></i> Upload Excel</a>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <!-- datatable -->
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-condesed" id="table">
                  <thead>
                    <tr class="bg-primary" style="">
                      <th style="width: 5%; text-align: center;">No</th>
                      <th style="text-align: center;">Tgl /<br>ID Pengeluaran</th>
                      <th style="text-align: center;">Deskripsi</th>
                      <th style="text-align: center;">Nama Gudang</th>
                      <th style="text-align: center;">Jenis Pengeluaran</th>
                      <th style="text-align: center;">Total Pengeluaran</th>
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
            "url": "<?php echo site_url('pengeluaran/datatablesource')?>",
            "type": "POST"
        },
        "columnDefs": [
                        { "targets": [ 0 ], "orderable": false, "className": "dt-body-center" },
                        { "targets": [ 1 ], "className": "dt-body-center" },
                        { "targets": [ 2 ], "className": "dt-body-left" },
                        { "targets": [ 3 ], "className": "dt-body-center" },
                        { "targets": [ 4 ], "className": "dt-body-center" },
                        { "targets": [ 5 ], "className": "dt-body-center" },
                        { "targets": [ 6 ], "orderable": false, "className": "dt-body-center" },
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
   
  

</script>

</body>
</html>

