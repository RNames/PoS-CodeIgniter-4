<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PetugasModel;
use App\Models\UserModel;
use App\Models\LogsModel;

class Loginctrl extends BaseController
{
    protected $logsModel;

    public function __construct()
    {
        $this->logsModel = new LogsModel(); // Inisialisasi logsModel
    }

    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('login/index');
    }

    public function process()
    {
        $petugas = new PetugasModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Ambil data petugas (termasuk yang sudah dihapus dengan soft delete)
        $dataUser = $petugas->getUserByEmail($email);

        if ($dataUser) {
            $id = $dataUser->id;

            // Ambil data petugas berdasarkan ID termasuk yang sudah dihapus (soft delete)
            $dataPetugas = $petugas->withDeleted()->find($id);

            // Pastikan data petugas ada (tidak null)
            if ($dataPetugas === null) {
                session()->setFlashdata('error', 'Data petugas tidak ditemukan.');
                return redirect()->back();
            }

            // Cek apakah akun telah dinonaktifkan (soft delete)
            if ($dataPetugas->deleted_at !== null) {
                session()->setFlashdata('status_error', 'Akun Anda telah dinonaktifkan! Hubungi admin untuk informasi lebih lanjut.');
                return redirect()->back(); // Hentikan proses lebih lanjut
            }

            // Verifikasi password jika akun tidak dinonaktifkan
            if (password_verify($password, $dataUser->password)) {
                session()->set([
                    'id'        => $dataUser->id,
                    'nama'      => $dataPetugas->nm_petugas,
                    'gambar'    => $dataPetugas->gambar,
                    'roles'     => $dataPetugas->roles,
                    'logged_in' => TRUE
                ]);

                // Simpan log login
                $this->logsModel->save([
                    'id_petugas' => $dataUser->id,
                    'action' => 'login',
                    'msg' => $dataPetugas->nm_petugas . ' berhasil login.',
                    'time' => date('Y-m-d H:i:s')
                ]);

                // Redirect berdasarkan role
                if ($dataPetugas->roles === 'owner') {
                    return redirect()->to(base_url('owner/dashboard'));
                } elseif ($dataPetugas->roles === 'petugas') {
                    return redirect()->to(base_url('petugas/dashboard'));
                }
            } else {
                session()->setFlashdata('error', 'Email & Password Salah');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('error', 'Email & Password Salah');
            return redirect()->back();
        }
    }

    public function logout()
    {
        // ✅ Simpan log logout sebelum session dihapus
        if (session()->get('id')) {
            $this->logsModel->save([
                'id_petugas' => session()->get('id'),
                'action' => 'logout',
                'msg' => session()->get('nama') . ' telah logout.',
                'time' => date('Y-m-d H:i:s')
            ]);
        }

        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
