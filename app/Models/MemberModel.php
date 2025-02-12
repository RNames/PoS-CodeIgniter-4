<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'member'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = [
        'nm_member',
        'email',
        'no_hp',
        'alamat',
        'poin',
        'tipe_member',
        'created_at',
        'updated_at'
    ]; // Kolom yang bisa diisi
}
