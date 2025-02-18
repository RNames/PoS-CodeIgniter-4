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
        $poin_digunakan = (int) $this->request->getPost('poin_digunakan');

        if (!$barangList) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu barang!');
        }

        $member = $memberModel->find($id_member);
        $total_poin_member = $member ? (int)$member['poin'] : 0;

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
                'total_harga' => $total_harga,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $total_belanja += $total_harga;
        }

        //  1. Hitung Diskon (dalam Rupiah)
        $jumlah_diskon = floor(($diskon / 100) * $total_belanja);

        //  2. Hitung Subtotal Setelah Diskon
        $subtotal = $total_belanja - $jumlah_diskon;

        //  3. Hitung PPN (12% dari subtotal)
        $ppn = floor($subtotal * 0.12);

        // 4. Hitung Total Akhir (Subtotal + PPN)
        $total_akhir = $subtotal + $ppn;

        // 5. Kurangkan total akhir dengan poin yang digunakan
        if ($poin_digunakan > $total_akhir) {
            $poin_digunakan = $total_akhir; // Gunakan hanya poin sebesar total transaksi
        }

        $total_akhir -= $poin_digunakan; //Mengurangi total akhir dengan poin yang digunakan (jika ada)

        // 6. Hitung Kembalian
        $total_kembalian = $total_bayar - $total_akhir;

        // 7. Hitung Poin (Hanya untuk Tipe 1 dan 2)
        $poin_didapat = 0;
        if ($tipe_member == 1 || $tipe_member == 2) {
            $poin_didapat = floor($total_belanja * 0.02); // 2% dari total sebelum diskon & pajak
        }

        // 8. Update Poin Member
        $poin_akhir = $total_poin_member - $poin_digunakan + $poin_didapat;

        // ✅ Generate Kode Transaksi
        $kode_transaksi = $transaksiModel->generateKodeTransaksi();

        $transaksiData = [
            'kode_transaksi' => $kode_transaksi, // ✅ Tambahkan kode transaksi
            'id_petugas' => session()->get('id'),
            'id_member' => $id_member,
            'tipe_member' => $tipe_member,
            'total_belanja' => $total_belanja,
            'diskon' => $diskon,
            'diskon_rp' => $jumlah_diskon,
            'ppn' => $ppn,
            'poin_didapat' => $poin_didapat,
            'poin_digunakan' => $poin_digunakan,
            'total_akhir' => $total_akhir,
            'total_bayar' => $total_bayar,
            'total_kembalian' => $total_kembalian,
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // ✅ Simpan transaksi
        $transaksiModel->insert($transaksiData);
        $transaksi_id = $transaksiModel->getInsertID();


        // ✅ Simpan Detail Transaksi dengan ID yang Sama
        foreach ($detailTransaksi as $detail) {
            $detail['id_laporan'] = $transaksi_id;
            $detailModel->insert([
                'id_laporan'  => $transaksi_id, // Menggunakan ID Transaksi sebagai id_laporan
                'barang_id'   => $detail['barang_id'],
                'jumlah'      => $detail['jumlah'],
                'harga'       => $detail['harga'],
                'total_harga' => $detail['total_harga'],
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        }

        $memberModel->update($id_member, ['poin' => $poin_akhir]);

        // ✅ Update id_detail_laporan di tabel laporan agar sesuai dengan transaksi_id
        $transaksiModel->update($transaksi_id, ['id_detail_laporan' => $transaksi_id]);


        // ✅ Update Stok Barang Sesuai FIFO
        foreach ($detailTransaksi as $detail) {
            $id_barang = $detail['barang_id'];
            $jumlah_dibeli = $detail['jumlah'];

            // ✅ Ambil data barang berdasarkan ID
            $barangData = $barangModel->find($id_barang);
            if (!$barangData) continue; // ✅ Jika barang tidak ditemukan, skip transaksi ini

            $stokModel = new \App\Models\StokModel();
            $stokList = $stokModel->where('kode_barang', $barangData['kode_barang']) // ✅ Gunakan kode_barang yang benar
                ->where('stok >', 0)
                ->orderBy('tanggal_beli', 'ASC')
                ->findAll();

            foreach ($stokList as $stok) {
                if ($jumlah_dibeli <= 0) break;

                if ($stok['stok'] >= $jumlah_dibeli) {
                    // Jika stok cukup, langsung kurangi
                    $stokModel->update($stok['id'], [
                        'stok' => $stok['stok'] - $jumlah_dibeli
                    ]);
                    break;
                } else {
                    // Jika stok tidak cukup, habiskan stok ini, lanjut ke stok berikutnya
                    $jumlah_dibeli -= $stok['stok'];
                    $stokModel->update($stok['id'], [
                        'stok' => 0
                    ]);
                }
            }
        }

        session()->setFlashdata('transaksi_berhasil', [
            'message' => 'Transaksi berhasil disimpan. Poin bertambah: ' . number_format($poin_didapat, 0),
            'id_transaksi' => $transaksi_id
        ]);

        return redirect()->to(base_url('owner/transaksi'));
    }

    public function cetak_nota($id)
    {
        $transaksiModel = new TransaksiModel();
        $detailModel = new DetailTransaksiModel();
        $memberModel = new MemberModel();

        $transaksi = $transaksiModel->find($id);
        if (!$transaksi) {
            return redirect()->to(base_url('owner/transaksi'))->with('error', 'Transaksi tidak ditemukan.');
        }

        $details = $detailModel->getDetailWithBarang($id);
        $member = $memberModel->find($transaksi['id_member']);

        $data = [
            'transaksi' => $transaksi,
            'details' => $details,
            'member' => $member
        ];

        return view('admin/transaksi/cetak_nota', $data);
    }
}
