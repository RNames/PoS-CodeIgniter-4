<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/barang/detail/' . $stok['kode_barang']) ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Edit Stok Barang</h2>
        </div>
    </div>

    <form id="editStokForm" action="<?= base_url('owner/barang/updateStok/' . $stok['id']) ?>" method="post">
    <div class="container mt-4 mb-4 row row-cols-2 g-3">
        <div class="form-group col">
            <label>Nama Barang</label>
            <input type="text" class="form-control" value="<?= esc($barang['nama_barang']); ?>" readonly>
        </div>

        <div class="form-group col">
            <label>Jumlah Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= esc($stok['stok']); ?>" required>
        </div>
        
        <div class="form-group col">
            <label>Tanggal Beli</label>
            <input type="date" name="tanggal_beli" class="form-control" value="<?= esc($stok['tanggal_beli']); ?>" required>
        </div>

        <div class="form-group col">
            <label>Tanggal Expired</label>
            <input type="date" name="tanggal_expired" class="form-control" value="<?= esc($stok['tanggal_expired']); ?>" required>
        </div>

    </div>

        <button type="button" id="submitEdit" class="btn btn-primary btn-block">Simpan Perubahan</button>
    </form>
</div>

<?= $this->include('admin/templates/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('submitEdit').addEventListener('click', function(event) {
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan perubahan ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editStokForm').submit();
            }
        });
    });

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            title: "Berhasil!",
            text: "<?= session()->getFlashdata('success'); ?>",
            icon: "success",
            confirmButtonText: "OK"
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            title: "Gagal!",
            text: "<?= session()->getFlashdata('error'); ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
    <?php endif; ?>
</script>