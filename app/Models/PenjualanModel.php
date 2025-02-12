<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_penjualan';
    protected $allowedFields = [
        'id_barang',
        'id_member',
        'jumlah',
        'total',
        'tanggal_input'
    ];
}
