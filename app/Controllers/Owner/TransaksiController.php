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

            $harga_column = 'harga_jual_' . $tipe_member;
            if (!isset($barang[$harga_column])) {
                return redirect()->back()->with('error', "Harga untuk tipe member $tipe_member tidak ditemukan.");
            }

            $harga = floor($barang[$harga_column]); // Membulatkan harga ke bawah
            $total_harga = $harga * $jumlah;

            $detailTransaksi[] = [
                'barang_id' => $id_barang,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'total_harga' => $total_harga
            ];

            $total_belanja += $total_harga;
        }

        // ✅ 1. Hitung Diskon (dalam Rupiah)
        $jumlah_diskon = floor(($diskon / 100) * $total_belanja);

        // ✅ 2. Hitung Subtotal Setelah Diskon
        $subtotal = $total_belanja - $jumlah_diskon;

        // ✅ 3. Hitung PPN (12% dari subtotal)
        $ppn = floor($subtotal * 0.12);

        // ✅ 4. Hitung Total Akhir (Subtotal + PPN)
        $total_akhir = $subtotal + $ppn;

        // ✅ 5. Hitung Kembalian
        $total_kembalian = $total_bayar - $total_akhir;

        // ✅ 6. Hitung Poin (Hanya untuk Tipe 1 dan 2)
        $poin = 0;
        if ($tipe_member == 1 || $tipe_member == 2) {
            $poin = floor($total_belanja * 0.02); // 2% dari total sebelum diskon & pajak
        }

        // ✅ Simpan transaksi tanpa id_detail_laporan dulu
        $transaksiData = [
            'id_detail_laporan' => 0,
            'id_petugas' => session()->get('id'),
            'id_member' => $id_member,
            'tipe_member' => $tipe_member,
            'total_belanja' => $total_belanja,
            'diskon' => $diskon,
            'diskon_rp' => $jumlah_diskon, // ✅ Simpan diskon dalam rupiah
            'ppn' => $ppn, // ✅ Simpan PPN
            'poin_didapat' => $poin, // ✅ Simpan poin ke database
            'total_akhir' => $total_akhir,
            'total_bayar' => $total_bayar,
            'total_kembalian' => $total_kembalian,
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $transaksiModel->insert($transaksiData);
        $transaksi_id = $transaksiModel->getInsertID();

        foreach ($detailTransaksi as &$detail) {
            $detail['id_laporan'] = $transaksi_id;
            $detailModel->insert($detail);
        }

        // ✅ Update id_detail_laporan dengan ID yang benar
        $detail_id = $detailModel->getInsertID();
        $transaksiModel->update($transaksi_id, ['id_detail_laporan' => $detail_id]);

        // ✅ Update Poin Member
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
