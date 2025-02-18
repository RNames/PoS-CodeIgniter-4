<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\KategoriModel;
use App\Models\LogsModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class BarangController extends BaseController
{
    protected $barangModel;
    protected $kategoriModel;
    protected $stokModel;
    protected $logsModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->kategoriModel = new KategoriModel();
        $this->stokModel = new StokModel();
        $this->logsModel = new LogsModel();
    }

    public function index()
    {
        $this->autoSoftDeleteStok();

        $data['barang'] = $this->barangModel->getBarangWithTotalStok();

        return view('admin/barang/index', $data);
    }


    public function create()
    {
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/barang/create', $data);
    }

    public function store()
    {
        $newIdBarang = $this->barangModel->generateIdBarang();
        $hargaBeli = (int) $this->request->getPost('harga_beli');

        $data = [
            'kode_barang'   => $newIdBarang,
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'satuan'        => $this->request->getPost('satuan'), // Tambahkan satuan
            'harga_beli'    => $hargaBeli,
            'harga_jual_1'  => $hargaBeli + ($hargaBeli * 0.10),
            'harga_jual_2'  => $hargaBeli + ($hargaBeli * 0.20),
            'harga_jual_3'  => $hargaBeli + ($hargaBeli * 0.30),
            'minimal_stok'  => $this->request->getPost('minimal_stok'),
            'created_at'    => date('Y-m-d H:i:s'),
        ];


        $this->barangModel->save($data);

        // Ambil data stok dari input form
        $stok = (int) $this->request->getPost('stok');
        $tanggal_beli = $this->request->getPost('tanggal_beli');
        $tanggal_expired = $this->request->getPost('tanggal_expired');

        if ($stok > 0) {
            $stokData = [
                'kode_barang'    => $newIdBarang,
                'stok'          => $stok,
                'tanggal_beli'  => $tanggal_beli,
                'tanggal_expired' => $tanggal_expired,
            ];

            // Simpan stok ke database
            $this->stokModel->insert($stokData);

            // Simpan log stok
            $this->logsModel->save([
                'id_petugas' => session()->get('id'),
                'action'     => 'tambah',
                'msg'        => "Menambahkan stok barang: " . $data['nama_barang'] . " sebanyak " . $stok,
                'old_data'   => null,
                'new_data'   => json_encode($stokData),
                'time'       => date('Y-m-d H:i:s')
            ]);
        }

        // Simpan log barang
        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'tambah',
            'msg'        => "Menambahkan barang: " . $data['nama_barang'],
            'old_data'   => null,
            'new_data'   => json_encode($data),
            'time'       => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Data barang berhasil diperbarui!');

        return redirect()->to(base_url('owner/barang'))->with('success', 'Barang dan stok berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data['barang'] = $this->barangModel->find($id);
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/barang/edit', $data);
    }

    public function update($id)
    {
        $oldData = $this->barangModel->find($id);

        if (!$oldData) {
            return redirect()->to(base_url('owner/barang'))->with('error', 'Barang tidak ditemukan!');
        }

        $hargaBeliBaru = (int) $this->request->getPost('harga_beli');

        // Data baru yang akan diperbarui
        $newData = [
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'satuan'        => $this->request->getPost('satuan'),
            'harga_beli'    => $hargaBeliBaru,
            'harga_jual_1'  => $hargaBeliBaru + ($hargaBeliBaru * 0.10), // 10% markup
            'harga_jual_2'  => $hargaBeliBaru + ($hargaBeliBaru * 0.20), // 20% markup
            'harga_jual_3'  => $hargaBeliBaru + ($hargaBeliBaru * 0.30), // 30% markup
            'minimal_stok'  => $this->request->getPost('minimal_stok'),
        ];

        $this->barangModel->update($id, $newData);

        // Simpan log perubahan
        $logOldData = [
            'nama_barang'   => $oldData['nama_barang'],
            'id_kategori'   => $oldData['id_kategori'],
            'satuan'        => $oldData['satuan'],
            'harga_beli'    => $oldData['harga_beli'],
            'harga_jual_1'  => $oldData['harga_jual_1'],
            'harga_jual_2'  => $oldData['harga_jual_2'],
            'harga_jual_3'  => $oldData['harga_jual_3'],
            'minimal_stok'  => $oldData['minimal_stok'],
        ];

        $logNewData = [
            'nama_barang'   => $newData['nama_barang'],
            'id_kategori'   => $newData['id_kategori'],
            'satuan'        => $newData['satuan'],
            'harga_beli'    => $newData['harga_beli'],
            'harga_jual_1'  => $newData['harga_jual_1'],
            'harga_jual_2'  => $newData['harga_jual_2'],
            'harga_jual_3'  => $newData['harga_jual_3'],
            'minimal_stok'  => $newData['minimal_stok'],
        ];

        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'edit',
            'msg'        => "Mengedit barang: {$oldData['nama_barang']}",
            'old_data'   => json_encode($logOldData),
            'new_data'   => json_encode($logNewData),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/barang'))->with('success', 'Barang berhasil diperbarui!');
    }


    public function delete($id)
    {
        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return redirect()->to(base_url('owner/barang'))->with('error', 'Barang tidak ditemukan!');
        }

        $oldData = [
            'kode_barang'   => $barang['kode_barang'],
            'nama_barang'   => $barang['nama_barang'],
            'harga_beli'    => $barang['harga_beli'],
            'harga_jual_1'  => $barang['harga_jual_1'],
            'harga_jual_2'  => $barang['harga_jual_2'],
            'harga_jual_3'  => $barang['harga_jual_3'],
        ];

        $this->barangModel->delete($id);

        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'hapus',
            'msg'        => "Menghapus barang: " . $barang['nama_barang'],
            'old_data'   => null,
            'new_data'   => json_encode(['deleted_at' => $barang['deleted_at']]),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/barang'))->with('success', 'Barang berhasil dihapus!');
    }

    public function cetakPDF()
    {
        // Ambil semua data barang
        $barang = $this->barangModel->getBarangWithTotalStok();

        // Load HTML tampilan ke dalam variabel
        $data['barang'] = $barang;
        $html = view('admin/barang/cetak_pdf', $data);

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->setIsRemoteEnabled(true); // Untuk mengaktifkan gambar dari URL

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF ke browser
        $dompdf->stream('Daftar_Barang.pdf', ['Attachment' => false]);
    }

    public function tambahStokForm($kode_barang)
    {
        $data['barang'] = $this->barangModel->where('kode_barang', $kode_barang)->first();

        if (!$data['barang']) {
            return redirect()->to(base_url('owner/barang'))->with('error', 'Barang tidak ditemukan!');
        }

        return view('admin/barang/tambah_stok', $data);
    }

    // Fungsi untuk menambah stok
    public function tambahStok()
    {
        $kode_barang = $this->request->getPost('kode_barang');
        $stokTambahan = (int) $this->request->getPost('stok');
        $tanggal_beli = $this->request->getPost('tanggal_beli');
        $tanggal_expired = $this->request->getPost('tanggal_expired');

        // Ambil semua stok lama berdasarkan kode_barang (stok lama dengan tanggal berbeda)
        $stokLamaData = $this->stokModel->where('kode_barang', $kode_barang)
            ->select('stok, tanggal_expired')
            ->findAll();

        // Format stok lama ke dalam array untuk log
        $stokLamaLog = [];
        $totalStokLama = 0;
        foreach ($stokLamaData as $stok) {
            $stokLamaLog[] = [
                'stok' => $stok['stok'],
                'tanggal_expired' => $stok['tanggal_expired']
            ];
            $totalStokLama += $stok['stok']; // Total stok sebelum penambahan
        }

        // Simpan data stok baru
        $this->stokModel->insert([
            'kode_barang'    => $kode_barang,
            'stok'          => $stokTambahan,
            'tanggal_beli'  => $tanggal_beli,
            'tanggal_expired' => $tanggal_expired
        ]);

        // Ambil total stok baru setelah penambahan
        $totalStokBaru = $totalStokLama + $stokTambahan;

        // Simpan log penambahan stok
        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'tambah',
            'msg'        => "Menambahkan stok untuk barang: $kode_barang.",
            'old_data'   => json_encode([
                'total_stok' => $totalStokLama
            ]),
            'new_data'   => json_encode([
                'stok_ditambahkan' => $stokTambahan,
                'tanggal_beli' => $tanggal_beli,
                'tanggal_expired' => $tanggal_expired,
                'total_stok' => $totalStokBaru
            ]),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/barang'))->with('success', 'Stok berhasil ditambahkan!');
    }

    public function detail($kode_barang)
    {
        $barang = $this->barangModel
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->where('barang.kode_barang', $kode_barang)
            ->first();

        if (!$barang) {
            return redirect()->to(base_url('owner/barang'))->with('error', 'Barang tidak ditemukan!');
        }

        // Ambil stok berdasarkan kode_barang
        $stokModel = new \App\Models\StokModel();
        $stokBarang = $stokModel->where('kode_barang', $kode_barang)->findAll();

        $data = [
            'barang' => $barang,
            'stokBarang' => $stokBarang
        ];

        return view('admin/barang/detail', $data);
    }

    public function editStokForm($id_stok)
    {
        $stok = $this->stokModel->find($id_stok);

        if (!$stok) {
            return redirect()->to(base_url('owner/barang'))->with('error', 'Stok tidak ditemukan!');
        }

        $data = [
            'stok' => $stok,
            'barang' => $this->barangModel->where('kode_barang', $stok['kode_barang'])->first(),
        ];

        return view('admin/barang/edit_stok', $data);
    }

    public function updateStok($id)
    {
        $stok = $this->stokModel->find($id);

        if (!$stok) {
            return redirect()->to(base_url('owner/barang'))->with('error', 'Stok tidak ditemukan!');
        }

        $kode_barang = $stok['kode_barang']; // Ambil kode barang dari data stok

        $newData = [
            'stok' => $this->request->getPost('stok'),
            'tanggal_beli' => $this->request->getPost('tanggal_beli'),
            'tanggal_expired' => $this->request->getPost('tanggal_expired'),
        ];

        $this->stokModel->update($id, $newData);

        // Tambahkan log perubahan stok
        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'edit',
            'msg'        => "Mengedit stok barang ID: " . $id,
            'old_data'   => json_encode($stok),
            'new_data'   => json_encode($newData),
            'time'       => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Stok barang berhasil diperbarui!');
        return redirect()->to('/owner/barang/detail/' . $kode_barang);
    }

    public function deleteStok($id)
    {
        $stok = $this->stokModel->find($id);

        if (!$stok) {
            return redirect()->back()->with('error', 'Stok tidak ditemukan!');
        }

        $this->stokModel->delete($id);

        // Tambahkan log penghapusan stok
        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'hapus',
            'msg'        => "Menghapus stok barang dengan ID: " . $id,
            'old_data'   => json_encode($stok),
            'new_data'   => null,
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Stok berhasil dihapus!');
    }

    public function autoSoftDeleteStok()
    {
        // Ambil semua barang yang masih aktif
        $barangList = $this->barangModel->findAll();

        foreach ($barangList as $barang) {

            $stokList = $this->stokModel->findAll();

            foreach ($stokList as $stok) {
                // Cek apakah stok habis
                if ($stok['stok'] <= 0) {
                    // Soft delete stok yang habis
                    $this->stokModel->delete($stok['id']);

                    // Simpan log penghapusan stok karena habis
                    $this->logsModel->save([
                        'id_petugas' => session()->get('id'),
                        'action'     => 'Hapus',
                        'msg'        => "Stok dengan kode barang '{$stok['kode_barang']}' telah dihapus (soft delete) karena stok habis.",
                        'old_data'   => json_encode($stok),
                        'new_data'   => json_encode(['deleted_at' => date('Y-m-d H:i:s')]),
                        'time'       => date('Y-m-d H:i:s')
                    ]);
                }

                // Cek apakah ada stok yang sudah expired
                $expiredStok = $this->stokModel->where('kode_barang', $barang['kode_barang'])
                    ->where('tanggal_expired <', date('Y-m-d'))
                    ->findAll();

                if (!empty($expiredStok)) {
                    foreach ($expiredStok as $stok) {
                        // Soft delete stok yang expired
                        $this->stokModel->delete($stok['id']);

                        // Simpan log penghapusan stok karena expired
                        $this->logsModel->save([
                            'id_petugas' => session()->get('id'),
                            'action'     => 'Hapus',
                            'msg'        => "Stok dengan kode barang '{$stok['kode_barang']}' telah dihapus (soft delete) karena expired.",
                            'old_data'   => json_encode($stok),
                            'new_data'   => json_encode(['deleted_at' => date('Y-m-d H:i:s')]),
                            'time'       => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }
    }
}
