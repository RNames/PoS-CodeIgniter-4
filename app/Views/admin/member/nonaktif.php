<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Daftar Member Nonaktif</h2>
    <a style="margin-bottom: 10px;" href="<?= base_url('owner/pengaturan-member') ?>" class="btn btn-secondary">Kembali ke Member Aktif</a>
    <table class="table">

        <tr>
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
                    <td><?= esc($m['nm_member']) ?></td>
                    <td><?= esc($m['email']) ?></td>
                    <td class="text-center"><?= esc($m['no_hp']) ?></td>
                    <td class="text-center"><?= esc($m['poin']) ?></td>
                    <td class="text-center"><?= esc($m['tipe_member']) ?></td>
                    <td class="text-center">
                        <button class="restoreBtn btn btn-success" data-id="<?= $m['id'] ?>">
                            <i class='fas fa-undo' style='font-size:20px'></i> Aktifkan
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.querySelectorAll('.restoreBtn').forEach(button => {
        button.addEventListener('click', function() {
            let memberId = this.getAttribute('data-id');
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin mengaktifkan kembali member ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Aktifkan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('owner/pengaturan-member/restore/') ?>" + memberId;
                }
            });
        });
    });
</script>

<?= $this->include('admin/templates/footer') ?>
