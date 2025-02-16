<?php

namespace App\Models;

use CodeIgniter\Model;

class PetugasModel extends Model
{
    protected $table = 'petugas'; // Name of the database table
    protected $primaryKey = 'id'; // Primary key of the table
    protected $returnType = 'object';
    protected $allowedFields = [
        'nm_petugas',
        'email',
        'gambar',
        'roles',
        'password',
        'created_at',
        'updated_at',
        'status',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    // Add a custom method to fetch member data
    public function getMember($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->withDeleted()->where('email', $email)->first();
    }


    public function getPetugas()
    {
        return $this->where('roles', 'petugas')->findAll();
    }
}
