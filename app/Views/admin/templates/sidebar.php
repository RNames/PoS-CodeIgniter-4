<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('owner/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cash-register"></i>
        </div>  
        <div class="sidebar-brand-text mx-3">KASIRAN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Home -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('owner/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Home</span>
        </a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('owner/logs') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Logs</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Data Produk -->
    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-database"></i>
            <span>Data Produk</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('owner/barang') ?>">Barang</a>
                <a class="collapse-item" href="<?= base_url('owner/kategori') ?>">Kategori</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Transaksi -->
    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
            <i class="fas fa-fw fa-desktop"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('owner/transaksi') ?>">Transaksi Jual</a>
                <a class="collapse-item" href="<?= base_url('owner/laporan') ?>">Laporan Penjualan</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">


    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('owner/petugas') ?>">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Pengaturan Petugas</span>
        </a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('owner/member') ?>">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Pengaturan Member</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Store Name and Address -->
            <h5 class="d-lg-block d-none mt-2">
                <b>
                    <?= isset($toko['nama_toko']) ? esc($toko['nama_toko']) : 'Default Store Name'; ?>,
                    <?= isset($toko['alamat_toko']) ? esc($toko['alamat_toko']) : 'Default Address'; ?>
                </b>
            </h5>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="img-profile rounded-circle" src="<?= base_url('assets/img/' . session()->get('gambar') ?? 'default-profile.png') ?>" alt="User Image">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small ml-2">
                            Hallo <?= session()->get('roles') ?>, <?= session()->get('nama') ?>
                        </span>
                        <i class="fas fa-angle-down"></i>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="<?= base_url('owner/profile') ?>">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->


        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Your Page Content Here -->