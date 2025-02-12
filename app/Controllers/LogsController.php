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
        $filter = $this->request->getGet('filter'); // Ambil filter dari GET parameter
        $query = $this->logsModel
            ->select('logs.*, petugas.nm_petugas')
            ->join('petugas', 'petugas.id = logs.id_petugas')
            ->orderBy('logs.time', 'DESC');

        if (!empty($filter)) {
            $filterArray = is_array($filter) ? $filter : explode(',', $filter);
            $query->whereIn('logs.action', $filterArray);
        }

        $logs = $query->findAll();

        return $this->response->setJSON($logs); // Kembalikan data sebagai JSON untuk AJAX
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
