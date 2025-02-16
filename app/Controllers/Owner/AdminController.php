<?php

namespace App\Controllers\Owner;

use CodeIgniter\Controller;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\TokoModel;
use App\Models\PetugasModel;
use App\Models\DetailTransaksiModel;
use App\Models\TransaksiModel;


class AdminController extends Controller
{
    public function index()
    {
        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();
        $transaksiModel = new TransaksiModel();
        $detailTransaksiModel = new DetailTransaksiModel();
        $tokoModel = new TokoModel();
        $petugasModel = new PetugasModel();

        // Get the current admin session data
        $id = session()->get('id');
        // Fetch member profile
        $hasil_profil = $id ? $petugasModel->find($id) : null;

        // Fetch all barang with total stok
        $barang_data = $barangModel->getBarangWithTotalStok();

        // Filter barang where total_stok is less than minimal_stok
        $low_stock = array_filter($barang_data, function ($barang) {
            return $barang['total_stok'] < $barang['minimal_stok'];
        });

        // Fetch dashboard data
        $data = [
            'low_stock' => $low_stock,  // Use the filtered low_stock data
            'total_barang' => $barangModel->countAllResults(),
            'total_stok' => array_sum(array_column($barang_data, 'total_stok')),
            'total_penjualan' => $detailTransaksiModel->selectSum('jumlah')->get()->getRow()->jumlah,
            'total_kategori' => $kategoriModel->countAllResults(),
            'toko' => $tokoModel->first(),
            'hasil_profil' => $hasil_profil,
            'barang_data' => $barang_data,  // Pass the barang data with total_stok
        ];

        // Get sales data for the last 7 days
        $lastWeekSales = $detailTransaksiModel->select('laporan.kode_transaksi, laporan.tanggal_transaksi, detail_laporan.total_harga')
            ->join('laporan', 'laporan.id = detail_laporan.id_laporan')
            ->where('laporan.tanggal_transaksi >=', date('Y-m-d H:i:s', strtotime('-1 week')))
            ->orderBy('laporan.tanggal_transaksi', 'DESC')
            ->findAll();

        // Pass the last 7 days sales data to the view
        $data['lastWeekSales'] = $lastWeekSales;

        $startDate = date('Y-m-d', strtotime('-6 days')); // 6 hari ke belakang + hari ini
        $endDate = date('Y-m-d');

        $allDates = [];
        $salesData = [];

        for ($date = strtotime($startDate); $date <= strtotime($endDate); $date += 86400) {
            $formattedDate = date('Y-m-d', $date);
            $allDates[$formattedDate] = 0; // Default 0 jika tidak ada transaksi
        }

        // Ambil data transaksi dari database
        $sales = $detailTransaksiModel
            ->select('laporan.tanggal_transaksi, SUM(detail_laporan.total_harga) as total_harga')
            ->join('laporan', 'laporan.id = detail_laporan.id_laporan')
            ->where('laporan.tanggal_transaksi >=', $startDate)
            ->groupBy('laporan.tanggal_transaksi')
            ->orderBy('laporan.tanggal_transaksi', 'ASC')
            ->findAll();

        // Masukkan data transaksi ke array
        foreach ($sales as $sale) {
            $dateKey = date('Y-m-d', strtotime($sale['tanggal_transaksi']));
            $allDates[$dateKey] = (int) $sale['total_harga'];
        }

        // Kirim data ke view
        $data['sales_dates'] = json_encode(array_keys($allDates));
        $data['sales_totals'] = json_encode(array_values($allDates));

        return view('admin/dashboard/index', $data);
    }
}
