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
        <div class="row">
          <div class="col-md-12">
            <div class="card" id="cardcontent">
              <div class="card-body">

                
                <form action="<?php echo(site_url('akun/importdata')) ?>" method="post" id="form" enctype="multipart/form-data">                      
                  <div class="row">
                    <div class="col-12">
                      <h5>Pilih File Excel Yang Akan di Upload</h5>
                    </div>
                    <div class="col-12 sub-test">
                      <span class="">Penting! Format excel yang akan diupload harus sesuai dengan kebutuhan sistem!</span>
                    </div>
                    <div class="col-12">
                      <input type="file" name="file">
                      <input type="submit" name="preview" value="Preview"><br><br>
                    </div>
                  </div>
                </form>


                <?php
                          if(isset($_POST['preview'])){ // Jika user menekan tombol Preview pada form 
                            if(isset($upload_error)){ // Jika proses upload gagal
                              echo "<div style='color: red;'>".$upload_error."</div>"; // Muncul pesan error upload
                              die; // stop skrip
                            }
                            
                            // Buat sebuah tag form untuk proses import data ke database
                            echo "<form method='post' action='".base_url("index.php/Pegawai/simpan_import")."'>";
                            
                            // Buat sebuah div untuk alert validasi kosong
                            echo "<div style='color: red; display: none;' id='kosong'>
                            Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum diisi (Yang berwarna Merah wajib diisi).
                            </div>";
                            
                            echo "
                            <div class='table-responsive'>
                            <table class='table table-bordered'>
                            <tr>
                              <th colspan='13'>Preview Data</th>
                            </tr>
                            <tr>
                              <th style='width: 5%; text-align: center;'>NIK</th>
                              <th>Nama</th>
                              <th>Jabatan</th>
                              <th>J Kelamin</th>
                              <th>Tgl Lahir</th>
                              <th>Agama</th>
                              <th>Pendidikan</th>
                              <th>Alamat</th>                             
                              <th>Status Nikah</th>
                            </tr>";
                            
                            $numrow = 1;
                            $kosong = 0;
                            
                            // Lakukan perulangan dari data yang ada di excel
                            // $sheet adalah variabel yang dikirim dari controller
                            foreach($sheet as $row){ 
                              // Ambil data pada excel sesuai Kolom
                              $nik = $row['A']; 
                              $nama = $row['B'];
                              $jabatan = $row['C'];  
                              $jkelamin = $row['D']; 
                              $tgllahir = $row['E']; 
                              $tempatlahir = $row['F']; 
                              $agama = $row['G']; 
                              $pendidikan = $row['H']; 
                              $alamat = $row['I']; 
                              $telp = $row['J'];
                              $email = $row['K']; 
                              $statusnikah = $row['L'];

                              // Cek jika semua data tidak diisi
                              if(empty($nik))
                                continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)
                              
                              // Cek $numrow apakah lebih dari 1
                              // Artinya karena baris pertama adalah nama-nama kolom
                              // Jadi dilewat saja, tidak usah diimport
                              if($numrow > 1){
                                // Validasi apakah semua data telah diisi
                                // Jika kosong, beri warna merah
                                $nik_td = ( ! empty($nik))? "" : " style='background: #E07171;'"; 
                                $nama_td = ( ! empty($nama))? "" : " style='background: #E07171;'"; 
                                $tgllahir_td = ( ! empty($tgllahir))? "" : " style='background: #E07171;'";
                                $jkelamin_td = ( ! empty($jkelamin))? "" : " style='background: #E07171;'";
                                $agama_td = ( ! empty($agama))? "" : " style='background: #E07171;'";
                                $jabatan_td = ( ! empty($jabatan))? "" : " style='background: #E07171;'";
                                $pendidikan_td = ( ! empty($pendidikan))? "" : " style='background: #E07171;'";
                                $statusnikah_td = ( ! empty($statusnikah))? "" : " style='background: #E07171;'";


                                // Jika salah satu data ada yang kosong
                                if(empty($nik) or empty($nama) or empty($jkelamin) or empty($tgllahir) or empty($agama) or empty($jabatan) or empty($pendidikan) or empty($statusnikah)){
                                  $kosong++;  // Tambah 1 variabel $kosong
                                }
                                
                                echo "<tr>";
                                echo "<td".$nik_td.">".$nik."</td>";
                                echo "<td".$nama_td.">".$nama."</td>";
                                echo "<td".$jabatan_td.">".$jabatan."</td>";
                                echo "<td".$jkelamin_td.">".$jkelamin."</td>";        
                                echo "<td".$tgllahir_td.">".$tgllahir."</td>";
                                echo "<td".$agama_td.">".$agama."</td>";
                                echo "<td".$pendidikan_td.">".$pendidikan."</td>";
                                echo "<td>".$alamat."</td>";
                                echo "<td".$statusnikah_td.">".$statusnikah."</td>";
                                echo "</tr>";
                              }
                              
                              $numrow++; // Tambah 1 setiap kali looping
                            }
                            
                            echo "</table>
                            </div>";
                            
                            // Cek apakah variabel kosong lebih dari 0
                            // Jika lebih dari 0, berarti ada data yang masih kosong
                            if($kosong > 0){
                            ?>  
                              <script>
                              $(document).ready(function(){
                                // Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
                                $("#jumlah_kosong").html('<?php echo $kosong; ?>');
                                
                                $("#kosong").show(); // Munculkan alert validasi kosong
                              });
                              </script>
                            <?php
                            }else{ // Jika semua data sudah diisi
                              echo "<hr>";
                              
                              // Buat sebuah tombol untuk mengimport data ke database
                              echo "<button type='submit' name='import' class='btn btn-success fa fa-cloud-upload'> Import Data</button>";
                              echo "<a href='".base_url("index.php/Pegawai")."' class='btn btn-default'>Cancel</a>";
                            }
                            
                            echo "</form>";
                          }
                          ?>


                                        
              </div> <!-- ./card-body -->

            </div> <!-- /.card -->
          </div> <!-- /.col -->
        </div>
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
