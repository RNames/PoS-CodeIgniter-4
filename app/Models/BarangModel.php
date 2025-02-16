<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'kode_barang',
        'id_kategori',
        'nama_barang',
        'expired',
        'tanggal_beli',
        'harga_beli',
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'minimal_stok',
        'stok',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $stokModel;

    public function __construct()
    {
        parent::__construct();
        $this->stokModel = new StokModel();  // Instantiate StokModel
    }

    public function generateIdBarang()
    {
        $lastBarang = $this->orderBy('id', 'DESC')->first();

        if ($lastBarang) {
            // Ambil angka terakhir dari ID_barang (contoh: b005 -> 5)
            $lastNumber = intval(substr($lastBarang['kode_barang'], 1));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        return 'B' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getBarang($id = false)
    {
        if ($id === false) {
            return $this->select('barang.*, kategori.nama_kategori')
                ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                ->findAll();
        }
        return $this->find($id);
    }

    public function getBarangWithTotalStok()
    {
        // Fetch data from barang and kategori tables
        $barangData = $this->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->findAll();

        foreach ($barangData as &$barang) {
            // Use StokModel to get the total stock for each barang
            $barang['total_stok'] = $this->stokModel->getTotalStok($barang['kode_barang']);
        }

        return $barangData;
    }
}
