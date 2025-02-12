<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\PenjualanModel;
use App\Models\TokoModel;
use App\Models\MemberModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('nembak');
    }
}