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
        // Ambil ID petugas yang login dari sesi
        $idPetugas = session()->get('id');

        if (!$idPetugas) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil input filter dari GET request
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $kodeTransaksi = $this->request->getGet('kode_transaksi');

        $query = $this->transaksiModel
            ->select('laporan.*, 
              petugas.nm_petugas as nama_kasir, 
              member.nm_member as nama_member, 
              member.tipe_member, 
              laporan.kode_transaksi,
              ROUND((laporan.total_belanja * (laporan.diskon / 100)) / 100) * 100 AS diskon_rp,
              ROUND((laporan.total_belanja * 0.12) / 100) * 100 AS ppn,
              ROUND((laporan.total_belanja + (laporan.total_belanja * 0.12)) / 100) * 100 AS total_setelah_ppn,
              ROUND(laporan.total_akhir / 100) * 100 AS total_akhir,
              ')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member')
            ->where('laporan.id_petugas', $idPetugas);

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
        // Ambil ID petugas yang login dari sesi
        $idPetugas = session()->get('id');

        if (!$idPetugas) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
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
        ')
        ->join('petugas', 'petugas.id = laporan.id_petugas')
        ->join('member', 'member.id = laporan.id_member')
        ->where('laporan.id', $id)
        ->where('laporan.id_petugas', $idPetugas)
        ->first();
    
    

        if (!$transaksi) {
            return redirect()->to(base_url('petugas/laporan'))->with('error', 'Transaksi tidak ditemukan atau tidak diizinkan.');
        }

        // Ambil detail transaksi (barang yang dibeli)
        $detailTransaksi = $this->detailTransaksiModel
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
        $idPetugas = session()->get('id');

        if (!$idPetugas) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $kodeTransaksi = $this->request->getGet('kode_transaksi');

        $query = $this->transaksiModel
            ->select('
        laporan.*, 
        petugas.nm_petugas as nama_kasir, 
        member.nm_member as nama_member, 
        member.tipe_member, 
        laporan.kode_transaksi,
        laporan.total_belanja,
        ROUND((laporan.total_belanja * laporan.diskon / 100) / 100) * 100 AS diskon_rupiah, 
        laporan.poin_digunakan,
        ROUND(laporan.total_akhir / 100) * 100 AS total_akhir
    ')
            ->join('petugas', 'petugas.id = laporan.id_petugas')
            ->join('member', 'member.id = laporan.id_member')
            ->where('laporan.id_petugas', $idPetugas);



        if (!empty($kodeTransaksi)) {
            $query->where('laporan.kode_transaksi', $kodeTransaksi);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('tanggal_transaksi >=', $startDate)
                ->where('tanggal_transaksi <=', $endDate);
        }

        $query->orderBy('tanggal_transaksi', 'DESC');

        $data['laporan'] = $query->findAll();
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        // Load tampilan laporan dalam bentuk HTML
        $html = view('petugas/laporan_pdf', $data);

        // Konfigurasi Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_transaksi.pdf', ["Attachment" => false]);
    }
}
