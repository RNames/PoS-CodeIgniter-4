<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Detail Barang</h2>
    <a href="<?= base_url('owner/barang') ?>" class="btn btn-secondary">Kembali</a>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title"><?= esc($barang['nama_barang']); ?></h5>
            <p><strong>Kategori:</strong> <?= esc($barang['nama_kategori']); ?></p>
            <p><strong>Harga Beli:</strong> Rp. <?= number_format($barang['harga_beli'], 0, ',', '.'); ?></p>
            <p><strong>Harga Jual 1:</strong> Rp. <?= number_format($barang['harga_jual_1'], 0, ',', '.'); ?></p>
            <p><strong>Harga Jual 2:</strong> Rp. <?= number_format($barang['harga_jual_2'], 0, ',', '.'); ?></p>
            <p><strong>Harga Jual 3:</strong> Rp. <?= number_format($barang['harga_jual_3'], 0, ',', '.'); ?></p>
        </div>
    </div>

    <h3 class="mt-4">Detail Stok</h3>
    <table class="table text-center">
        <thead>
            <tr>
                <th>ID Stok</th>
                <th>Tanggal Beli</th>
                <th>Tanggal Expired</th>
                <th>Jumlah Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($stokBarang)) : ?>
                <tr>
                    <td colspan="4">Tidak ada stok untuk barang ini.</td>
                </tr>
            <?php else : ?>
                <?php foreach ($stokBarang as $stok) : ?>
                    <tr>
                        <td><?= esc($stok['id']); ?></td>
                        <td><?= esc($stok['tanggal_beli']); ?></td>
                        <td><?= esc($stok['tanggal_expired']); ?></td>
                        <td><?= esc($stok['stok']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('admin/templates/footer') ?>
