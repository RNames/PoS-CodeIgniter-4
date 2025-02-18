<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Daftar Barang</h2>

    <table>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Tanggal Expired</th>
            <th>Satuan</th>
            <th>Harga Jual 1</th>
            <th>Harga Jual 2</th>
            <th>Harga Jual 3</th>
            <th>Min. Stok</th>
            <th>Stok</th>
        </tr>
        <?php foreach ($barang as $b) : ?>
            <tr>
                <td><?= esc($b['kode_barang']); ?></td>
                <td><?= esc($b['nama_barang']); ?></td>
                <td><?= esc($b['nama_kategori']); ?></td>
                <td><?= esc($b['expired']); ?></td>
                <td><?= esc($b['satuan']); ?></td>
                <td>Rp. <?= number_format($b['harga_jual_1'], 0, ',', '.'); ?></td>
                <td>Rp. <?= number_format($b['harga_jual_2'], 0, ',', '.'); ?></td>
                <td>Rp. <?= number_format($b['harga_jual_3'], 0, ',', '.'); ?></td>
                <td><?= esc($b['minimal_stok']); ?></td>
                <td><?= esc($b['total_stok']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
