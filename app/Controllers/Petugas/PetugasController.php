<?php

namespace App\Controllers\Petugas;

use CodeIgniter\Controller;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\TokoModel;
use App\Models\MemberModel;

class PetugasController extends Controller
{
    public function index()
    {

        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();
        $penjualanModel = new PenjualanModel();
        $tokoModel = new TokoModel();
        $memberModel = new MemberModel();

        // Get the current admin session data
        $id_member = session()->get('id_member');
        // Fetch member profile
        $hasil_profil = $id_member ? $memberModel->find($id_member) : null;

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


        return view('petugas/dashboard/index', $data);
    }
}
