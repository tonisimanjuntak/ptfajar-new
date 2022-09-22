<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>

  <div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Hapus Transaksi</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item active">Hapus Transaksi</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <div class="card" id="cardcontent">
        <div class="card-header">
          <h5 class="card-title">Hapus Transaksi</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="alert alert-danger text-center" role="alert">
                PERHATIAN!!! Fitur ini hanya untuk implementasi awal saja.<br>
                Semua data akan terhapus kecuali data pengguna
              </div>
            </div>
            <div class="col-12 text-center">
              <a href="<?php echo site_url('hapustransaksi/hapus') ?>" class="btn btn-danger" id="hapus">Hapus Transaksi</a>
            </div>


          </div> <!-- /.row -->
        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
  <!-- Main row -->




<?php $this->load->view("template/footer") ?>



<script type="text/javascript">

  var table;

  $(document).ready(function() {

    //defenisi datatable
  

  }); //end (document).ready

  
  $(document).on("click", "#hapus", function(e) {
    var link = $(this).attr("href");
    e.preventDefault();

    swal({
      title: "Hapus?",
      text: "Apakah anda yakin akan menghapus semua data? Data tidak dapat dikembalikan lagi jika sudah dihapus",
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

