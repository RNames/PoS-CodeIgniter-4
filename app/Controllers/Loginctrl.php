<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PetugasModel;
use App\Models\MemberModel;
use App\Models\LogsModel;

class Loginctrl extends BaseController
{
    protected $logsModel;
    protected $memberModel;

    public function __construct()
    {
        $this->logsModel = new LogsModel(); // Inisialisasi logsModel
        $this->memberModel = new MemberModel(); // Inisialisasi memberModel
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
        $member = $this->memberModel; // Instance MemberModel
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Check if user is a petugas first
        $dataUser = $petugas->getUserByEmail($email);

        // If user is not found in petugas model, check if they are a member
        if (!$dataUser) {
            $dataUser = $member->where('email', $email)->first();
            if ($dataUser) {
                // Member login, display access restriction message
                session()->setFlashdata('status_error', 'Anda tidak memiliki akses. Hanya petugas yang dapat login.');
                return redirect()->back();
            }
        }

        if ($dataUser) {
            // Check if it's a petugas login and validate password
            $id = $dataUser->id;
            $dataPetugas = $petugas->withDeleted()->find($id);

            if ($dataPetugas === null) {
                session()->setFlashdata('error', 'Data petugas tidak ditemukan.');
                return redirect()->back();
            }

            // Cek apakah akun telah dinonaktifkan (soft delete)
            if ($dataPetugas->deleted_at !== null) {
                session()->setFlashdata('status_error', 'Akun Anda telah dinonaktifkan! Hubungi admin untuk informasi lebih lanjut.');
                return redirect()->back(); 
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

                // Redirect based on role
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
