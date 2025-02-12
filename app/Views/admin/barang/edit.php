<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<h2>Edit Barang</h2>
<form action="<?= base_url('owner/barang/update/' . $barang['id']) ?>" method="post">
    <label>Nama Barang</label>
    <input type="text" name="nama_barang" value="<?= $barang['nama_barang']; ?>" class="form-control" required>

    <label>Kategori</label>
    <select name="id_kategori" class="form-control" required>
        <?php foreach ($kategori as $k) : ?>
            <option value="<?= $k['id']; ?>" <?= $barang['id_kategori'] == $k['id'] ? 'selected' : ''; ?>>
                <?= $k['nama_kategori']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Harga Beli</label>
    <input type="number" name="harga_beli" value="<?= $barang['harga_beli']; ?>" class="form-control" required>

    <button type="submit" class="btn btn-success mt-3">Update</button>
</form>

<?= $this->include('admin/templates/footer') ?>
