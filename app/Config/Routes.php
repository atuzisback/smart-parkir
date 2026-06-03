<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Default & Login (Bisa dibuka tanpa login)
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/prosesLogin', 'Auth::prosesLogin');
$routes->get('auth/logout', 'Auth::logout');

// 2. Proteksi Semua Menu Menggunakan Grup Utama
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {

    // Semua yang login bisa buka dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // SLOT
    $routes->group('', ['filter' => 'cek_role:admin,manajer,petugas'], function ($routes) {

        $routes->get('master/slot', 'Master::slot');
        $routes->post('master/simpanSlot', 'Master::simpanSlot');
        $routes->post('master/updateSlot', 'Master::updateSlot');
        $routes->get('master/hapusSlot/(:num)', 'Master::hapusSlot/$1');
    });

    // ZONA + USER
    $routes->group('', ['filter' => 'cek_role:admin,manajer'], function ($routes) {

        $routes->get('master/zona', 'Master::zona');
        $routes->post('master/simpanZona', 'Master::simpanZona');
        $routes->post('master/updateZona', 'Master::updateZona');
        $routes->get('master/hapusZona/(:num)', 'Master::hapusZona/$1');

        $routes->get('master/user', 'Master::user');
        $routes->post('master/simpanUser', 'Master::simpanUser');
        $routes->get('master/hapusUser/(:num)', 'Master::hapusUser/$1');
    });

    // GERBANG
    $routes->group('', ['filter' => 'cek_role:admin,manajer,petugas'], function ($routes) {

        $routes->get('master/gerbang', 'Master::gerbang');
        $routes->post('master/simpanGerbang', 'Master::simpanGerbang');
        $routes->get('master/hapusGerbang/(:num)', 'Master::hapusGerbang/$1');
    });

    // ── KELOMPOK OPERASIONAL PARKIR (UNTUK: petugas, admin) ──
    $routes->group('', ['filter' => 'cek_role:petugas,admin,manajer'], function ($routes) {
        $routes->get('transaksi/masuk', 'Transaksi::masuk');
        $routes->post('transaksi/simpanMasuk', 'Transaksi::simpanMasuk');
        $routes->post('transaksi/proses-masuk', 'Transaksi::prosesMasuk');
        $routes->get('transaksi/keluar', 'Transaksi::keluar');
        $routes->post('transaksi/proses-keluar', 'Transaksi::prosesKeluar');
        $routes->post('transaksi/prosesKeluar', 'Transaksi::prosesKeluar');
        $routes->get('transaksi/struk/(:num)', 'Transaksi::struk/$1');
        $routes->get('transaksi/monitoring', 'Transaksi::monitoring');
        $routes->get('transaksi/aktif', 'Transaksi::aktif');
        $routes->get('transaksi/riwayat', 'Transaksi::riwayat');
    });

    // ── KELOMPOK LAPORAN KEUANGAN (UNTUK: manajer, admin) ──
    $routes->group('', ['filter' => 'cek_role:manajer,admin'], function ($routes) {
        $routes->get('laporan', 'Laporan::index');
        $routes->get('laporan/pendapatan', 'Laporan::pendapatan');
        $routes->get('laporan/statistik', 'Laporan::statistik');
        $routes->get('laporan/histori', 'Laporan::histori');
        $routes->get('laporan/kepadatan', 'Laporan::kepadatan');
    });
});
