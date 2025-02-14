<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        .btn-print { margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Nota Transaksi</h2>
    <p><strong>Kode Transaksi:</strong> <?= $transaksi['kode_transaksi'] ?></p> <!-- Ganti ID dengan Kode Transaksi -->
    <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?></p>
    
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $item) : ?>
                <tr>
                    <td><?= $item['barang_id'] ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= number_format($item['total_harga'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Total Akhir:</strong> Rp <?= number_format($transaksi['total_akhir'], 0, ',', '.') ?></p>
    <button class="btn-print" onclick="window.print()">Cetak</button>
</body>
</html>
