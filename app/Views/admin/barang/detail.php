<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/barang') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Detail Barang</h2>
        </div>
    </div>

    <div class="card border-success mt-3">
        <div class="card-body">
            <h5 class="card-title mb-2">Nama Barang : <?= esc($barang['nama_barang']); ?></h5>
            <p><strong>Kode Barang  :</strong> <?= esc($barang['kode_barang']); ?></p>
            <p><strong>Kategori     :</strong> <?= esc($barang['nama_kategori']); ?></p>
            <p><strong>Harga Beli   :</strong> Rp. <?= number_format($barang['harga_beli'], 0, ',', '.'); ?></p>
            <p><strong>Harga Jual 1 :</strong> Rp. <?= number_format($barang['harga_jual_1'], 0, ',', '.'); ?></p>
            <p><strong>Harga Jual 2 :</strong> Rp. <?= number_format($barang['harga_jual_2'], 0, ',', '.'); ?></p>
            <p><strong>Harga Jual 3 :</strong> Rp. <?= number_format($barang['harga_jual_3'], 0, ',', '.'); ?></p>
            <p><strong>Minimal Stok :</strong> <?= esc($barang['minimal_stok']); ?></p>
        </div>
    </div>

    <h3 class="mt-4">Detail Stok</h3>
    <table class="table text-center">
            <tr>
                <th>ID Stok</th>
                <th>Tanggal Beli</th>
                <th>Tanggal Expired</th>
                <th>Jumlah Stok</th>
                <th>Aksi</th>
            </tr>
        <tbody>
            <?php if (empty($stokBarang)) : ?>
                <tr>
                    <td colspan="6">Tidak ada stok untuk barang ini.</td>
                </tr>
            <?php else : ?>
                <?php foreach ($stokBarang as $stok) : ?>
                    <tr>
                        <td><?= esc($stok['id']); ?></td>
                        <td><?= esc($stok['tanggal_beli']); ?></td>
                        <td><?= esc($stok['tanggal_expired']); ?></td>
                        <td><?= esc($stok['stok']); ?></td>
                        <td>
                            <a href="<?= base_url('owner/barang/editStokForm/' . $stok['id']) ?>" class="btn btn-warning">
                                <i class='fas fa-edit' style='font-size:20px'></i>Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<script>
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function() {
            let stokId = this.getAttribute('data-id');
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menghapus stok ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/barang/deleteStok/') ?>" + stokId;
                }
            });
        });
    });

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            title: "Berhasil!",
            text: "<?= session()->getFlashdata('success'); ?>",
            icon: "success",
            confirmButtonText: "OK"
        });
    <?php endif; ?>
</script>

<?= $this->include('admin/templates/footer') ?>