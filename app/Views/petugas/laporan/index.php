<?= $this->include('petugas/templates/header') ?>
<?= $this->include('petugas/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <h2>Laporan Transaksi</h2>

    <!-- Form Filter -->
    <div class="container mt-4 mb-4">
        <form action="<?= base_url('petugas/laporan') ?>" method="get">

            <div class="row g-3 mb-2">
                <div class="col-md-4">
                    <label for="kode_transaksi">Kode Transaksi</label>
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
            </div>

            <div class="row g-3 mb-4">
                <div class="col">
                    <button type="submit" class="btn btn-block btn-primary">Filter</button>
                </div>
                <div class="col">
                    <a href="<?= base_url('petugas/laporan') ?>" class="btn btn-block btn-secondary">Reset</a>
                </div>
                <div class="col">
                    <a href="<?= base_url('petugas/laporan/exportPdf?start_date=' . ($startDate ?? '') . '&end_date=' . ($endDate ?? '')) ?>" class="btn btn-danger btn-block" target="_blank">
                        <i class="fa fa-file-pdf"></i> Cetak Laporan
                    </a>
                </div>
            </div>

        </form>



        <table class="table mt-3 text-centered">
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Member</th>
                <th>Total Akhir</th>
                <th>Aksi</th>
            </tr>
            <tbody>
                <?php foreach ($laporan as $row) : ?>
                    <tr>
                        <td><?= esc($row['kode_transaksi']) ?></td>
                        <td><?= esc($row['tanggal_transaksi']) ?></td>
                        <td><?= esc($row['nama_kasir']) ?></td>
                        <td><?= esc($row['nama_member']) ?> (Tipe <?= esc($row['tipe_member']) ?>)</td>
                        <td>Rp <?= number_format($row['total_akhir'], 0, ',', '.') ?></td>
                        <td>
                            <a href="<?= base_url('petugas/laporan/detail/' . $row['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-info-circle"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
<?= $this->include('petugas/templates/footer') ?>