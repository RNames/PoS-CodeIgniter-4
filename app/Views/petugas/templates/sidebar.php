<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('petugas/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KASIRAN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Home -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('petugas/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Home</span>
        </a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('petugas/transaksi') ?>">
            <i class="fas fa-fw fa-cash-register"></i>
            <span>Transaksi</span>
        </a>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('petugas/laporan') ?>">
            <i class="fas fa-fw fa-desktop"></i>
            <span>Laporan Transaksi</span>
        </a>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
            <i class="fas fa-fw fa-user"></i>
            <span>Pengaturan Member</span>
        </a>
        <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('petugas/pengaturan-member') ?>">Member Aktif</a>
                <a class="collapse-item" href="<?= base_url('petugas/pengaturan-member/nonaktif') ?>">Member Nonaktif</a>
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0 mb-3">

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
            <h2 class="d-lg-block d-none mt-2">
                <b>
                    <?= ucfirst(str_replace('-', ' ', service('uri')->getSegment(2) ?? 'Dashboard')); ?>
                </b>
            </h2>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- User Information -->
                <li class="nav-item dropdown no-arrow">
                    <span class="nav-link dropdown-toggle" role="button">
                        <img class="img-profile rounded-circle" src="<?= base_url('assets/img/default-profile.png') ?>" alt="User Image">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small ml-2">
                            Hallo <?= session()->get('roles') ?>, <?= session()->get('nama') ?>
                        </span>
                    </span>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->


        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Your Page Content Here -->