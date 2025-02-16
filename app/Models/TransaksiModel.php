<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_detail_laporan',
        'kode_transaksi',
        'id_petugas',
        'id_member',
        'tipe_member',
        'total_belanja',
        'diskon',
        'poin_digunakan',
        'poin_didapat',
        'total_akhir',
        'total_bayar',
        'total_kembalian',
        'tanggal_transaksi'
    ];

    public function insertTransaction($data)
    {
        return $this->insert($data);
    }

    public function generateKodeTransaksi()
    {
        $lastTransaction = $this->orderBy('id', 'DESC')->first();

        if ($lastTransaction) {
            $lastKode = $lastTransaction['kode_transaksi'];
            $number = (int) substr($lastKode, 2) + 1;
        } else {
            $number = 1;
        }

        return 'KT' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function getSalesReportLastDay()
    {
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        return $this->select('laporan.kode_transaksi, barang.nama_barang, detail_laporan.jumlah, detail_laporan.total_harga')
            ->join('detail_laporan', 'detail_laporan.id_laporan = laporan.id', 'left')
            ->join('barang', 'barang.id = detail_laporan.barang_id', 'left')
            ->where('laporan.tanggal_transaksi >=', $yesterday) // Transactions from the last 1 day
            ->findAll();
    }
}
