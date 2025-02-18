<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\PetugasModel;
use App\Models\MemberModel;
use App\Models\DetailTransaksiModel;
use App\Models\BarangModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController extends BaseController
{
    protected $transaksiModel;
    protected $petugasModel;
    protected $memberModel;
    protected $detailTransaksiModel;
    protected $barangModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->petugasModel = new PetugasModel();
        $this->memberModel = new MemberModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        // Ambil filter dari input
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $kodeTransaksi = $this->request->getGet('kode_transaksi');
        $kasirId = $this->request->getGet('kasir_id'); // Filter untuk kasir

        // Query laporan transaksi
        $query = $this->transaksiModel
            ->select('laporan.*, 
                petugas.nm_petugas as nama_kasir, 
                member.nm_member as nama_member, 
                member.tipe_member, 
                laporan.kode_transaksi,
                ROUND((laporan.total_belanja * (laporan.diskon / 100)) / 100) * 100 AS diskon_rp,
                ROUND((laporan.total_belanja * 0.12) / 100) * 100 AS ppn,
                ROUND((laporan.total_belanja + (laporan.total_belanja * 0.12)) / 100) * 100 AS total_setelah_ppn,
                ROUND(laporan.total_akhir / 100) * 100 AS total_akhir
            ')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member');

        // Filter berdasarkan kasir jika dipilih
        if (!empty($kasirId)) {
            $query->where('laporan.id_petugas', $kasirId);
        }

        // Filter berdasarkan kode transaksi jika ada input
        if (!empty($kodeTransaksi)) {
            $query->where('laporan.kode_transaksi', $kodeTransaksi);
        }

        // Filter berdasarkan rentang tanggal jika ada input
        if (!empty($startDate) && !empty($endDate)) {
            $query->where('tanggal_transaksi >=', $startDate)
                ->where('tanggal_transaksi <=', $endDate);
        }

        // Urutkan dari transaksi terbaru ke terlama
        $query->orderBy('tanggal_transaksi', 'DESC');

        // Eksekusi query
        $laporan = $query->findAll();

        // Ambil daftar kasir untuk filter dropdown
        $kasirList = $this->petugasModel->select('id, nm_petugas')->findAll();

        return view('admin/laporan/index', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'kasirList' => $kasirList,
            'kasirId' => $kasirId,
        ]);
    }

    public function detail($id)
    {

        $transaksi = $this->transaksiModel
            ->select('laporan.*, 
              petugas.nm_petugas as nama_kasir, 
              member.nm_member as nama_member, 
              member.tipe_member, 
              ROUND((laporan.total_belanja * (laporan.diskon / 100)) / 100) * 100 AS diskon_rp,
              ROUND((laporan.total_belanja * 0.12) / 100) * 100 AS ppn,
              ROUND((laporan.total_belanja + (laporan.total_belanja * 0.12)) / 100) * 100 AS total_setelah_ppn,
              ROUND(laporan.total_akhir / 100) * 100 AS total_akhir,
              ROUND(laporan.total_bayar / 100) * 100 AS total_bayar_bulat,
              GREATEST(ROUND(laporan.total_bayar / 100) * 100 - ROUND(laporan.total_akhir / 100) * 100, 0) AS total_kembalian,
              laporan.poin_digunakan')
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

    // Inside the LaporanController

    public function penjualan($date = null)
    {
        // Get the search parameters
        $kodeTransaksi = $this->request->getGet('kode_transaksi');
        $date = $this->request->getGet('date');  // Get the date filter from the GET request

        $query = $this->detailTransaksiModel
            ->select('laporan.kode_transaksi, laporan.tanggal_transaksi, barang.nama_barang, detail_laporan.jumlah, detail_laporan.total_harga') // Tambahkan tanggal_transaksi
            ->join('laporan', 'laporan.id = detail_laporan.id_laporan')
            ->join('barang', 'barang.id = detail_laporan.barang_id');

        if ($date) {
            $query->where('DATE(laporan.tanggal_transaksi)', $date);
        }

        if (!empty($kodeTransaksi)) {
            $query->like('laporan.kode_transaksi', $kodeTransaksi);
        }

        $query->orderBy('laporan.kode_transaksi', 'DESC');


        $report = $query->findAll();

        // If no data is found, return a message
        if (empty($report)) {
            return view('admin/laporan/penjualan', ['message' => 'No sales data found.']);
        }

        // Pass the report, date, and kodeTransaksi to the view
        return view('admin/laporan/penjualan', [
            'report' => $report,
            'date' => $date,
            'kodeTransaksi' => $kodeTransaksi  // Pass the search term to the view
        ]);
    }

    private function getLaporanPenjualan()
    {
        $kodeTransaksi = $this->request->getGet('kode_transaksi');
        $date = $this->request->getGet('date');

        $query = $this->detailTransaksiModel
            ->select('laporan.kode_transaksi, laporan.tanggal_transaksi, barang.nama_barang, detail_laporan.jumlah, detail_laporan.total_harga')
            ->join('laporan', 'laporan.id = detail_laporan.id_laporan')
            ->join('barang', 'barang.id = detail_laporan.barang_id');

        if ($date) {
            $query->where('DATE(laporan.tanggal_transaksi)', $date);
        }

        if (!empty($kodeTransaksi)) {
            $query->like('laporan.kode_transaksi', $kodeTransaksi);
        }

        $query->orderBy('laporan.kode_transaksi', 'DESC');

        return $query->findAll();
    }

    public function exportPdf()
    {
        $startDate = $this->request->getGet('start_date'); // Ambil filter tanggal mulai
        $endDate = $this->request->getGet('end_date'); // Ambil filter tanggal akhir
        $kodeTransaksi = $this->request->getGet('kode_transaksi'); // Ambil filter kode transaksi

        $query = $this->detailTransaksiModel
            ->select('
        laporan.*, 
        petugas.nm_petugas as nama_petugas, 
        member.nm_member as nama_member, 
        member.tipe_member, 
        laporan.kode_transaksi,
        laporan.total_belanja,
        ROUND((laporan.total_belanja * laporan.diskon / 100) / 100) * 100 AS diskon_rupiah, 
        laporan.poin_digunakan,
        ROUND(laporan.total_akhir / 100) * 100 AS total_akhir
        ')
            ->join('laporan', 'laporan.id = detail_laporan.id_laporan')
            ->join('barang', 'barang.id = detail_laporan.barang_id')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member')
            ->groupBy('laporan.id');


        if (!empty($startDate) && !empty($endDate)) {
            $query->where('laporan.tanggal_transaksi >=', $startDate)
                ->where('laporan.tanggal_transaksi <=', $endDate);
        }

        if (!empty($kodeTransaksi)) {
            $query->like('laporan.kode_transaksi', $kodeTransaksi);
        }

        $query->orderBy('laporan.tanggal_transaksi', 'DESC');

        $data = [
            'laporan' => $query->findAll(),
            'startDate' => $startDate, // Kirim startDate ke view
            'endDate' => $endDate, // Kirim endDate ke view
        ];

        // Load tampilan laporan dalam bentuk HTML
        $html = view('admin/laporan_pdf', $data);

        // Konfigurasi Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_penjualan.pdf', ["Attachment" => false]);
    }
}
