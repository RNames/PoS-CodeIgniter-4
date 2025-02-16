<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kategori',
        'kode_kategori',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $useSoftDeletes = true; // Aktifkan soft delete
    protected $deletedField = 'deleted_at';

    // Fungsi untuk mendapatkan kode kategori terakhir dan menambahkan 1
    public function generateKodeKategori()
    {
        $lastKategori = $this->orderBy('id', 'DESC')->first();
        if ($lastKategori) {
            $lastNumber = (int) substr($lastKategori['kode_kategori'], 1);
            $newNumber = $lastNumber + 1;
            return 'K' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }
        return 'K001';
    }

    public function getKategori($id = false, $includeDeleted = false)
    {
        if ($id === false) {
            $query = $this;
            if (!$includeDeleted) {
                $query = $query->where('deleted_at', null);
            }
            return $query->findAll();
        }

        return $this->find($id);
    }

    // Fungsi untuk mengembalikan kategori yang dihapus (restore)
    public function restoreKategori($id)
    {
        return $this->update($id, ['deleted_at' => null]);
    }
}
