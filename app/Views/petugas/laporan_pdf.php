<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2 class="title">Laporan Transaksi</h2>
    <?php if ($startDate && $endDate) : ?>
        <p>Periode: <?= esc($startDate) ?> s/d <?= esc($endDate) ?></p>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Nama Kasir</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Tipe Pelanggan</th>
                <th>Total Belanja</th>
                <th>Diskon (Rp)</th>
                <th>Poin Digunakan</th>
                <th>Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($laporan as $row) : ?>
                <tr>
                    <td><?= esc($row['kode_transaksi']) ?></td>
                    <td><?= esc($row['nama_kasir']) ?></td>
                    <td><?= esc($row['tanggal_transaksi']) ?></td>
                    <td><?= esc($row['nama_member']) ?></td>
                    <td><?= esc($row['tipe_member']) ?></td>
                    <td>Rp <?= number_format($row['total_belanja'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['diskon_rupiah'], 0, ',', '.') ?></td>
                    <td><?= esc($row['poin_digunakan']) ?></td>
                    <td>Rp <?= number_format($row['total_akhir'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>