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

        // Query dasar dengan join untuk mendapatkan nama kasir, pelanggan, diskon dalam rupiah, dan PPN
        $query = $this->transaksiModel
            ->select('laporan.*, 
                  petugas.nm_petugas as nama_kasir, 
                  member.nm_member as nama_member, 
                  (laporan.total_belanja * (laporan.diskon / 100)) as diskon_rp,
                  (laporan.total_belanja * 0.12) as ppn,
                  (laporan.total_belanja + (laporan.total_belanja * 0.12)) as total_setelah_ppn')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member');

        // Filter berdasarkan tanggal jika diberikan
        if (!empty($startDate) && !empty($endDate)) {
            $query->where('tanggal_transaksi >=', $startDate)
                ->where('tanggal_transaksi <=', $endDate);
        }

        $laporan = $query->findAll();

        return view('admin/laporan/index', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
