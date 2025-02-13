<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogsModel;

class LogsController extends BaseController
{
    protected $logsModel;

    public function __construct()
    {
        $this->logsModel = new LogsModel();
    }

    public function index()
    {
        return view('admin/logs/index'); // Tidak perlu mengirim data karena pakai AJAX
    }

    public function getFilteredLogs()
    {
        $filter = $this->request->getGet('filter');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('perPage') ?: 10;
        $offset = ($page - 1) * $perPage;

        $query = $this->logsModel->select('logs.*, petugas.nm_petugas')
            ->join('petugas', 'logs.id_petugas = petugas.id')
            ->orderBy('logs.time', 'DESC');

        if (!empty($filter)) {
            $query->whereIn('logs.action', $filter);
        }

        $totalLogs = $query->countAllResults(false);
        $logs = $query->limit($perPage, $offset)->findAll();

        return $this->response->setJSON([
            'logs' => $logs,
            'totalPages' => ceil($totalLogs / $perPage)
        ]);
    }


    public function detail($id)
    {
        $log = $this->logsModel
            ->select('logs.*, petugas.nm_petugas')
            ->join('petugas', 'petugas.id = logs.id_petugas')
            ->where('logs.id', $id)
            ->first();

        if (!$log) {
            return redirect()->to(base_url('owner/logs'))->with('error', 'Log tidak ditemukan!');
        }

        return view('admin/logs/detail', ['log' => $log]);
    }
}
