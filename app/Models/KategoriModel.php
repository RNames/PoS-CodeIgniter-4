<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kategori', 'kode_kategori', 'created_at', 'updated_at'];

    // Fungsi untuk mendapatkan kode kategori terakhir dan menambahkan 1
    public function generateKodeKategori()
    {
        $lastKategori = $this->orderBy('id', 'DESC')->first();
        if ($lastKategori) {
            $lastNumber = (int) substr($lastKategori['kode_kategori'], 1); // Ambil angka dari K001
            $newNumber = $lastNumber + 1;
            return 'K' . str_pad($newNumber, 3, '0', STR_PAD_LEFT); // Format menjadi K001, K002, dll.
        }
        return 'K001'; // Jika belum ada kategori, mulai dari K001
    }

    public function getKategori($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }
        return $this->find($id);
    }
}
