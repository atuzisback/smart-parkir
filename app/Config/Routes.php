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
    $routes->get('transaksi/monitoring', 'Transaksi::monitoring');
    $routes->post('transaksi/proses-masuk', 'Transaksi::prosesMasuk');
    $routes->get('transaksi/aktif', 'Transaksi::aktif');
    $routes->get('transaksi/riwayat', 'Transaksi::riwayat');

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
    $routes->get('laporan', 'Laporan::index'); // <-- Sekarang sudah aman di dalam grup
    $routes->get('laporan/pendapatan', 'Laporan::pendapatan');
    $routes->get('laporan/statistik', 'Laporan::statistik');
    $routes->get('laporan/histori', 'Laporan::histori');
    $routes->get('laporan/kepadatan', 'Laporan::kepadatan');

    // Rute Autentikasi Login
    $routes->get('login', 'Auth::login');
    $routes->post('auth/prosesLogin', 'Auth::prosesLogin');
    $routes->get('logout', 'Auth::logout');

    // Pastikan name controller-nya disesuaikan (misal: Transaksi atau TransaksiController)
    $routes->post('transaksi/proses-keluar', 'Transaksi::prosesKeluar');
});
