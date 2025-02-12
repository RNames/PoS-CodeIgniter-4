<?php

namespace App\Controllers\Owner;

use CodeIgniter\Controller;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\TokoModel;
use App\Models\PetugasModel;

class AdminController extends Controller
{
    public function index()
    {
        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();
        $penjualanModel = new PenjualanModel();
        $tokoModel = new TokoModel();
        $petugasModel = new PetugasModel();

        // Get the current admin session data
        $id = session()->get('id');
        // Fetch member profile
        $hasil_profil = $id ? $petugasModel->find($id) : null;

        // Fetch dashboard data
        $data = [
            'low_stock' => $barangModel->where('stok <=', 3)->findAll(),
            'total_barang' => $barangModel->countAllResults(),
            'total_stok' => $barangModel->selectSum('stok')->get()->getRow()->stok,
            'total_penjualan' => $penjualanModel->selectSum('jumlah')->get()->getRow()->jumlah,
            'total_kategori' => $kategoriModel->countAllResults(),
            'toko' => $tokoModel->first(),
            'hasil_profil' => $hasil_profil, // Add profile data to the view
        ];
        
        return view('admin/dashboard/index', $data);
    }
}
