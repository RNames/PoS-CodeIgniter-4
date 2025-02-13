<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\MemberModel;
use App\Models\BarangModel;

class TransaksiController extends BaseController
{
    public function index()
    {
        $memberModel = new MemberModel();
        $barangModel = new BarangModel();

        $data['members'] = $memberModel->findAll();
        $data['barangs'] = $barangModel->findAll();

        return view('admin/transaksi/index', $data);
    }

    public function proses()
    {
        $transaksiModel = new TransaksiModel();
        $detailModel = new DetailTransaksiModel();
        $barangModel = new BarangModel();
        $memberModel = new MemberModel();

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
        $barangUpdates = []; // Untuk update stok

        foreach ($barangList as $id_barang => $jumlah) {
            $barang = $barangModel->find($id_barang);
            if (!$barang || $jumlah <= 0) continue;

            // ✅ Cek stok tersedia
            if ($barang['stok'] < $jumlah) {
                return redirect()->back()->with('error', "Stok tidak cukup untuk {$barang['nama_barang']} (tersedia: {$barang['stok']})");
            }

            $harga_column = 'harga_jual_' . $tipe_member;
            if (!isset($barang[$harga_column])) {
                return redirect()->back()->with('error', "Harga untuk tipe member $tipe_member tidak ditemukan.");
            }

            $harga = floor($barang[$harga_column]);
            $total_harga = $harga * $jumlah;

            $detailTransaksi[] = [
                'barang_id' => $id_barang,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'total_harga' => $total_harga
            ];

            $total_belanja += $total_harga;

            // ✅ Siapkan update stok
            $barangUpdates[$id_barang] = $barang['stok'] - $jumlah;
        }

        $ppn = floor($total_belanja * 0.12);
        $total_setelah_ppn = floor($total_belanja + $ppn);
        $jumlah_diskon = floor(($diskon / 100) * $total_setelah_ppn);
        $total_akhir = floor($total_setelah_ppn - $jumlah_diskon);
        $total_kembalian = floor($total_bayar - $total_akhir);

        $poin = 0;
        if ($tipe_member == 1 || $tipe_member == 2) {
            $poin = floor($total_belanja * 0.02);
        }

        $transaksiData = [
            'id_detail_laporan' => 0,
            'id_petugas' => session()->get('id'),
            'id_member' => $id_member,
            'tipe_member' => $tipe_member,
            'total_belanja' => $total_belanja,
            'diskon' => $diskon,
            'poin_didapat' => $poin,
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

        // ✅ Update stok barang
        foreach ($barangUpdates as $id_barang => $newStock) {
            $barangModel->update($id_barang, ['stok' => $newStock]);
        }

        if ($poin > 0) {
            $member = $memberModel->find($id_member);
            if ($member) {
                $new_poin = $member['poin'] + $poin;
                $memberModel->update($id_member, ['poin' => $new_poin]);
            }
        }

        return redirect()->to(base_url('owner/transaksi'))->with('success', 'Transaksi berhasil disimpan. Poin bertambah: ' . number_format($poin, 0));
    }
}
