<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/kategori') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Tambah Kategori</h2>
        </div>
    </div>

    <form action="<?= base_url('owner/kategori/store') ?>" method="post">
        <div class="container mt-4 mb-4 row row-cols-2 g-3">
            <div class="form-group col">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" required>
            </div>
            <div class="form-group col">
                <label>Kode Kategori</label>
                <input type="text" name="kode_kategori" class="form-control" value="<?= $kode_kategori ?>" readonly>
            </div>
        </div>
        <button type="submit" class="btn btn-block btn-success">Simpan</button>
    </form>
</div>

<?= $this->include('admin/templates/footer') ?>