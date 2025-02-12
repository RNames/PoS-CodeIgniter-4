<?= $this->include('petugas/templates/header') ?>
<?= $this->include('petugas/templates/sidebar') ?>

<h3>Dashboard</h3>
<br />
<?php if (!empty($low_stock)): ?>
    <div class='alert alert-warning'>
        <span class='glyphicon glyphicon-info-sign'></span>
        Ada <span style='color:red'><?= count($low_stock) ?></span> barang yang stok tersisa kurang dari 3 items. Silahkan pesan lagi!!
        <span class='pull-right'><a href='<?= base_url('barang') ?>'>Tabel Barang <i class='fa fa-angle-double-right'></i></a></span>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h6 class="pt-2"><i class="fas fa-cubes"></i> Nama Barang</h6>
            </div>
            <div class="card-body">
                <center>
                    <h1><?= number_format($total_barang); ?></h1>
                </center>
            </div>
            <div class="card-footer">
                <a href='<?= base_url('barang') ?>'>Tabel Barang <i class='fa fa-angle-double-right'></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h6 class="pt-2"><i class="fas fa-chart-bar"></i> Stok Barang</h6>
            </div>
            <div class="card-body">
                <center>
                    <h1><?= number_format($total_stok); ?></h1>
                </center>
            </div>
            <div class="card-footer">
                <a href='<?= base_url('barang') ?>'>Tabel Barang <i class='fa fa-angle-double-right'></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h6 class="pt-2"><i class="fas fa-upload"></i> Telah Terjual</h6>
            </div>
            <div class="card-body">
                <center>
                    <h1><?= number_format($total_penjualan); ?></h1>
                </center>
            </div>
            <div class="card-footer">
                <a href='<?= base_url('laporan') ?>'>Tabel laporan <i class='fa fa-angle-double-right'></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h6 class="pt-2"><i class="fa fa-bookmark"></i> Kategori Barang</h6>
            </div>
            <div class="card-body">
                <center>
                    <h1><?= number_format($total_kategori); ?></h1>
                </center>
            </div>
            <div class="card-footer">
                <a href='<?= base_url('kategori') ?>'>Tabel Kategori <i class='fa fa-angle-double-right'></i></a>
            </div>
        </div>
    </div>
</div>


<?= $this->include('petugas/templates/footer') ?>