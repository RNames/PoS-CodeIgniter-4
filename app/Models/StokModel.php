<?php

namespace App\Models;

use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table = 'stok';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_barang', 'stok', 'tanggal_beli', 'tanggal_expired'];

    // Fungsi untuk menambah stok dengan tanggal beli dan expired yang berbeda
    public function tambahStok($data)
    {
        return $this->insert($data);
    }
}
