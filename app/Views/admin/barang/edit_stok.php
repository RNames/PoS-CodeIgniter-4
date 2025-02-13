<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Edit Stok Barang</h2>
    <a href="<?= base_url('owner/barang/detail/' . $stok['kode_barang']) ?>" class="btn btn-secondary">Kembali</a>

    <form id="editStokForm" action="<?= base_url('owner/barang/updateStok/' . $stok['id']) ?>" method="post">
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" class="form-control" value="<?= esc($barang['nama_barang']); ?>" readonly>
        </div>

        <div class="form-group">
            <label>Tanggal Beli</label>
            <input type="date" name="tanggal_beli" class="form-control" value="<?= esc($stok['tanggal_beli']); ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal Expired</label>
            <input type="date" name="tanggal_expired" class="form-control" value="<?= esc($stok['tanggal_expired']); ?>" required>
        </div>

        <div class="form-group">
            <label>Jumlah Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= esc($stok['stok']); ?>" required>
        </div>

        <button type="button" id="submitEdit" class="btn btn-primary">Simpan Perubahan</button>
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
