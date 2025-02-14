<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            margin: 20px;
        }

        hr {
            border: none;
            border-top: 2px solid black;
            margin: 10px 0;
        }

        .container {
            max-width: 400px;
            margin: auto;
            text-align: left;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
        }

        .info {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .items {
            width: 100%;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .items th,
        .items td {
            padding: 4px 0;
        }

        .items th {
            text-align: left;
        }

        .summary {
            font-size: 14px;
            margin-top: 10px;
        }

        .right {
            float: right;
        }

        .btn-print {
            display: block;
            margin: 20px auto;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <p class="right"><?= date('d/m/Y, h:i A') ?></p>
        <p class="right">Kasir Dashboard</p>
        <h2 class="left">Nota Transaksi</h2>
        <p><strong>Kode Transaksi:</strong> <?= $transaksi['kode_transaksi'] ?></p>
        <hr>

        <p class="info"><strong>Pelanggan:</strong> <?= $member ? $member['nm_member'] : 'Umum' ?></p>
        <p class="info"><strong>Tanggal:</strong> <?= date('Y-m-d H:i:s', strtotime($transaksi['tanggal_transaksi'])) ?></p>

        <table class="items">
            <tbody>
                <?php foreach ($details as $item) : ?>
                    <tr>
                        <td><?= isset($item['nama_barang']) ? $item['nama_barang'] : 'Barang Tidak Ditemukan' ?></td>
                        <td class="right">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="right">x<?= $item['jumlah'] ?> &nbsp; Rp <?= number_format($item['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr>

        <p class="summary"><strong>Total Belanja:</strong> <span class="right">Rp <?= number_format($transaksi['total_belanja'], 0, ',', '.') ?></span></p>
        <p class="summary"><strong>Diskon:</strong> <span class="right"><?= $transaksi['diskon'] ?>%</span></p>
        <p class="summary"><strong>Total Akhir (PPN 12%):</strong> <span class="right">Rp <?= number_format($transaksi['total_akhir'], 0, ',', '.') ?></span></p>
        <p class="summary"><strong>Uang Dibayar:</strong> <span class="right">Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></span></p>
        <p class="summary"><strong>Kembalian:</strong> <span class="right">Rp <?= number_format($transaksi['total_kembalian'], 0, ',', '.') ?></span></p>

        <button class="btn-print" onclick="window.print()">Cetak</button>
    </div>
</body>

</html>