<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <h2>Edit Barang</h2>
    <a href="<?= base_url('owner/barang') ?>" class="btn btn-secondary">Kembali</a>
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

        <label>Satuan</label>
        <input type="text" name="satuan" value="<?= esc($barang['satuan']); ?>" class="form-control" required>

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

        <button type="button" id="btn-update" class="btn btn-success mt-3">Update</button>
    </form>
</div>

<script>
    document.getElementById("harga_beli").addEventListener("input", function() {
        let hargaBeli = parseFloat(this.value) || 0;

        document.getElementById("harga_jual_1").value = hargaBeli + (hargaBeli * 0.10);
        document.getElementById("harga_jual_2").value = hargaBeli + (hargaBeli * 0.20);
        document.getElementById("harga_jual_3").value = hargaBeli + (hargaBeli * 0.30);
    });

    document.getElementById("btn-update").addEventListener("click", function() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan diperbarui!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector("form").submit();
            }
        });
    });

    // Notifikasi sukses setelah update
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success'); ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>
</script>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success'); ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
<?php endif; ?>


<?= $this->include('admin/templates/footer') ?>