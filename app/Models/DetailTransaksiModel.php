<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table = 'detail_laporan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['barang_id', 'jumlah', 'harga', 'total_harga'];

    public function insertDetail($data)
    {
        return $this->insert($data);
    }
}
