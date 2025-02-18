<!-- Sidebar -->

<style>
    .nav-item .nav-link.collapsed::after {

        color: black !important;
    }

    .nav-item .nav-link[data-toggle=collapse]::after {

        color: black !important;
    }

    .nav-item .nav-link {
        width: 100% !important;
    }

    .hovers {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hovers:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>

<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('owner/dashboard') ?>">
        <div class="sidebar-brand-text mx-3">KASIRAN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Home -->

    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link " href="<?= base_url('owner/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt text-primary"></i>
            <span class="text-dark">Home</span>
        </a>
    </li>


    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link" href="<?= base_url('owner/logs') ?>">
            <i class="fas fa-fw fa-database text-primary"></i>
            <span class="text-dark">Logs</span>
        </a>
    </li>

    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link" href="<?= base_url('owner/transaksi') ?>">
            <i class="fas fa-fw fa-cash-register text-primary"></i>
            <span class="text-dark">Transaksi</span>
        </a>
    </li>

    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link" href="<?= base_url('owner/laporan') ?>">
            <i class="fas fa-fw fa-desktop text-primary"></i>
            <span class="text-dark">Laporan Transaksi</span>
        </a>
    </li>

    <!-- Nav Item - Data Produk -->
    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-box text-primary"></i>
            <span class="text-dark text-center">Data Produk</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner shadow rounded">
                <a class="collapse-item" href="<?= base_url('owner/barang') ?>">Barang</a>
                <a class="collapse-item" href="<?= base_url('owner/kategori') ?>">Kategori</a>
            </div>
        </div>
    </li>

    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
            <i class="fas fa-fw fa-user-nurse text-primary"></i>
            <span class="text-dark text-center">Pengaturan Petugas</span>
        </a>
        <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner shadow rounded">
                <a class="collapse-item" href="<?= base_url('owner/pengaturan-petugas') ?>">Petugas Aktif</a>
                <a class="collapse-item" href="<?= base_url('owner/pengaturan-petugas/nonaktif') ?>">Petugas Nonaktif</a>
            </div>
        </div>
    </li>

    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
            <i class="fas fa-fw fa-user text-primary"></i>
            <span class="text-dark">Pengaturan Member</span>
        </a>
        <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner shadow rounded">
                <a class="collapse-item" href="<?= base_url('owner/pengaturan-member') ?>">Member Aktif</a>
                <a class="collapse-item" href="<?= base_url('owner/pengaturan-member/nonaktif') ?>">Member Nonaktif</a>
            </div>
        </div>
    </li>

    <li class="nav-item active bg-white my-2 mx-3 shadow rounded hovers">
        <a class="nav-link" href="#" id="btnLogout">
            <i class="fas fa-fw fa-sign-out-alt text-primary"></i>
            <span class="text-dark">Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0 mb-3">


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
                        <img class="img-profile rounded-circle" src="<?= base_url('assets/img/' . session()->get('gambar') ?? 'default-profile.png') ?>" alt="User Image">
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

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("btnLogout").addEventListener("click", function(e) {
                        e.preventDefault(); // Mencegah redirect langsung

                        Swal.fire({
                            title: "Konfirmasi Logout",
                            text: "Apakah Anda yakin ingin keluar?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Ya, Logout!",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "<?= base_url('logout') ?>"; // Redirect ke halaman logout
                            }
                        });
                    });
                });
            </script>