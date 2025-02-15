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
    ];

    // Add a custom method to fetch member data
    public function getMember($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getPetugas()
    {
        return $this->where('roles', 'petugas')->findAll();
    }

    public function getActivePetugas()
    {
        return $this->where('roles', 'petugas')->where('status', 1)->findAll();
    }

    public function getInactivePetugas()
    {
        return $this->where('roles', 'petugas')->where('status', 0)->findAll();
    }
}
