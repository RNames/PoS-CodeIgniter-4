<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>
<h2>Daftar Kategori</h2>
<a href="<?= base_url('owner/kategori/create') ?>" class="btn btn-primary">Tambah Kategori</a>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Kode Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($kategori as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['kode_kategori'] ?></td>
                <td>
                    <a href="<?= base_url('owner/kategori/edit/' . $row['id']) ?>" class="btn btn-warning">Edit</a>
                    <a href="<?= base_url('owner/kategori/delete/' . $row['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->include('admin/templates/footer') ?>