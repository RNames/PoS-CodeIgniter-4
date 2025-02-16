<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table      = 'member';
    protected $primaryKey = 'id';

    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'nm_member',
        'email',
        'no_hp',
        'alamat',
        'poin',
        'tipe_member',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // Add this line for the soft delete field

    public function softDelete($id)
    {
        return $this->update($id, ['status' => 0]);
    }
}
