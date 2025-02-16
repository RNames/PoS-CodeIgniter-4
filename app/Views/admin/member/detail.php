<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/pengaturan-member') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Detail Member</h2>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
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
                    window.location.href = "<?= base_url('owner/pengaturan-member/delete/') ?>" + memberId;
                }
            });
        });
    });
</script>


<?= $this->include('admin/templates/footer') ?>