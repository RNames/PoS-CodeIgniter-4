<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_detail_laporan', 'id_petugas', 'id_member', 'tipe_member', 'total_belanja', 'diskon', 
        'poin_digunakan', 'poin_didapat', 'total_akhir', 'total_bayar', 'total_kembalian', 'tanggal_transaksi'
    ];

    public function insertTransaction($data)
    {
        return $this->insert($data);
    }
}
