<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <h2>Daftar Barang</h2>
    <a  href="<?= base_url('owner/barang/create') ?>" class="btn btn-primary mb-3 mt-3">Tambah Barang</a>

    <table class="table table-sm text-center">
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Tanggal Expired</th>
                <th>Harga Jual 1</th>
                <th>Harga Jual 2</th>
                <th>Harga Jual 3</th>
                <th>Minimal Stok</th>
                <th>Total Stok</th>
                <th>Aksi</th>
            </tr>
        <tbody>
            <?php foreach ($barang as $b) : ?>
                <tr>
                    <td><?= esc($b['kode_barang']); ?></td>
                    <td><?= esc($b['nama_barang'] ?? 'Tanpa Kategori'); ?></td>
                    <td><?= esc($b['nama_kategori']); ?></td>
                    <td><?= esc($b['expired']); ?></td>
                    <td>Rp. <?= number_format($b['harga_jual_1'], 0, ',', '.'); ?></td>
                    <td>Rp. <?= number_format($b['harga_jual_2'], 0, ',', '.'); ?></td>
                    <td>Rp. <?= number_format($b['harga_jual_3'], 0, ',', '.'); ?></td>
                    <td><?= esc($b['minimal_stok']); ?></td>
                    <td><?= esc($b['total_stok']); ?></td>
                    <td>
                        <a href="<?= base_url('owner/barang/detail/' . $b['kode_barang']) ?>" class="btn btn-primary">
                            <i class='fas fa-info-circle' style='font-size:20px'></i> 
                        </a>
                        <a href="<?= base_url('owner/barang/tambahStok/' . $b['kode_barang']) ?>" class="btn btn-info">
                            <i class='fas fa-plus' style='font-size:20px'></i> 
                        </a>
                        <a href="<?= base_url('owner/barang/edit/' . $b['id']) ?>" class="btn btn-warning">
                            <i class='fas fa-edit' style='font-size:20px'></i>
                        </a>
                        <button class="deleteBtn btn btn-danger" data-id="<?= $b['id'] ?>" class="btn btn-danger">
                            <i class='fas fa-trash-alt' style='font-size:20px'></i> 
                        </button>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function() {
            let memberId = this.getAttribute('data-id');
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/barang/delete/') ?>" + memberId;
                }
            });
        });
    });
</script>


<?= $this->include('admin/templates/footer') ?>