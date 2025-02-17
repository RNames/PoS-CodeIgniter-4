<?= $this->include('petugas/templates/header') ?>
<?= $this->include('petugas/templates/sidebar') ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">
    <h2>Daftar Member</h2>
    <div class="d-flex gap-2 mb-4">
        <a href="<?= base_url('petugas/pengaturan-member/create') ?>" class="btn btn-primary">Tambah Member</a>
        <a href="<?= base_url('petugas/pengaturan-member/nonaktif') ?>" class="btn btn-secondary">Lihat Member Nonaktif</a>
    </div>
    <table class="table">

        <tr>
            <th >Nama</th>
            <th class="text-center">Email</th>
            <th class="text-center">No HP</th>
            <th class="text-center">Poin</th>
            <th class="text-center">Tipe Member</th>
            <th class="text-center">Aksi</th>
        </tr>

        <tbody>
            <?php foreach ($members as $m) : ?>
                <tr>
                    <td><?= esc($m['nm_member']) ?></td>
                    <td class="text-center" ><?= esc($m['email']) ?></td>
                    <td class="text-center"><?= esc($m['no_hp']) ?></td>
                    <td class="text-center"><?= esc($m['poin']) ?></td>
                    <td class="text-center"><?= esc($m['tipe_member']) ?></td>
                    <td class="text-center">
                        <a href="<?= base_url('petugas/pengaturan-member/detail/' . $m['id']) ?>" class="btn btn-primary">
                            <i class='fas fa-info-circle' style='font-size:20px'></i> Detail
                        </a>
                        <a href="<?= base_url('petugas/pengaturan-member/edit/' . $m['id']) ?>" class="btn btn-warning">
                            <i class='fas fa-edit' style='font-size:20px'></i> Edit
                        </a>
                        <button class="deleteBtn btn btn-danger" data-id="<?= $m['id'] ?>">
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
                    window.location.href = "<?= base_url('petugas/pengaturan-member/delete/') ?>" + memberId;
                }
            });
        });
    });
</script>

<?= $this->include('petugas/templates/footer') ?>