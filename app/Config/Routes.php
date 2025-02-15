<?php

use CodeIgniter\Router\RouteCollection;

$routes->setAutoRoute(false); // Pastikan AutoRoute dimatikan agar lebih aman

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Loginctrl::index');
$routes->get('login', 'Loginctrl::index');
$routes->post('login/process', 'Loginctrl::process');
$routes->get('logout', 'Loginctrl::logout');
$routes->get('dashboard', 'DashboardController::index');

// **GROUP OWNER**
$routes->group('owner', ['filter' => 'role:owner'], function ($routes) {
    $routes->get('dashboard', 'Owner\AdminController::index');

    $routes->get('logs', 'LogsController::index');
    $routes->get('logs/detail/(:num)', 'LogsController::detail/$1');
    $routes->get('logs/getFilteredLogs', 'LogsController::getFilteredLogs');



    //Petugas
    $routes->get('petugas', 'Owner\SetPetugasController::index');
    $routes->get('petugas/create', 'Owner\SetPetugasController::create');
    $routes->post('petugas/store', 'Owner\SetPetugasController::store');
    $routes->get('petugas/edit/(:num)', 'Owner\SetPetugasController::edit/$1');
    $routes->post('petugas/update/(:num)', 'Owner\SetPetugasController::update/$1');
    $routes->get('petugas/delete/(:num)', 'Owner\SetPetugasController::delete/$1');
    $routes->get('petugas/nonaktif', 'Owner\SetPetugasController::nonaktif');
    $routes->get('petugas/restore/(:num)', 'Owner\SetPetugasController::restore/$1');


    //Member
    $routes->get('member', 'Owner\SetMemberController::index');
    $routes->get('member/create', 'Owner\SetMemberController::create');
    $routes->post('member/store', 'Owner\SetMemberController::store');
    $routes->get('member/edit/(:num)', 'Owner\SetMemberController::edit/$1');
    $routes->post('member/update/(:num)', 'Owner\SetMemberController::update/$1');
    $routes->get('member/delete/(:num)', 'Owner\SetMemberController::delete/$1');
    $routes->get('member/detail/(:num)', 'Owner\SetMemberController::detail/$1');
    $routes->get('member/nonaktif', 'Owner\SetMemberController::nonaktif');
    $routes->get('member/restore/(:num)', 'Owner\SetMemberController::restore/$1');


    //Barang
    $routes->get('barang', 'Owner\BarangController::index');
    $routes->get('barang/create', 'Owner\BarangController::create');
    $routes->post('barang/store', 'Owner\BarangController::store');
    $routes->get('barang/edit/(:num)', 'Owner\BarangController::edit/$1');
    $routes->post('barang/update/(:num)', 'Owner\BarangController::update/$1');
    $routes->get('barang/delete/(:num)', 'Owner\BarangController::delete/$1');

    $routes->get('barang/tambahStok/(:segment)', 'Owner\BarangController::tambahStokForm/$1');
    $routes->post('barang/tambahStok', 'Owner\BarangController::tambahStok');
    $routes->get('barang/detail/(:segment)', 'Owner\BarangController::detail/$1');

    $routes->get('barang/editStokForm/(:num)', 'Owner\BarangController::editStokForm/$1');
    $routes->post('barang/updateStok/(:num)', 'Owner\BarangController::updateStok/$1');
    $routes->get('barang/deleteStok/(:num)', 'Owner\BarangController::deleteStok/$1');

    //Kategori
    $routes->get('kategori', 'Owner\KategoriController::index');
    $routes->get('kategori/create', 'Owner\KategoriController::create');
    $routes->post('kategori/store', 'Owner\KategoriController::store');
    $routes->get('kategori/edit/(:num)', 'Owner\KategoriController::edit/$1');
    $routes->post('kategori/update/(:num)', 'Owner\KategoriController::update/$1');
    $routes->get('kategori/delete/(:num)', 'Owner\KategoriController::delete/$1');

    //Transaksi
    $routes->get('transaksi', 'Owner\TransaksiController::index');
    $routes->post('transaksi/proses', 'Owner\TransaksiController::proses');
    $routes->get('transaksi/cetak_nota/(:num)', 'Owner\TransaksiController::cetak_nota/$1');


    $routes->get('laporan', 'Owner\LaporanController::index');
    $routes->get('laporan/detail/(:num)', 'Owner\LaporanController::detail/$1');
});

// **GROUP PETUGAS**
$routes->group('petugas', ['filter' => 'role:petugas'], function ($routes) {
    $routes->get('dashboard', 'Petugas\PetugasController::index');
});

// **GROUP MEMBER**
$routes->group('member', ['filter' => 'role:owner,petugas'], function ($routes) {

    // CRUD Member
    $routes->get('member', 'SetMemberController::index'); // Menampilkan daftar member
    $routes->get('member/create', 'SetMemberController::create'); // Form tambah member
    $routes->post('member/store', 'SetMemberController::store'); // Proses simpan member
    $routes->get('member/edit/(:num)', 'SetMemberController::edit/$1'); // Form edit member
    $routes->post('member/update/(:num)', 'SetMemberController::update/$1'); // Proses update
    $routes->get('member/delete/(:num)', 'SetMemberController::delete/$1'); // Hapus member
});
