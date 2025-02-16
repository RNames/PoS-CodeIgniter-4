<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<h2>Kategori Terhapus</h2>
<a href="<?= base_url('owner/kategori') ?>" class="btn btn-secondary">Kembali</a>

<table class="table table-bordered mt-3">
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
                    <button class="btn btn-success restore-btn" data-id="<?= $row['id'] ?>" data-name="<?= esc($row['nama_kategori']) ?>">
                        <i class="fa fa-undo"></i> Pulihkan
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->include('admin/templates/footer') ?>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.restore-btn').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            let name = this.getAttribute('data-name');

            Swal.fire({
                title: "Pulihkan Kategori?",
                text: "Apakah Anda ingin memulihkan kategori '" + name + "'?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Pulihkan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/kategori/restore/') ?>" + id;
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
