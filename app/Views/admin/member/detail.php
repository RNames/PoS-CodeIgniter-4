<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Detail Member</h2>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td><?= esc($member['id']) ?></td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td><?= esc($member['nm_member']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= esc($member['email']) ?></td>
                </tr>
                <tr>
                    <th>No HP</th>
                    <td><?= esc($member['no_hp']) ?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td><?= esc($member['alamat']) ?></td>
                </tr>
                <tr>
                    <th>Poin</th>
                    <td><?= esc($member['poin']) ?></td>
                </tr>
                <tr>
                    <th>Tipe Member</th>
                    <td><?= esc($member['tipe_member']) ?></td>
                </tr>
            </table>
            <a href="<?= base_url('owner/member') ?>" class="btn btn-secondary">
                Kembali
            </a>
            <a href="<?= base_url('owner/member/edit/' . $member['id']) ?>" class="btn btn-warning">
                Edit
            </a>
            <button class="deleteBtn btn btn-danger" data-id="<?= $member['id'] ?>">
                Hapus
            </button>
        </div>
    </div>
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