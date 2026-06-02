<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Default (Saat pertama kali dibuka, langsung melempar ke halaman transaksi masuk)
$routes->get('/', 'Transaksi::masuk');

// 2. Registrasi Rute Menggunakan Format Grup Terbaru (CI 4.7.2+)
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {

$routes->get('dashboard', 'Dashboard::index');

   // Rute Menu Transaksi
$routes->get('transaksi/masuk', 'Transaksi::masuk');
$routes->post('transaksi/simpanMasuk', 'Transaksi::simpanMasuk');
$routes->get('transaksi/keluar', 'Transaksi::keluar');
$routes->post('transaksi/prosesKeluar', 'Transaksi::prosesKeluar');
$routes->get('transaksi/struk/(:num)', 'Transaksi::struk/$1');

// Tambahkan baris baru ini, rek!
$routes->get('transaksi/monitoring', 'Transaksi::monitoring');

// Rute untuk memproses data kendaraan masuk (Metode POST)
$routes->post('transaksi/proses-masuk', 'Transaksi::prosesMasuk');

    // ── Rute Menu Master Data ──
    $routes->get('master/slot', 'Master::slot');
    $routes->post('master/simpanSlot', 'Master::simpanSlot');
    $routes->post('master/updateSlot', 'Master::updateSlot');
    $routes->get('master/hapusSlot/(:num)', 'Master::hapusSlot/$1');

    $routes->get('master/zona', 'Master::zona');
    $routes->post('master/simpanZona', 'Master::simpanZona');
    $routes->post('master/updateZona', 'Master::updateZona');
    $routes->get('master/hapusZona/(:num)', 'Master::hapusZona/$1');

    $routes->get('master/user', 'Master::user');
    $routes->post('master/simpanUser', 'Master::simpanUser');
    $routes->get('master/hapusUser/(:num)', 'Master::hapusUser/$1');

    $routes->get('master/gerbang', 'Master::gerbang');
    $routes->post('master/simpanGerbang', 'Master::simpanGerbang');
    $routes->get('master/hapusGerbang/(:num)', 'Master::hapusGerbang/$1');

    // ── Rute Menu Laporan ──
    $routes->get('laporan/pendapatan', 'Laporan::pendapatan');
    $routes->get('laporan/statistik', 'Laporan::statistik');
    $routes->get('laporan/histori', 'Laporan::histori');
    $routes->get('laporan/kepadatan', 'Laporan::kepadatan');

    // Rute untuk Navigasi Utama / Atas
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('transaksi/aktif', 'Transaksi::aktif'); // Sesuaikan nama method di controller kamu
$routes->get('transaksi/riwayat', 'Transaksi::riwayat'); // Sesuaikan nama method di controller kamu

// Rute Autentikasi Login
$routes->get('login', 'Auth::login');
$routes->post('auth/prosesLogin', 'Auth::prosesLogin');
$routes->get('logout', 'Auth::logout');
});