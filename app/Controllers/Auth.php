<?php

namespace App\Controllers;

use App\Models\UserModel; // Pastikan nama Model User kamu sudah sesuai

class Auth extends BaseController
{
    public function login()
    {
        // Kalau user sudah login, langsung lempar ke halaman dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/login');
    }

    public function prosesLogin()
    {
        $session = session();
        $model = new UserModel(); // Inisialisasi model user database
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Cari data user berdasarkan username
        $user = $model->where('username', $username)->first();

        if ($user) {
            // Cek password (bisa teks biasa atau password_hash)
            if (password_verify($password, $user['password']) || $user['password'] === $password) {
                
                // Set data ke dalam session
                $setSession = [
    'id_user'   => $user['id_user'],
    'username'  => $user['username'],
    'nama'      => $user['nama'] ?? $user['username'], // AMAN: Kalau kolom 'nama' tidak ada, pakai data username
    'level'     => $user['level'] ?? 'Admin',          // AMAN: Nilai cadangan jika kolom level kosong
    'logged_in' => true
];
                $session->set($setSession);
                
                return redirect()->to(base_url('dashboard'));
            } else {
                return redirect()->back()->with('error', 'Password salah, rek!');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan!');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}