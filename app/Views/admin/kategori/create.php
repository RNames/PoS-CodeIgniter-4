<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>
<h2>Tambah Kategori</h2>
<form action="<?= base_url('owner/kategori/store') ?>" method="post">
    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Kode Kategori</label>
        <input type="text" name="kode_kategori" class="form-control" value="<?= $kode_kategori ?>" readonly>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="<?= base_url('owner/kategori') ?>" class="btn btn-secondary">Kembali</a>
</form>
<?= $this->include('admin/templates/footer') ?>
