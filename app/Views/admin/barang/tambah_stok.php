<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/barang') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Tambah Stok Barang</h2>
        </div>
    </div>
    <form id="stokForm" action="<?= base_url('owner/barang/tambahStok') ?>" method="post">
        <input type="hidden" name="kode_barang" value="<?= $barang['kode_barang'] ?>">

        <div class="container mt-3 mb-4 row row-cols-2 g-3">

            <div class="form-group col">
                <label>Nama Barang</label>
                <input type="text" class="form-control" value="<?= $barang['nama_barang'] ?>" readonly>
            </div>

            <div class="form-group col">
                <label>Jumlah Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>

            <div class="form-group col">
                <label>Tanggal Beli</label>
                <input type="date" name="tanggal_beli" class="form-control" required>
            </div>

            <div class="form-group col">
                <label>Tanggal Expired</label>
                <input type="date" name="tanggal_expired" class="form-control" required>
            </div>
        </div>

        <button type="button" id="btnSubmit" class="btn btn-success btn-block">Simpan</button>
    </form>
</div>

<!-- Tambahkan SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btnSubmit').addEventListener('click', function() {
        Swal.fire({
            title: 'Konfirmasi Tambah Stok',
            text: "Apakah Anda yakin ingin menambahkan stok ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('stokForm').submit();
            }
        });
    });
</script>

<?= $this->include('admin/templates/footer') ?>