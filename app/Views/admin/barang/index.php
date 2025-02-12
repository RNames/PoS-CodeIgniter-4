<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>


<div class="container">
    <h2>Daftar Barang</h2>
    <a style="margin-bottom: 10px;" href="<?= base_url('owner/barang/create') ?>" class="btn btn-primary">Tambah Barang</a>

    <table class="table text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Tanggal Expired</th>
                <th>Harga Jual 1</th>
                <th>Harga Jual 2</th>
                <th>Harga Jual 3</th>
                <th>Total Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
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
                    <td><?= esc($b['total_stok']); ?></td>
                    <td>
                        <a href="<?= base_url('owner/barang/detail/' . $b['kode_barang']) ?>" class="btn btn-primary">
                            <i class='fas fa-info-circle' style='font-size:20px'></i> Detail
                        </a>
                        <a href="<?= base_url('owner/barang/tambahStok/' . $b['kode_barang']) ?>" class="btn btn-info">
                            <i class='fas fa-plus' style='font-size:20px'></i> Tambah Stok
                        </a>
                        <a href="<?= base_url('owner/barang/edit/' . $b['id']) ?>" class="btn btn-warning">
                            <i class='fas fa-edit' style='font-size:20px'></i> Edit
                        </a>
                        <button class="deleteBtn btn btn-danger" data-id="<?= $b['id'] ?>" class="btn btn-danger">
                            <i class='fas fa-trash-alt' style='font-size:20px'></i> Hapus
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