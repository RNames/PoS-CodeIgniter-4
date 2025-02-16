<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">LAPORAN PENJUALAN</h1>
    <p style="text-align: center;"><?= isset($date) ? 'Tanggal: ' . date('d-m-Y', strtotime($date)) : 'All Time'; ?></p>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Barang</th>
                <th>Jumlah Terjual</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($report)): ?>
                <?php foreach ($report as $item): ?>
                    <tr>
                        <td><?= $item['kode_transaksi']; ?></td>
                        <td><?= date('d-m-Y', strtotime($item['tanggal_transaksi'])); ?></td>
                        <td><?= $item['nama_barang']; ?></td>
                        <td><?= $item['jumlah']; ?></td>
                        <td><?= number_format($item['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data untuk tanggal ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>