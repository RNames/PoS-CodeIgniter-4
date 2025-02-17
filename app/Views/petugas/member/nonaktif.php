<?= $this->include('petugas/templates/header') ?>
<?= $this->include('petugas/templates/sidebar') ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">
    <h2>Daftar Member Nonaktif</h2>
    <a style="margin-bottom: 10px;" href="<?= base_url('petugas/pengaturan-member') ?>" class="btn btn-secondary">Kembali ke Member Aktif</a>
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
                    window.location.href = "<?= base_url('petugas/pengaturan-member/restore/') ?>" + memberId;
                }
            });
        });
    });
</script>

<?= $this->include('petugas/templates/footer') ?>
