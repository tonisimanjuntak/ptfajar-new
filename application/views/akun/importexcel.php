<?php  
  $this->load->view("template/header");
  $this->load->view("template/topmenu");
  $this->load->view("template/sidemenu");
?>
<style>
  .sub-test {
    margin-top: -10px;
    margin-bottom: 20px;
    color: #5D62C1;
  }
</style>

<div class="row" id="toni-breadcrumb">
    <div class="col-6">
        <h4 class="text-dark mt-2">Akun</h4>
    </div>  
    <div class="col-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="<?php echo(site_url()) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo(site_url('akun')) ?>">Akun</a></li>
        <li class="breadcrumb-item active" id="lblactive">Import Data Excel</li>
      </ol>
      
    </div>
  </div>

  <div class="row" id="toni-content">
    <div class="col-md-12">
      <form action="<?php echo(site_url('akun/importdata')) ?>" method="post" id="form" enctype="multipart/form-data">                      
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <h5>Pilih File Excel Yang Akan di Upload</h5>
                  </div>
                  <div class="col-12 sub-test">
                    <span class="">Penting! Format excel yang akan diupload harus sesuai dengan kebutuhan sistem!</span>
                  </div>
                  <div class="col-12">
                    <input name="filepegawai" type="file" required="required">                                         
                  </div>
                  <div class="col-12 mt-5">
                    <input name="upload" type="submit" class="btn btn-md btn-success" value="Mulai Import">
                  </div>
                </div>
                                        
              </div> <!-- ./card-body -->

            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
      </form>
    </div>
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>



<script type="text/javascript">
  

  $(document).ready(function() {

    $('.select2').select2();

    $("#form").bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        namaakun: {
          validators:{
            notEmpty: {
                message: "namaakun tidak boleh kosong"
            },
          }
        },
      }
    });


    $("form").attr('autocomplete', 'off');
  }); //end (document).ready
  
</script>

</body>
</html>
