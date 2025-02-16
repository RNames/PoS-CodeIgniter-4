<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
            <h2>Daftar Kategori</h2>
    <div class="d-flex gap-2">
        <a href="<?= base_url('owner/kategori/create') ?>" class="btn btn-primary">Tambah Kategori</a>
        <a href="<?= base_url('owner/kategori/deleted') ?>" class="btn btn-secondary">Lihat Kategori Terhapus</a>
    </div>

    <table class="table text-center mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Kode Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($kategori as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($row['nama_kategori']) ?></td>
                    <td><?= esc($row['kode_kategori']) ?></td>
                    <td>
                        <a href="<?= base_url('owner/kategori/edit/' . $row['id']) ?>" class="btn btn-warning"><i class='fas fa-edit' style='font-size:20px'></i> Edit</a>
                        <button class="btn btn-danger delete-btn" data-id="<?= $row['id'] ?>" data-name="<?= esc($row['nama_kategori']) ?>"><i class='fas fa-trash-alt' style='font-size:20px'></i> Hapus</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('admin/templates/footer') ?>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let name = this.getAttribute('data-name');

            Swal.fire({
                title: "Hapus Kategori?",
                text: "Apakah Anda yakin ingin menghapus kategori '" + name + "'?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/kategori/delete/') ?>" + id;
                }
            });
        });
    });

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "<?= session()->getFlashdata('success') ?>",
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>