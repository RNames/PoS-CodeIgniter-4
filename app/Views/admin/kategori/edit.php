<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>
<h2>Edit Kategori</h2>
<form action="<?= base_url('owner/kategori/update/'.$kategori['id']) ?>" method="post">
    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" value="<?= $kategori['nama_kategori'] ?>" required>
    </div>
    <div class="form-group">
        <label>Kode Kategori</label>
        <input type="text" name="kode_kategori" class="form-control" value="<?= $kategori['kode_kategori'] ?>" readonly>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="<?= base_url('owner/kategori') ?>" class="btn btn-secondary">Kembali</a>
</form>
<?= $this->include('admin/templates/footer') ?>
