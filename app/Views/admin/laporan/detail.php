<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Detail Transaksi #<?= esc($transaksi['id']) ?></h2>

    <table class="table table-bordered">
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

    <h3>Barang yang Dibeli</h3>
    <table class="table table-striped">
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

    <a href="<?= base_url('owner/laporan') ?>" class="btn btn-secondary">Kembali</a>
</div>

<?= $this->include('admin/templates/footer') ?>