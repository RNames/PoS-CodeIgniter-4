<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\PetugasModel;
use App\Models\MemberModel;

class LaporanController extends BaseController
{
    protected $transaksiModel;
    protected $petugasModel;
    protected $memberModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->petugasModel = new PetugasModel();
        $this->memberModel = new MemberModel();
    }

    public function index()
    {
        // Ambil input filter dari GET request
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $idLaporan = $this->request->getGet('id_laporan'); // Ambil ID Laporan dari input

        // Query dengan join untuk mendapatkan data laporan dengan detail
        $query = $this->transaksiModel
            ->select('laporan.*, 
                  petugas.nm_petugas as nama_kasir, 
                  member.nm_member as nama_member, 
                  member.tipe_member, 
                  (laporan.total_belanja * (laporan.diskon / 100)) as diskon_rp,
                  (laporan.total_belanja * 0.12) as ppn,
                  (laporan.total_belanja + (laporan.total_belanja * 0.12)) as total_setelah_ppn')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member');

        // Filter berdasarkan ID Laporan
        if (!empty($idLaporan)) {
            $query->where('laporan.id', $idLaporan);
        }

        // Filter berdasarkan rentang tanggal jika ada input
        if (!empty($startDate) && !empty($endDate)) {
            $query->where('tanggal_transaksi >=', $startDate)
                ->where('tanggal_transaksi <=', $endDate);
        }

        // Urutkan dari transaksi terbaru ke terlama
        $query->orderBy('tanggal_transaksi', 'DESC');

        // Eksekusi query dan ambil data
        $laporan = $query->findAll();

        return view('admin/laporan/index', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'idLaporan' => $idLaporan
        ]);
    }

    public function detail($id)
    {
        // Ambil data transaksi berdasarkan ID
        $transaksi = $this->transaksiModel
            ->select('laporan.*, 
                  petugas.nm_petugas as nama_kasir, 
                  member.nm_member as nama_member, 
                  member.tipe_member, 
                  (laporan.total_belanja * (laporan.diskon / 100)) as diskon_rp,
                  (laporan.total_belanja * 0.12) as ppn,
                  (laporan.total_belanja + (laporan.total_belanja * 0.12)) as total_setelah_ppn')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member')
            ->where('laporan.id', $id)
            ->first();

        if (!$transaksi) {
            return redirect()->to(base_url('owner/laporan'))->with('error', 'Transaksi tidak ditemukan.');
        }

        // Ambil detail transaksi (barang yang dibeli)
        $detailModel = new \App\Models\DetailTransaksiModel();
        $barangModel = new \App\Models\BarangModel();

        $detailTransaksi = $detailModel
            ->select('detail_laporan.*, barang.kode_barang, barang.nama_barang')
            ->join('barang', 'barang.id = detail_laporan.barang_id')
            ->where('detail_laporan.id_laporan', $id)
            ->findAll();



        return view('admin/laporan/detail', [
            'transaksi' => $transaksi,
            'detailTransaksi' => $detailTransaksi
        ]);
    }
}
