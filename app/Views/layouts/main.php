<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Smart Parkir' ?> - Sistem Manajemen Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon"><i class="fas fa-parking"></i></div>
                <div class="logo-text">
                    <span class="logo-title">SmartParkir</span>
                    <span class="logo-sub">Management System</span>
                </div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-label">UTAMA</span>
                <a href="<?= base_url('dashboard') ?>" class="nav-item <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-label">MASTER DATA</span>
                <a href="<?= base_url('master/slot') ?>" class="nav-item <?= (strpos(current_url(), 'master/slot') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Kelola Slot</span>
                </a>
                <a href="<?= base_url('master/zona') ?>" class="nav-item <?= (strpos(current_url(), 'master/zona') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-layer-group"></i>
                    <span>Zona & Tarif</span>
                </a>
                <a href="<?= base_url('master/user') ?>" class="nav-item <?= (strpos(current_url(), 'master/user') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-users-cog"></i>
                    <span>Manajemen User</span>
                </a>
                <a href="<?= base_url('master/gerbang') ?>" class="nav-item <?= (strpos(current_url(), 'master/gerbang') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-door-open"></i>
                    <span>Gerbang</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-label">OPERASIONAL</span>
                <a href="<?= base_url('transaksi/masuk') ?>" class="nav-item <?= (strpos(current_url(), 'transaksi/masuk') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Gerbang Masuk</span>
                </a>
                <a href="<?= base_url('transaksi/monitoring') ?>" class="nav-item <?= (strpos(current_url(), 'transaksi/monitoring') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-eye"></i>
                    <span>Monitoring Slot</span>
                </a>
                <a href="<?= base_url('transaksi/keluar') ?>" class="nav-item <?= (strpos(current_url(), 'transaksi/keluar') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Gerbang Keluar</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-label">LAPORAN</span>
                <a href="<?= base_url('laporan/pendapatan') ?>" class="nav-item <?= (strpos(current_url(), 'laporan/pendapatan') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Pendapatan</span>
                </a>
                <a href="<?= base_url('laporan/statistik') ?>" class="nav-item <?= (strpos(current_url(), 'laporan/statistik') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistik Kendaraan</span>
                </a>
                <a href="<?= base_url('laporan/histori') ?>" class="nav-item <?= (strpos(current_url(), 'laporan/histori') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-history"></i>
                    <span>Histori Parkir</span>
                </a>
                <a href="<?= base_url('laporan/kepadatan') ?>" class="nav-item <?= (strpos(current_url(), 'laporan/kepadatan') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-heat-map"></i>
                    <span>Kepadatan Lahan</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <span class="user-name"><?= session('username') ?? 'Admin' ?></span>
                    <span class="user-role"><?= ucfirst(session('role') ?? 'petugas') ?></span>
                </div>
            </div>
            <a href="<?= base_url('auth/logout') ?>" class="logout-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb-area">
                    <h1 class="page-title"><?= $title ?? 'Dashboard' ?></h1>
                    <div class="breadcrumb">
                        <span>Smart Parkir</span>
                        <i class="fas fa-chevron-right"></i>
                        <span class="current"><?= $title ?? 'Dashboard' ?></span>
                    </div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-clock" id="topbarClock"></div>
                <button class="topbar-btn" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                <button class="topbar-btn" title="Refresh" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
                <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= session()->getFlashdata('error') ?>
                <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
            </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>
    
    

    <script src="<?= base_url('js/app.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>