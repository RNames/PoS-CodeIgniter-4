<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Tambah Barang</h2>
    <form action="<?= base_url('owner/barang/store') ?>" method="post">
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" required>

        <label>Kategori</label>
        <select name="id_kategori" class="form-control" required>
            <?php foreach ($kategori as $k) : ?>
                <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
            <?php endforeach; ?>
        </select>

        <label>Tanggal Expired</label>
        <input type="date" name="tanggal_expired" class="form-control" required>

        <label>Tanggal Beli</label>
        <input type="date" name="tanggal_beli" class="form-control" required>

        <label>Harga Beli (HPP)</label>
        <input type="number" id="harga_beli" name="harga_beli" class="form-control" required oninput="hitungHargaJual()">

        <label>Harga Jual 1 (+10%)</label>
        <input type="number" id="harga_jual_1" name="harga_jual_1" class="form-control" required readonly>

        <label>Harga Jual 2 (+20%)</label>
        <input type="number" id="harga_jual_2" name="harga_jual_2" class="form-control" required readonly>

        <label>Harga Jual 3 (+30%)</label>
        <input type="number" id="harga_jual_3" name="harga_jual_3" class="form-control" required readonly>

        <label>Stok</label>
        <input type="number" name="stok" class="form-control" required>

        <button type="submit" class="btn btn-success mt-3">Simpan</button>
    </form>
</div>
<script>
    function hitungHargaJual() {
        let hpp = parseFloat(document.getElementById('harga_beli').value) || 0;

        document.getElementById('harga_jual_1').value = Math.round(hpp * 1.10);
        document.getElementById('harga_jual_2').value = Math.round(hpp * 1.20);
        document.getElementById('harga_jual_3').value = Math.round(hpp * 1.30);
    }
</script>

<?= $this->include('admin/templates/footer') ?>