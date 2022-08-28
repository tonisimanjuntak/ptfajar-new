  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-warning elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo(base_url()) ?>" class="brand-link navbar-navy text-light text-sm">
      <img src="<?php echo $this->session->userdata('logoperusahaan'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo strtoupper($this->session->userdata('namaperusahaan')); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar text-sm">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $this->session->userdata('fotouser'); ?>" class="img-circle elevation-2" alt="User Image" style="width: 80px;">
        </div>
        <div class="info ml-3">
          <a href="#" class="d-block"><?php echo $this->session->userdata('namapengguna'); ?></a>
          <p>Administrator</p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="<?php echo(site_url()) ?>" class="nav-link <?php echo ($menu=='home') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <?php  
            $menudropdown = array("pengguna", "akun", "penandatangan", "pengaturan", "supplier");
            if (in_array($menu, $menudropdown)) {
              $dropdownselected = true;
            }else{
              $dropdownselected = false;
            }
          ?>

          <li class="nav-item has-treeview <?php echo ($dropdownselected) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?php echo ($dropdownselected) ? 'active' : '' ?>">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("akun")) ?>" class="nav-link <?php echo ($menu=='akun') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Akun</p>
                    </a>
                  </li>
              
                  
                  <!-- <li class="nav-item">
                    <a href="<?php echo(site_url("penandatangan")) ?>" class="nav-link <?php echo ($menu=='penandatangan') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Penandatangan</p>
                    </a>
                  </li> -->
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("pengaturan")) ?>" class="nav-link <?php echo ($menu=='pengaturan') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Pengaturan</p>
                    </a>
                  </li>
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("pengguna")) ?>" class="nav-link <?php echo ($menu=='pengguna') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Pengguna</p>
                    </a>
                  </li>
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("supplier")) ?>" class="nav-link <?php echo ($menu=='supplier') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Gudang</p>
                    </a>
                  </li>
                   


            </ul>
          </li>
          

          <?php  
            $menudropdown = array("penerimaan", "pengeluaran", "lappenerimaan", "lappengeluaran", "stokopname", "kartustok");
            if (in_array($menu, $menudropdown)) {
              $dropdownselected = true;
            }else{
              $dropdownselected = false;
            }
          ?>

          <li class="nav-item has-treeview <?php echo ($dropdownselected) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?php echo ($dropdownselected) ? 'active' : '' ?>">
              <i class="nav-icon fas fa-clipboard"></i>
              <p>
                Transaksi Barang
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                  
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("penerimaan")) ?>" class="nav-link <?php echo ($menu=='penerimaan') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Penerimaan Barang</p>
                    </a>
                  </li>
              
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("pengeluaran")) ?>" class="nav-link <?php echo ($menu=='pengeluaran') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Pengeluaran Barang</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("konfirmasiterkirim")) ?>" class="nav-link <?php echo ($menu=='konfirmasiterkirim') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Konfirmasi Barang Terkirim</p>
                    </a>
                  </li>


                  <li class="nav-item">
                    <a href="<?php echo(site_url("stokopname")) ?>" class="nav-link <?php echo ($menu=='stokopname') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Stok Opname</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("lappenerimaan")) ?>" class="nav-link <?php echo ($menu=='lappenerimaan') ? 'active' : '' ?>">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Laporan Penerimaan</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("lappengeluaran")) ?>" class="nav-link <?php echo ($menu=='lappengeluaran') ? 'active' : '' ?>">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Laporan Pengeluaran</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?php echo(site_url("kartustok")) ?>" class="nav-link <?php echo ($menu=='kartustok') ? 'active' : '' ?>">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Kartu Stok</p>
                    </a>
                  </li>
              
                  


            </ul>
          </li>


          <?php  
            $menudropdown = array("jurnalpenyesuaian", "lapjurnal");
            if (in_array($menu, $menudropdown)) {
              $dropdownselected = true;
            }else{
              $dropdownselected = false;
            }
          ?>

          <li class="nav-item has-treeview <?php echo ($dropdownselected) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?php echo ($dropdownselected) ? 'active' : '' ?>">
              <i class="nav-icon fa fa-book-open"></i>
              <p>
                Akuntansi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                  
                  
                  <li class="nav-item">
                    <a href="<?php echo(site_url("jurnalpenyesuaian")) ?>" class="nav-link <?php echo ($menu=='jurnalpenyesuaian') ? 'active' : '' ?>">
                      <i class="fa fa-circle-notch nav-icon"></i>
                      <p>Junal Penyesuaian</p>
                    </a>
                  </li>
                  

                  <li class="nav-item">
                    <a href="<?php echo(site_url("lapjurnal")) ?>" class="nav-link <?php echo ($menu=='lapjurnal') ? 'active' : '' ?>">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Cetak Jurnal</p>
                    </a>
                  </li>
              
                  


            </ul>
          </li>

          <li class="nav-item">
            <a href="<?php echo(site_url('Login/keluar')) ?>" class="nav-link text-warning">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
          
          
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
