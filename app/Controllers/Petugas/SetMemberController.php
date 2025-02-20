<?php

namespace App\Controllers\petugas;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\PetugasModel;
use App\Models\LogsModel;

class SetMemberController extends BaseController
{
    protected $memberModel;
    protected $petugasModel;
    protected $logsModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->petugasModel = new PetugasModel();
        $this->logsModel = new LogsModel();
    }

    public function index()
    {
        $members = $this->memberModel
            ->where('status', 1) // Only active members
            ->where('deleted_at', null) // Exclude soft-deleted members
            ->orderBy("FIELD(tipe_member, 3) DESC") // Tipe member 3 selalu di atas
            ->orderBy("nm_member", "ASC") // Urutkan berdasarkan nama
            ->findAll();

        return view('petugas/member/index', ['members' => $members]);
    }

    public function create()
    {
        $memberTipe3Exists = $this->memberModel->where('tipe_member', 3)->countAllResults() > 0;

        return view('petugas/member/create', ['memberTipe3Exists' => $memberTipe3Exists]);
    }

    public function store()
    {
        $email = $this->request->getPost('email');
        $no_hp = $this->request->getPost('no_hp');
        $id_petugas = session()->get('id');

        // Check if the email already exists in petugas
        if ($this->petugasModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah digunakan oleh petugas!')->withInput();
        }

        if ($this->memberModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar sebagai member!')->withInput();
        }

        if ($this->memberModel->where('no_hp', $no_hp)->first()) {
            return redirect()->back()->with('error', 'Nomor HP sudah terdaftar!')->withInput();
        }

        $newData = [
            'nm_member'   => $this->request->getPost('nm_member'),
            'email'       => $email,
            'no_hp'       => $no_hp,
            'alamat'      => $this->request->getPost('alamat'),
            'poin'        => $this->request->getPost('poin'),
            'tipe_member' => $this->request->getPost('tipe_member'),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        $this->memberModel->save($newData);
        $id_member = $this->memberModel->insertID();

        $this->logsModel->save([
            'id_petugas' => $id_petugas,
            'action'     => 'tambah',
            'msg'        => "Menambahkan member dengan ID: $id_member",
            'old_data'   => null,
            'new_data'   => json_encode($newData),
            'time'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('petugas/pengaturan-member'))->with('success', 'Member berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data['member'] = $this->memberModel->find($id);

        // Check if there is already a member with tipe_member == 3
        $existingTipe3 = $this->memberModel->where('tipe_member', 3)->first();
        $data['disableTipe3'] = $existingTipe3 ? true : false;

        return view('petugas/member/edit', $data);
    }

    public function update($id)
    {
        $email = $this->request->getPost('email');
        $no_hp = $this->request->getPost('no_hp');
        $id_petugas = session()->get('id');

        // Check if the email already exists in petugas
        if ($this->petugasModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah digunakan oleh petugas!')->withInput();
        }

        // Check if the email is already taken by another member
        $oldData = $this->memberModel->find($id);
        if ($this->memberModel->where('email', $email)->where('id !=', $id)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar sebagai member lain!')->withInput();
        }

        $newData = [
            'nm_member'   => $this->request->getPost('nm_member'),
            'email'       => $email,
            'no_hp'       => $no_hp,
            'alamat'      => $this->request->getPost('alamat'),
            'poin'        => $this->request->getPost('poin'),
            'tipe_member' => $this->request->getPost('tipe_member'),
        ];

        $this->memberModel->update($id, $newData);

        $this->logsModel->save([
            'id_petugas' => $id_petugas,
            'action'     => 'edit',
            'msg'        => "Mengedit member ID: $id",
            'old_data'   => json_encode($oldData),
            'new_data'   => json_encode($newData),
            'time'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('petugas/pengaturan-member'))->with('success', 'Member berhasil diperbarui!');
    }

    public function detail($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            return redirect()->to(base_url('petugas/pengaturan-member'))->with('error', 'Member tidak ditemukan!');
        }
        return view('petugas/member/detail', ['member' => $member]);
    }

    public function delete($id)
    {
        $id_petugas = session()->get('id');
        $member = $this->memberModel->find($id);
        if (!$member) {
            return redirect()->to(base_url('petugas/pengaturan-member'))->with('error', 'Member tidak ditemukan!');
        }

        $this->memberModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);

        // Log the action
        $this->logsModel->save([
            'id_petugas' => $id_petugas,
            'action'     => 'hapus',
            'msg'        => "Menonaktifkan member ID: $id",
            'old_data'   => json_encode($member),
            'new_data'   => null,
            'time'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('petugas/pengaturan-member'))->with('success', 'Member berhasil dinonaktifkan!');
    }


    public function nonaktif()
    {
        $members = $this->memberModel
            ->onlyDeleted() // Exclude soft-deleted members
            ->orderBy("FIELD(tipe_member, 3) DESC") // Tipe member 3 selalu di atas
            ->orderBy("nm_member", "ASC") // Urutkan berdasarkan nama
            ->findAll();


        return view('petugas/member/nonaktif', ['members' => $members]);
    }

    public function restore($id)
    {
        $id_petugas = session()->get('id');
        $member = $this->memberModel->onlyDeleted()->find($id);

        if (!$member) {
            return redirect()->to(base_url('petugas/pengaturan-member/nonaktif'))->with('error', 'Member tidak ditemukan!');
        }

        // Pastikan member yang di-restore adalah member yang benar-benar dihapus
        if ($member['deleted_at'] !== null) {
            // Mengembalikan member dengan mengubah 'deleted_at' menjadi null dan 'status' menjadi 1 (aktif)
            $this->memberModel->update($id, ['deleted_at' => null, 'status' => 1]);

            // Log aksi restore
            $this->logsModel->save([
                'id_petugas' => $id_petugas,
                'action'     => 'restore',
                'msg'        => "Mengaktifkan kembali member ID: $id",
                'old_data'   => json_encode($member),
                'new_data'   => null,
                'time'       => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to(base_url('petugas/pengaturan-member/nonaktif'))->with('success', 'Member berhasil diaktifkan kembali!');
        } else {
            return redirect()->to(base_url('petugas/pengaturan-member/nonaktif'))->with('error', 'Member sudah aktif!');
        }
    }
}
