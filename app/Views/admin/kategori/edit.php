<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/kategori') ?>" class="btn btn-secondary">
                <i class="fas fa-fw fa-angle-left"></i> Kembali
            </a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Edit Kategori</h2>
        </div>
    </div>
    
    <form id="updateForm" action="<?= base_url('owner/kategori/update/' . $kategori['id']) ?>" method="post">
        <div class="container mt-4 mb-4 row row-cols-2 g-3">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="<?= $kategori['nama_kategori'] ?>" required>
            </div>
            <div class="form-group">
                <label>Kode Kategori</label>
                <input type="text" name="kode_kategori" class="form-control" value="<?= $kategori['kode_kategori'] ?>" readonly>
            </div>
        </div>
        <button type="button" class="btn btn-block btn-success" id="btnUpdate">Update</button>
    </form>
</div>

<!-- Tambahkan SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("btnUpdate").addEventListener("click", function() {
    Swal.fire({
        title: "Konfirmasi Update",
        text: "Apakah Anda yakin ingin mengupdate kategori ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Update!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("updateForm").submit();
        }
    });
});
</script>

<?= $this->include('admin/templates/footer') ?>
