<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class KategoriController extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data['kategori'] = $this->kategoriModel->getKategori();
        return view('admin/kategori/index', $data);
    }

    public function create()
    {
        $data['kode_kategori'] = $this->kategoriModel->generateKodeKategori(); // Ambil kode otomatis
        return view('admin/kategori/create', $data);
    }

    public function store()
    {
        $this->kategoriModel->save([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'kode_kategori' => $this->kategoriModel->generateKodeKategori(), // Gunakan kode otomatis
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('owner/kategori'))->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data['kategori'] = $this->kategoriModel->getKategori($id);
        return view('admin/kategori/edit', $data);
    }

    public function update($id)
    {
        $this->kategoriModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('owner/kategori'))->with('success', 'Kategori berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->kategoriModel->delete($id);
        return redirect()->to(base_url('owner/kategori'))->with('success', 'Kategori berhasil dihapus!');
    }
}
