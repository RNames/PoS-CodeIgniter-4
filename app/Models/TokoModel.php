<?php

namespace App\Models;

use CodeIgniter\Model;

class TokoModel extends Model
{
    protected $table = 'toko'; // Name of the table
    protected $primaryKey = 'id_toko'; // Primary key column

    protected $allowedFields = ['nama_toko', 'alamat_toko', 'tlp', 'nama_pemilik']; // Columns that can be inserted/updated

    // If you want to use automatic timestamps (optional)
    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
}
