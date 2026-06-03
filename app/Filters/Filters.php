<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    /**
     * Mengatur alias nama filter untuk mempermudah pemanggilan di Routes
     */
    public array $aliases = [
        'csrf'          => \CodeIgniter\Filters\CSRF::class,
        'toolbar'       => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars'  => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,

        // ALIAS UNTUK PENGUNCI ROLE KAMU:
        'cek_role'      => \App\Filters\RoleFilter::class,
    ];

    /**
     * Filter yang langsung berjalan di setiap request (Global)
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
        ],
    ];

    /**
     * Filter berdasarkan metode HTTP (GET, POST, dll)
     */
    public array $methods = [];

    /**
     * Filter berdasarkan pola URL tertentu
     */
    public array $filters = [];
}
