<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>
<div class="container">
    <h3>Daftar Petugas Nonaktif</h3>
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
            <?php foreach ($inactivePetugas as $p) : ?>
                <tr>
                    <td><?= $p->id ?></td>
                    <td><?= $p->nm_petugas ?></td>
                    <td><?= $p->email ?></td>
                    <td><?= $p->roles ?></td>
                    <td>
                        <button class="restoreBtn btn btn-success" data-id="<?= $p->id ?>">
                            <i class='fas fa-undo' style='font-size:20px'></i> Aktifkan
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Konfirmasi sebelum memulihkan petugas
    document.querySelectorAll('.restoreBtn').forEach(button => {
        button.addEventListener('click', function() {
            let petugasId = this.getAttribute('data-id');
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin memulihkan petugas ini?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Pulihkan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/pengaturan-petugas/restore/') ?>" + petugasId;
                }
            });
        });
    });
</script>
<?= $this->include('admin/templates/footer') ?>