<?php

namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_petugas', 'action', 'msg', 'time', 'old_data', 'new_data'];
}
