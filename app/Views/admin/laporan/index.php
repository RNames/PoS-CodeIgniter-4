<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container mt-4">
    <h2>Laporan Transaksi</h2>

    <!-- Form Filter -->
    <form action="<?= base_url('owner/transaksi/laporan') ?>" method="get" class="row g-3">
        <div class="col-md-3">
            <label for="id_laporan">ID Laporan:</label>
            <input type="text" name="id_laporan" class="form-control" placeholder="Masukkan ID Laporan" value="<?= esc($idLaporan ?? '') ?>">
        </div>
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


    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Transaksi</th>
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
                    <td><?= esc($row['id']) ?></td>
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