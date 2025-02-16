<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h3>Dashboard</h3>
    <br />

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="pt-2"><i class="fas fa-cubes"></i> Total Barang</h6>
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

        <!-- Total Stok -->
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="pt-2"><i class="fas fa-chart-bar"></i> Total Stok</h6>
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

        <!-- Total Terjual -->
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="pt-2"><i class="fas fa-upload"></i> Total Terjual</h6>
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

        <!-- Total Kategori -->
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="pt-2"><i class="fa fa-bookmark"></i> Total Kategori</h6>
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
</div>
<br />

<div class="container">
    <div class="row">
        <div class="col mb-3">
            <h4>Total Penjualan 1 Minggu Terakhir</h4>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
        <div class="col mb-3">
            <!-- Low Stock Alert -->
            <?php if (!empty($low_stock)): ?>
                <div class='alert alert-warning'>
                    <span class='glyphicon glyphicon-info-sign'></span>
                    Ada <span style='color:red'><?= count($low_stock) ?></span> barang yang stok tersisa kurang dari minimal stok. Silahkan pesan lagi!!
                    <span class='pull-right'><a href='<?= base_url('barang') ?>'>Tabel Barang <i class='fa fa-angle-double-right'></i></a></span>
                </div>
            <?php endif; ?>


            <!-- Low Stock Table -->
            <?php if (!empty($low_stock)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok Tersisa</th>
                            <th>Minimal Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock as $barang): ?>
                            <tr>
                                <td><?= $barang['kode_barang']; ?></td>
                                <td><?= $barang['nama_barang']; ?></td>
                                <td><?= $barang['total_stok']; ?></td>
                                <td><?= $barang['minimal_stok']; ?></td>
                                <td>
                                    <a href="<?= base_url('owner/barang/tambahStok/' . $barang['kode_barang']); ?>" class="btn btn-primary btn-sm">Tambah</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $sales_dates; ?>, // Semua tanggal 7 hari terakhir
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: <?= $sales_totals; ?>, // Total penjualan (0 jika tidak ada transaksi)
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString(); // Format angka dengan koma
                        }
                    }
                }
            }
        }
    });
</script>


<?= $this->include('admin/templates/footer') ?>