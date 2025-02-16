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
    <input type="number" name="harga_beli" id="harga_beli" value="<?= $barang['harga_beli']; ?>" class="form-control" required>

    <label>Harga Jual 1 (10% Markup)</label>
    <input type="number" id="harga_jual_1" value="<?= $barang['harga_jual_1']; ?>" class="form-control" readonly>

    <label>Harga Jual 2 (20% Markup)</label>
    <input type="number" id="harga_jual_2" value="<?= $barang['harga_jual_2']; ?>" class="form-control" readonly>

    <label>Harga Jual 3 (30% Markup)</label>
    <input type="number" id="harga_jual_3" value="<?= $barang['harga_jual_3']; ?>" class="form-control" readonly>

    <label>Minimal Stok</label>
    <input type="number" name="minimal_stok" id="minimal_stok" value="<?= $barang['minimal_stok']; ?>" class="form-control" required>

    <button type="submit" class="btn btn-success mt-3">Update</button>
</form>

<script>
    document.getElementById("harga_beli").addEventListener("input", function () {
        let hargaBeli = parseFloat(this.value) || 0;
        
        document.getElementById("harga_jual_1").value = hargaBeli + (hargaBeli * 0.10);
        document.getElementById("harga_jual_2").value = hargaBeli + (hargaBeli * 0.20);
        document.getElementById("harga_jual_3").value = hargaBeli + (hargaBeli * 0.30);
    });
</script>

<?= $this->include('admin/templates/footer') ?>
