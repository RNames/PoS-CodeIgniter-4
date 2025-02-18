<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-5 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/barang') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i> Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Tambah Barang</h2>
        </div>
    </div>
    <form action="<?= base_url('owner/barang/store') ?>" method="post">
        <div class="container mt-4 mb-4 row row-cols-2 g-3">
            <div class="form-group col">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>

            <div class="form-group col">
                <label>Kategori</label>
                <select name="id_kategori" class="form-control" required>
                    <?php foreach ($kategori as $k) : ?>
                        <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control" required placeholder="Contoh: pcs, box, kg">
            </div>
            
            <div class="form-group col">
                <label>Tanggal Beli</label>
                <input type="date" name="tanggal_beli" class="form-control" required>
            </div>
            
            <div class="form-group col">
                <label>Harga Beli (HPP)</label>
                <input type="number" id="harga_beli" name="harga_beli" class="form-control" required oninput="hitungHargaJual()">
            </div>

            <div class="form-group col">
                <label>Tanggal Expired</label>
                <input type="date" name="tanggal_expired" class="form-control" required>
            </div>


            <div class="form-group col-12">
                <div class=" row row-cols-3 g-3">

                    <div class="form-group col">
                        <label>Harga Jual 1 (+10%)</label>
                        <input type="number" id="harga_jual_1" name="harga_jual_1" class="form-control" required readonly>
                    </div>

                    <div class="form-group col">
                        <label>Harga Jual 2 (+20%)</label>
                        <input type="number" id="harga_jual_2" name="harga_jual_2" class="form-control" required readonly>
                    </div>

                    <div class="form-group col">
                        <label>Harga Jual 3 (+30%)</label>
                        <input type="number" id="harga_jual_3" name="harga_jual_3" class="form-control" required readonly>
                    </div>
                </div>
            </div>

                <div class="form-group col">
                    <label>Minimal Stok</label>
                    <input type="number" id="minimal_stok" name="minimal_stok" class="form-control" required>
                </div>

                <div class="form-group col">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-block btn-success mt-3">Simpan</button>

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