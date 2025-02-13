<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\MemberModel;
use App\Models\BarangModel;
use App\Models\StokModel;

class TransaksiController extends BaseController
{
    public function index()
    {
        $barangModel = new BarangModel();
        $memberModel = new MemberModel();

        $data['barangs'] = $barangModel->getBarangWithTotalStok(); // Mengambil stok dari tabel stok
        $data['members'] = $memberModel->findAll();

        return view('admin/transaksi/index', $data);
    }

    public function proses()
    {
        $transaksiModel = new TransaksiModel();
        $detailModel = new DetailTransaksiModel();
        $barangModel = new BarangModel();
        $memberModel = new MemberModel();
        $stokModel = new StokModel();

        $id_member = $this->request->getPost('id_member');
        $tipe_member = $this->request->getPost('tipe_member');
        $diskon = (float) $this->request->getPost('diskon');
        $total_bayar = (int) $this->request->getPost('total_bayar');
        $barangList = $this->request->getPost('jumlah');

        if (!$barangList) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu barang!');
        }

        $total_belanja = 0;
        $detailTransaksi = [];

        foreach ($barangList as $id_barang => $jumlah) {
            $barang = $barangModel->find($id_barang);
            if (!$barang || $jumlah <= 0) continue;

            // Ambil harga sesuai tipe member
            $harga_column = 'harga_jual_' . $tipe_member;
            if (!isset($barang[$harga_column])) {
                return redirect()->back()->with('error', "Harga untuk tipe member $tipe_member tidak ditemukan.");
            }
            $harga = floor($barang[$harga_column]);

            // Kurangi stok dari tabel stok berdasarkan FIFO
            $sisa_jumlah = $jumlah;
            $stokEntries = $stokModel->where('kode_barang', $barang['kode_barang'])
                ->orderBy('tanggal_beli', 'ASC')
                ->findAll();

            foreach ($stokEntries as $stok) {
                if ($sisa_jumlah <= 0) break;

                $stok_terpakai = min($stok['stok'], $sisa_jumlah);
                $stokModel->update($stok['id'], ['stok' => $stok['stok'] - $stok_terpakai]);
                $sisa_jumlah -= $stok_terpakai;
            }

            if ($sisa_jumlah > 0) {
                return redirect()->back()->with('error', "Stok barang {$barang['nama_barang']} tidak mencukupi!");
            }

            // Simpan detail transaksi
            $total_harga = $harga * $jumlah;
            $detailTransaksi[] = [
                'barang_id' => $id_barang,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'total_harga' => $total_harga
            ];

            $total_belanja += $total_harga;
        }

        // Hitung total setelah pajak dan diskon
        $ppn = floor($total_belanja * 0.12);
        $total_setelah_ppn = floor($total_belanja + $ppn);
        $jumlah_diskon = floor(($diskon / 100) * $total_setelah_ppn);
        $total_akhir = floor($total_setelah_ppn - $jumlah_diskon);
        $total_kembalian = floor($total_bayar - $total_akhir);

        // Simpan transaksi utama
        $transaksiData = [
            'id_detail_laporan' => 0,
            'id_petugas' => session()->get('id'),
            'id_member' => $id_member,
            'tipe_member' => $tipe_member,
            'total_belanja' => $total_belanja,
            'diskon' => $diskon,
            'total_akhir' => $total_akhir,
            'total_bayar' => $total_bayar,
            'total_kembalian' => $total_kembalian,
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        ];

        $transaksiModel->insert($transaksiData);
        $transaksi_id = $transaksiModel->getInsertID();

        foreach ($detailTransaksi as &$detail) {
            $detail['id_laporan'] = $transaksi_id;
            $detailModel->insert($detail);
        }

        return redirect()->to(base_url('owner/transaksi'))->with('success', 'Transaksi berhasil disimpan.');
    }
}
