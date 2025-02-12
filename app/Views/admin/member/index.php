<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Daftar Member</h2>
    <a style="margin-bottom: 10px;" href="<?= base_url('owner/member/create') ?>" class="btn btn-primary">Tambah Member</a>
    <table class="table">

        <tr>
            <th class="text-center">ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th class="text-center">No HP</th>
            <th class="text-center">Poin</th>
            <th class="text-center">Tipe Member</th>
            <th class="text-center">Aksi</th>
        </tr>

        <tbody>
            <?php foreach ($members as $m) : ?>
                <tr>
                    <td class="text-center"><?= esc($m['id']) ?></td>
                    <td><?= esc($m['nm_member']) ?></td>
                    <td><?= esc($m['email']) ?></td>
                    <td class="text-center"><?= esc($m['no_hp']) ?></td>
                    <td class="text-center"><?= esc($m['poin']) ?></td>
                    <td class="text-center"><?= esc($m['tipe_member']) ?></td>
                    <td class="text-center">
                        <a href="<?= base_url('owner/member/detail/' . $m['id']) ?>" class="btn btn-info">
                            <i class='fas fa-eye' style='font-size:20px'></i> Detail
                        </a>
                        <a href="<?= base_url('owner/member/edit/' . $m['id']) ?>" class="btn btn-warning">
                            <i class='fas fa-edit' style='font-size:20px'></i> Edit
                        </a>
                        <button class="deleteBtn btn btn-danger" data-id="<?= $m['id'] ?>">
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
                    window.location.href = "<?= base_url('owner/member/delete/') ?>" + memberId;
                }
            });
        });
    });
</script>

<?= $this->include('admin/templates/footer') ?>