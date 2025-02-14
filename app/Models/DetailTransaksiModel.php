<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table = 'detail_laporan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_laporan','barang_id', 'jumlah', 'harga', 'total_harga','created_at','updated_at'];

    public function insertDetail($data)
    {
        return $this->insert($data);
    }

    public function getDetailWithBarang($id_transaksi)
{
    return $this->select('detail_laporan.*, barang.nama_barang')
        ->join('barang', 'barang.kode_barang = detail_laporan.barang_id', 'left')
        ->where('detail_laporan.id_laporan', $id_transaksi)
        ->findAll();
}

}
