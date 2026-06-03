<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu, rek!');
        }

        // 2. Cek apakah role user saat ini diizinkan mengakses halaman (argumen dari Routes.php)
        if ($arguments) {
            $roleUserSaatIni = session()->get('role');
            // Jika role user tidak ada di dalam daftar argumen filter, tendang balik ke dashboard
            if (!in_array($roleUserSaatIni, $arguments)) {
                return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut!');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja, tidak perlu diisi
    }
}
