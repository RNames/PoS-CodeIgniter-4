<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container mt-4">
    <h2>Laporan Transaksi</h2>

    <!-- Form Filter Tanggal -->
    <form action="<?= base_url('owner/transaksi/laporan') ?>" method="get" class="row g-3">
        <div class="col-md-3">
            <label for="start_date">Tanggal Mulai:</label>
            <input type="date" name="start_date" class="form-control" value="<?= esc($startDate ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="end_date">Tanggal Akhir:</label>
            <input type="date" name="end_date" class="form-control" value="<?= esc($endDate ?? '') ?>">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <table class="table table-bordered text-center mt-3">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama Kasir</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Tipe Pelanggan</th>
                <th>Total Pembelanjaan</th>
                <th>Diskon</th>
                <th>Poin Didapat</th>
                <th>Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporan)) : ?>
                <tr>
                    <td colspan="9">Tidak ada transaksi dalam rentang waktu yang dipilih.</td>
                </tr>
            <?php else : ?>
                <?php $no = 1; ?>
                <?php foreach ($laporan as $row) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= esc($row['nama_kasir']); ?></td>
                        <td><?= esc($row['tanggal_transaksi']); ?></td>
                        <td><?= esc($row['nama_member']); ?></td>
                        <td><?= esc($row['tipe_member']); ?></td>
                        <td>Rp. <?= number_format($row['total_belanja'], 0, ',', '.'); ?></td>
                        <td>Rp. <?= number_format($row['diskon'], 0, ',', '.'); ?></td>
                        <td><?= esc($row['poin_didapat']); ?></td>
                        <td>Rp. <?= number_format($row['total_akhir'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('admin/templates/footer') ?>
