<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\PetugasModel;
use App\Models\TokoModel;
use App\Models\LogsModel;

class SetPetugasController extends BaseController
{
    protected $petugasModel;
    protected $tokoModel;
    protected $logsModel;

    public function __construct()
    {
        $this->petugasModel = new PetugasModel();
        $this->tokoModel = new TokoModel();
        $this->logsModel = new LogsModel();
    }
    public function index()
    {
        $petugasModel = new PetugasModel();

        // Ambil hanya petugas aktif (status = 1) dan roles = petugas
        $activePetugas = $petugasModel->where('status', 1)->where('roles', 'petugas')->findAll();

        // Ambil hanya petugas nonaktif (status = 0) dan roles = petugas
        $inactivePetugas = $petugasModel->where('status', 0)->where('roles', 'petugas')->findAll();

        $data = [
            'activePetugas'   => $activePetugas,
            'inactivePetugas' => $inactivePetugas
        ];

        // You can conditionally load this based on the request or provide links to both active and inactive views
        return view('admin/set_petugas/index', $data);
    }

    public function nonaktif()
    {
        $petugasModel = new PetugasModel();

        // Ambil hanya petugas nonaktif (status = 0) dan roles = petugas
        $inactivePetugas = $petugasModel->where('status', 0)->where('roles', 'petugas')->findAll();

        $data = [
            'inactivePetugas' => $inactivePetugas
        ];

        return view('admin/set_petugas/nonaktif', $data);
    }

    public function create()
    {
        $data['toko'] = $this->tokoModel->first();
        return view('admin/set_petugas/create', $data);
    }

    public function store()
    {
        $email = $this->request->getPost('email');
        $nm_petugas = $this->request->getPost('nm_petugas');

        if ($this->petugasModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar!')->withInput();
        }

        if ($this->petugasModel->where('nm_petugas', $nm_petugas)->first()) {
            return redirect()->back()->with('error', 'Nama Petugas sudah terdaftar!')->withInput();
        }

        $data = [
            'nm_petugas'  => $nm_petugas,
            'email'      => $email,
            'roles'      => 'petugas',
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->petugasModel->save($data);

        unset($data['password']); // Jangan simpan password di logs

        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'tambah',
            'msg'        => 'Menambahkan petugas: ' . $nm_petugas,
            'old_data'   => null,
            'new_data'   => json_encode($data),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/petugas'))->with('success', 'Petugas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = [
            'petugas' => $this->petugasModel->find($id),
            'toko' => $this->tokoModel->first()
        ];
        return view('admin/set_petugas/edit', $data);
    }

    public function update($id)
    {
        $email = $this->request->getPost('email');
        $existingUser = $this->petugasModel->where('email', $email)->where('id !=', $id)->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Email sudah digunakan oleh petugas lain!')->withInput();
        }

        $oldData = $this->petugasModel->find($id);
        $newData = [
            'nm_petugas'  => $this->request->getPost('nm_petugas'),
            'email'       => $email,
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $newData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->petugasModel->update($id, $newData);

        // Perbaiki akses properti dari object ke array sebelum menyimpannya di logs
        $logOldData = [
            'nm_petugas' => $oldData->nm_petugas,
            'email'      => $oldData->email
        ];

        $logNewData = [
            'nm_petugas' => $newData['nm_petugas'],
            'email'      => $newData['email']
        ];

        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'edit',
            'msg'        => "Mengedit petugas: {$oldData->nm_petugas} menjadi {$newData['nm_petugas']}",
            'old_data'   => json_encode($logOldData),
            'new_data'   => json_encode($logNewData),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/petugas'))->with('success', 'Petugas berhasil diperbarui!');
    }

    public function delete($id)
    {
        $petugas = $this->petugasModel->find($id);

        if (!$petugas) {
            return redirect()->to(base_url('owner/petugas'))->with('error', 'Petugas tidak ditemukan!');
        }

        // Simpan data lama untuk log
        $oldData = [
            'nm_petugas' => $petugas->nm_petugas,
            'email'      => $petugas->email
        ];

        // Soft delete: Update status menjadi 0
        $this->petugasModel->update($id, ['status' => 0]);

        // Simpan log penghapusan
        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'hapus',
            'msg'        => 'Menonaktifkan petugas: ' . $petugas->nm_petugas,
            'old_data'   => json_encode($oldData),
            'new_data'   => json_encode(['status' => 0]),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/petugas'))->with('success', 'Petugas berhasil dinonaktifkan!');
    }

    public function restore($id)
    {
        $petugas = $this->petugasModel->find($id);

        if (!$petugas) {
            return redirect()->to(base_url('owner/petugas'))->with('error', 'Petugas tidak ditemukan!');
        }

        // Update status menjadi 1 (aktif kembali)
        $this->petugasModel->update($id, ['status' => 1]);

        // Simpan log pemulihan
        $this->logsModel->save([
            'id_petugas' => session()->get('id'),
            'action'     => 'restore',
            'msg'        => 'Mengaktifkan kembali petugas: ' . $petugas->nm_petugas,
            'old_data'   => json_encode(['status' => 0]),
            'new_data'   => json_encode(['status' => 1]),
            'time'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('owner/petugas/nonaktif'))->with('success', 'Petugas berhasil diaktifkan kembali!');
    }
}
