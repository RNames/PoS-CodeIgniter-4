<?= $this->include('petugas/templates/header') ?>
<?= $this->include('petugas/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">

    <div class="row">
        <div class="col">
            <a href="<?= base_url('petugas/laporan') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-9">
            <h2 class="text-center">Detail Transaksi</h2>
        </div>
        <div class="col">
            <a href="<?= base_url('petugas/transaksi/cetak_nota/' . esc($transaksi['id'])) ?>" class="btn btn-primary">
                <i class="fas fa-fw fa-print"></i> Cetak Nota
            </a>
        </div>
    </div>

    <div class="card border-success mt-3 mb-4 p-0">
        <table class="table table-striped mb-0">
            <tr>
                <th>Kode Transaksi</th>
                <td><?= esc($transaksi['kode_transaksi']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Transaksi</th>
                <td><?= esc($transaksi['tanggal_transaksi']) ?></td>
            </tr>
            <tr>
                <th>Kasir</th>
                <td><?= esc($transaksi['nama_kasir']) ?></td>
            </tr>
            <tr>
                <th>Member</th>
                <td><?= esc($transaksi['nama_member']) ?> (Tipe <?= esc($transaksi['tipe_member']) ?>)</td>
            </tr>
            <tr>
                <th>Total Belanja</th>
                <td>Rp <?= number_format($transaksi['total_belanja'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Diskon</th>
                <td>Rp <?= number_format($transaksi['diskon_rp'], 0, ',', '.') ?> (<?= $transaksi['diskon'] ?>%)</td>
            </tr>
            <tr>
                <th>PPN (12%)</th>
                <td>Rp <?= number_format($transaksi['ppn'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Total Akhir</th>
                <td>Rp <?= number_format($transaksi['total_akhir'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Total Bayar</th>
                <td>Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Kembalian</th>
                <td>Rp <?= number_format($transaksi['total_kembalian'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Poin Didapat</th>
                <td><?= esc($transaksi['poin_didapat']) ?></td>
            </tr>
        </table>
    </div>

    <h3>Barang yang Dibeli</h3>
    <table class="table text-center mt-3">
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detailTransaksi as $detail) : ?>
                <tr>
                    <td><?= esc($detail['kode_barang']) ?></td>
                    <td><?= esc($detail['nama_barang']) ?></td>
                    <td><?= esc($detail['jumlah']) ?></td>
                    <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($detail['total_harga'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('petugas/templates/footer') ?>