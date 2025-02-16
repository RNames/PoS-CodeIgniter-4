<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <h2><?= isset($date) ? 'Laporan Penjualan untuk Tanggal: ' . $date : 'Laporan Penjualan' ?></h2>

    <div class="container mt-4 mb-4">
        <form action="<?= site_url('owner/laporan/penjualan') ?>" method="get">

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label for="kode_transaksi">Cari Kode Transaksi</label>
                    <input type="text" id="kode_transaksi" name="kode_transaksi" class="form-control" value="<?= isset($kodeTransaksi) ? $kodeTransaksi : '' ?>" placeholder="Kode Transaksi">
                </div>

                <div class="col-md-6">
                    <label for="date">Pilih Tanggal</label>
                    <input type="date" id="date" name="date" class="form-control" value="<?= isset($date) ? $date : '' ?>">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col">
                    <button type="submit" class="btn btn-block btn-primary">Filter</button>
                </div>
                <div class="col">
                    <a href="<?= base_url('owner/laporan/penjualan') ?>" class="btn btn-block btn-secondary">Reset</a>
                </div>
                <div class="col">
                    <a href="<?= base_url('owner/laporan/export_pdf?date=' . $date . '&kode_transaksi=' . $kodeTransaksi) ?>" class="btn btn-block btn-danger">Export ke PDF</a>
                </div>
            </div>
        </form>
    </div>


    <?php if (isset($message)): ?>
        <div class="alert alert-warning"><?= $message ?></div>
    <?php else: ?>
        <div id="laporan">
            <table class="table mt-3 text-center">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $item): ?>
                        <tr>
                            <td><?= $item['kode_transaksi']; ?></td>
                            <td><?= date('d-m-Y', strtotime($item['tanggal_transaksi'])); ?></td> <!-- Tambahkan ini -->
                            <td><?= $item['nama_barang']; ?></td>
                            <td><?= $item['jumlah']; ?></td>
                            <td><?= number_format($item['total_harga'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Script Cetak -->
<script>
    function printReport() {
        var laporan = document.getElementById('laporan').innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = laporan;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>

<?= $this->include('admin/templates/footer') ?>