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



            </div>
            

        </div> <!-- ./card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
  <!-- Main row -->



<?php $this->load->view("template/footer") ?>


<script>

  $(document).ready(function() {
    
    $('.select2').select2();

        
  });


</script>

</body>
</html>

