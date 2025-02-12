<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>


<div class="container">
    <h2>Tambah Stok Barang</h2>
    <form action="<?= base_url('owner/barang/tambahStok') ?>" method="post">
        <input type="hidden" name="kode_barang" value="<?= $barang['kode_barang'] ?>">

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" class="form-control" value="<?= $barang['nama_barang'] ?>" readonly>
        </div>

        <div class="form-group">
            <label>Jumlah Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Tanggal Beli</label>
            <input type="date" name="tanggal_beli" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Tanggal Expired</label>
            <input type="date" name="tanggal_expired" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>

<?= $this->include('admin/templates/footer') ?>
