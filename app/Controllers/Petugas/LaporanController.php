<?php

namespace App\Controllers\Petugas;

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
        // Ambil input filter dari GET request
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $kodeTransaksi = $this->request->getGet('kode_transaksi');

        // Query dengan join untuk mendapatkan data laporan dengan detail
        $query = $this->transaksiModel
            ->select('laporan.*, 
              petugas.nm_petugas as nama_kasir, 
              member.nm_member as nama_member, 
              member.tipe_member, 
              laporan.kode_transaksi,
              (laporan.total_belanja * (laporan.diskon / 100)) as diskon_rp,
              (laporan.total_belanja * 0.12) as ppn,
              (laporan.total_belanja + (laporan.total_belanja * 0.12)) as total_setelah_ppn')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member');

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

        // Eksekusi query dan ambil data
        $laporan = $query->findAll();

        return view('petugas/laporan/index', [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
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
            return redirect()->to(base_url('petugas/laporan'))->with('error', 'Transaksi tidak ditemukan.');
        }

        // Ambil detail transaksi (barang yang dibeli)
        $detailModel = new \App\Models\DetailTransaksiModel();
        $barangModel = new \App\Models\BarangModel();

        $detailTransaksi = $detailModel
            ->select('detail_laporan.*, barang.kode_barang, barang.nama_barang')
            ->join('barang', 'barang.id = detail_laporan.barang_id')
            ->where('detail_laporan.id_laporan', $id)
            ->findAll();



        return view('petugas/laporan/detail', [
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
            return view('petugas/laporan/penjualan', ['message' => 'No sales data found.']);
        }

        // Pass the report, date, and kodeTransaksi to the view
        return view('petugas/laporan/penjualan', [
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
        $date = $this->request->getGet('date'); // Ambil filter tanggal
        $kodeTransaksi = $this->request->getGet('kode_transaksi'); // Ambil filter kode transaksi

        $query = $this->detailTransaksiModel
            ->select('laporan.kode_transaksi, laporan.tanggal_transaksi, barang.nama_barang, detail_laporan.jumlah, detail_laporan.total_harga')
            ->join('laporan', 'laporan.id = detail_laporan.id_laporan')
            ->join('barang', 'barang.id = detail_laporan.barang_id');

        if (!empty($date)) {
            $query->where('DATE(laporan.tanggal_transaksi)', $date);
        }

        if (!empty($kodeTransaksi)) {
            $query->like('laporan.kode_transaksi', $kodeTransaksi);
        }

        $query->orderBy('laporan.tanggal_transaksi', 'DESC');

        $data['report'] = $query->findAll();
        $data['date'] = $date; // Kirim tanggal ke view

        // Load tampilan laporan dalam bentuk HTML
        $html = view('petugas/laporan_pdf', $data);

        // Konfigurasi Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream('laporan_penjualan.pdf', ["Attachment" => false]);
    }
}
