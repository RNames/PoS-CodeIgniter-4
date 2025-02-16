<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Daftar Petugas Aktif</h2>
    <div class="d-flex gap-2 mb-4">
        <a href="<?= base_url('owner/pengaturan-petugas/create') ?>" class="btn btn-primary">Tambah Petugas</a>
        <a href="<?= base_url('owner/pengaturan-petugas/nonaktif') ?>" class="btn btn-secondary">Lihat Petugas Nonaktif</a>
    </div>
    <table class="table text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activePetugas as $p) : ?>
                <tr>
                    <td><?= $p->id ?></td>
                    <td><?= $p->nm_petugas ?></td>
                    <td><?= $p->email ?></td>
                    <td><?= $p->roles ?></td>
                    <td>
                        <a href="<?= base_url('owner/pengaturan-petugas/edit/' . $p->id) ?>" class="btn btn-warning">
                            <i class='fas fa-edit' style='font-size:20px'></i> Edit
                        </a>
                        <button class="deleteBtn btn btn-danger" data-id="<?= $p->id ?>">
                            <i class='fas fa-power-off' style='font-size:20px'></i> Nonaktifkan
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
            let petugasId = this.getAttribute('data-id');
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menonaktifkan petugas ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Nonaktifkan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/pengaturan-petugas/delete/') ?>" + encodeURIComponent(petugasId);
                }
            });
        });
    });
</script>

<?= $this->include('admin/templates/footer') ?>