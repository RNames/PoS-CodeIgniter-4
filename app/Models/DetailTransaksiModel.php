<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table = 'detail_laporan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_laporan','barang_id', 'jumlah', 'harga', 'total_harga','created_at','updated_at'];

    public function insertDetail($data)
    {
        return $this->insert($data);
    }
}
