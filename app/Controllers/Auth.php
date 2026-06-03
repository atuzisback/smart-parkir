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
            // Pengecekan password mendukung: password_verify, teks biasa, DAN hash SHA-256 dari database
            if (
                password_verify($password, $user['password']) ||
                $user['password'] === $password ||
                hash('sha256', $password) === $user['password'] // <-- TAMBAHAN UNTUK MEMBACA HASH SHA-256
            ) {

                // Set data ke dalam session
                $setSession = [
                    'id_user'   => $user['id_user'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],                      // Untuk membedakan hak akses manajer, admin, petugas
                    'logged_in' => true
                ];
                $session->set($setSession);

                return redirect()->to(base_url('dashboard'))->with('success', 'Selamat datang kembali!');
            } else {
                return redirect()->back()->with('error', 'Password salah, rek!');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan!');
        }
    }

    public function logout()
    {
        // Hancurkan semua data session login
        session()->destroy();

        // Alihkan halaman kembali ke form login dengan pesan sukses
        return redirect()->to(base_url('login'))->with('success', 'Anda berhasil logout.');
    }
}
