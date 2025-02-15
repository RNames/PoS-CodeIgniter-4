<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_barang',
        'id_kategori',
        'nama_barang',
        'expired',
        'tanggal_beli',
        'harga_beli',
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'stok',
        'created_at',
        'updated_at',
        'status',
    ];

    public function generateIdBarang()
    {
        // Ambil ID terakhir
        $lastBarang = $this->orderBy('id', 'DESC')->first();

        if ($lastBarang) {
            // Ambil angka terakhir dari ID_barang (contoh: b005 -> 5)
            $lastNumber = intval(substr($lastBarang['kode_barang'], 1));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format menjadi bXXX
        return 'B' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getBarang($id = false)
    {
        if ($id === false) {
            return $this->select('barang.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                ->findAll();
        }
        return $this->find($id);
    }

    public function getBarangWithTotalStok()
    {
        return $this->select('barang.*, kategori.nama_kategori, 
            COALESCE(SUM(stok.stok), 0) AS total_stok')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->join('stok', 'stok.kode_barang = barang.kode_barang', 'left')
            ->groupBy('barang.kode_barang')
            ->findAll();
    }
}
