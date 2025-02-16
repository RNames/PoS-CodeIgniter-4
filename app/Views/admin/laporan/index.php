<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container mt-4">
    <h2>Laporan Transaksi</h2>

    <!-- Form Filter -->
    <div class="container mt-4 mb-4">
        <form action="<?= base_url('owner/laporan') ?>" method="get" class="row g-3">
            <div class="col-md-4">
                <label for="kode_transaksi">Kode Transaksi:</label>
                <input type="text" name="kode_transaksi" class="form-control" placeholder="Masukkan Kode Transaksi" value="<?= esc($kodeTransaksi ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="<?= esc($startDate ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label for="end_date">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="<?= esc($endDate ?? '') ?>">
            </div>
            <div class="col-md-3 align-self-end d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?= base_url('owner/laporan') ?>" class="btn btn-secondary">Reset</a>
            </div>

        </form>
    </div>


    <table class="table table-striped">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Member</th>
                <th>Total Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($laporan as $row) : ?>
                <tr>
                    <td><?= esc($row['kode_transaksi']) ?></td>
                    <td><?= esc($row['tanggal_transaksi']) ?></td>
                    <td><?= esc($row['nama_kasir']) ?></td>
                    <td><?= esc($row['nama_member']) ?> (Tipe <?= esc($row['tipe_member']) ?>)</td>
                    <td>Rp <?= number_format($row['total_akhir'], 0, ',', '.') ?></td>
                    <td>
                        <a href="<?= base_url('owner/laporan/detail/' . $row['id']) ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?= $this->include('admin/templates/footer') ?>