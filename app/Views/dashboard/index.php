<?= $this->extend('layouts/main') ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-parking"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Slot</div>
            <div class="stat-value"><?= $total_slot ?? 0 ?></div>
            <div class="stat-meta">Seluruh zona</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-car"></i></div>
        <div class="stat-body">
            <div class="stat-label">Slot Terisi</div>
            <div class="stat-value"><?= $slot_terisi ?? 0 ?> <small style="font-size:14px;font-weight:500;color:var(--text-muted)">(<?= $pct_terisi ?? 0 ?>%)</small></div>
            <div class="stat-meta"><?= $slot_tersedia ?? 0 ?> slot tersedia</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-clock"></i></div>
        <div class="stat-body">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value"><?= $transaksi_hari_ini ?? 0 ?></div>
            <div class="stat-meta">Masuk & keluar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-trend-up"></i></div>
        <div class="stat-body">
            <div class="stat-label">Pendapatan Hari Ini</div>
            <div class="stat-value" style="font-size:18px">Rp <?= number_format($pendapatan_hari_ini ?? 0, 0, ',', '.') ?></div>
            <div class="stat-meta stat-meta-up"><i class="fas fa-arrow-up"></i> Lunas</div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs-container">
    <div class="tabs-header">
       <a class="nav-link" href="<?= base_url('dashboard'); ?>">
    <i class="fa-solid fa-gauge me-1"></i> Dashboard
</a>

<a class="nav-link" href="<?= base_url('transaksi/masuk'); ?>">
    <i class="fa-solid fa-car me-1"></i> Transaksi Aktif
</a>

<a class="nav-link" href="<?= base_url('laporan/pendapatan'); ?>">
    <i class="fa-solid fa-history me-1"></i> Riwayat
</a>

<a class="nav-link" href="<?= base_url('master/gerbang'); ?>">
    <i class="fa-solid fa-door-open me-1"></i> Gerbang
</a>
        </button>
    </div>

    <div class="tabs-body">
        <!-- Tab: Dashboard (Zona) -->
        <div class="tab-pane active" id="tab-dashboard">
            <div class="zona-grid">
                <?php if (!empty($zona_data)): ?>
                    <?php foreach ($zona_data as $zona): ?>
                    <div class="zona-card">
                        <div class="zona-header">
                            <div>
                                <div class="zona-name"><?= esc($zona['nama_zona']) ?></div>
                                <div class="zona-tarif">Tarif: Rp <?= number_format($zona['tarif_jam'], 0, ',', '.') ?>/jam</div>
                            </div>
                            <?php
                                $lc = strtolower($zona['nama_zona']);
                                $cls = str_contains($lc,'motor') ? 'motor' : (str_contains($lc,'vip') ? 'vip' : 'mobil');
                                $lbl = str_contains($lc,'motor') ? 'Motor' : (str_contains($lc,'vip') ? 'VIP' : 'Mobil');
                            ?>
                            <span class="zona-badge <?= $cls ?>"><?= $lbl ?></span>
                        </div>
                        <?php
                            $total = $zona['total_slot'] ?? 0;
                            $terisi = $zona['slot_terisi'] ?? 0;
                            $tersedia = $zona['slot_tersedia'] ?? 0;
                            $pct = $total > 0 ? round(($terisi/$total)*100) : 0;
                            $fillClass = $pct >= 80 ? 'danger' : ($pct >= 60 ? 'warning' : '');
                        ?>
                        <div class="zona-capacity">
                            <span>Kapasitas</span>
                            <strong><?= $terisi ?>/<?= $total ?></strong>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill <?= $fillClass ?>" style="width:<?= $pct ?>%" data-width="<?= $pct ?>%"></div>
                        </div>
                        <div class="slot-grid">
                            <?php if (!empty($zona['slots'])): ?>
                                <?php foreach ($zona['slots'] as $slot): ?>
                                    <span class="slot-item <?= $slot['status_tersedia'] ? 'tersedia' : 'terisi' ?>"
                                          title="<?= $slot['status_tersedia'] ? 'Tersedia' : 'Terisi' ?>">
                                        <?= esc($slot['no_slot']) ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column:1/-1"><div class="empty-state"><i class="fas fa-parking"></i><h3>Belum ada zona</h3></div></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab: Transaksi Aktif -->
        <div class="tab-pane" id="tab-aktif">
            <div class="table-responsive">
                <table id="tbl-aktif">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>No. Plat</th>
                            <th>Slot</th>
                            <th>Zona</th>
                            <th>Waktu Masuk</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transaksi_aktif)): ?>
                            <?php foreach ($transaksi_aktif as $t): ?>
                            <tr>
                                <td class="mono"><?= esc($t['barcode'] ?? 'BC'.str_pad($t['id_transaksi'],8,'0',STR_PAD_LEFT)) ?></td>
                                <td><strong class="mono"><?= esc($t['no_plat']) ?></strong></td>
                                <td class="mono"><?= esc($t['no_slot'] ?? '-') ?></td>
                                <td><?= esc($t['nama_zona'] ?? '-') ?></td>
                                <td><?= date('d/m/Y, H.i.s', strtotime($t['waktu_masuk'])) ?></td>
                                <td class="mono"><?= esc($t['durasi_display'] ?? '-') ?></td>
                                <td><span class="badge-status parkir">Parkir</span></td>
                                <td>
                                    <a href="<?= base_url('transaksi/keluar?barcode='.($t['barcode'] ?? '')) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-sign-out-alt"></i> Proses Keluar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-car"></i><h3>Tidak ada transaksi aktif</h3></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Riwayat -->
        <div class="tab-pane" id="tab-riwayat">
            <div class="table-responsive">
                <table id="tbl-riwayat">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>No. Plat</th>
                            <th>Zona</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Durasi</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($riwayat_transaksi)): ?>
                            <?php foreach ($riwayat_transaksi as $t): ?>
                            <tr>
                                <td class="mono"><?= esc($t['barcode'] ?? 'BC'.str_pad($t['id_transaksi'],8,'0',STR_PAD_LEFT)) ?></td>
                                <td><strong class="mono"><?= esc($t['no_plat']) ?></strong></td>
                                <td><?= esc($t['nama_zona'] ?? '-') ?></td>
                                <td><?= date('d/m/Y, H.i.s', strtotime($t['waktu_masuk'])) ?></td>
                                <td><?= $t['waktu_keluar'] ? date('d/m/Y, H.i.s', strtotime($t['waktu_keluar'])) : '-' ?></td>
                                <td class="mono"><?= $t['durasi'] ? floor($t['durasi']/60).'j '.($t['durasi']%60).'m' : '-' ?></td>
                                <td class="mono">Rp <?= number_format($t['total_biaya'], 0, ',', '.') ?></td>
                                <td><span class="badge-status <?= $t['status_pembayaran'] ?>"><?= ucfirst($t['status_pembayaran']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-history"></i><h3>Belum ada riwayat</h3></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Gerbang -->
        <div class="tab-pane" id="tab-gerbang">
            <div class="gerbang-grid">
                <?php if (!empty($gerbang_data)): ?>
                    <?php foreach ($gerbang_data as $g): ?>
                    <div class="gerbang-card">
                        <div class="gerbang-header">
                            <span class="gerbang-name"><?= esc($g['nama_gerbang']) ?></span>
                            <span class="tag <?= $g['tipe_gerbang'] === 'masuk' ? 'tag-masuk' : 'tag-keluar' ?>">
                                <?= strtoupper($g['tipe_gerbang']) ?>
                            </span>
                        </div>
                        <div class="gerbang-info">
                            <p><i class="fas fa-camera"></i> Kamera ANPR: <?= esc($g['kamera_lpr'] ?? '-') ?></p>
                            <p><i class="fas fa-circle-dot"></i> Status Palang:
                                <span class="palang-status <?= $g['status_palang'] ?>"><?= strtoupper($g['status_palang']) ?></span>
                            </p>
                        </div>
                        <form method="post" action="<?= base_url('gerbang/toggle') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_gerbang" value="<?= $g['id_gerbang'] ?>">
                            <button type="submit" class="btn btn-primary btn-block btn-sm">
                                <i class="fas fa-door-open"></i> Buka Palang
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column:1/-1"><div class="empty-state"><i class="fas fa-door-open"></i><h3>Tidak ada gerbang</h3></div></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Section: Chart + Live Feed -->
<div class="section-grid mt-20">
    <!-- Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <span class="chart-title"><i class="fas fa-chart-line text-primary-color"></i> Pendapatan Harian (7 Hari Terakhir)</span>
            <a href="<?= base_url('laporan/pendapatan') ?>" class="btn btn-ghost btn-sm">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="chart-wrapper">
            <canvas id="chartPendapatan"></canvas>
        </div>
    </div>

    <!-- Live Feed -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-bolt"></i> Live Activity</span>
            <span class="badge-status belum" style="font-size:10px;padding:3px 8px">Live</span>
        </div>
        <div class="card-body" style="padding:0 20px">
            <?php if (!empty($live_feed)): ?>
                <?php foreach (array_slice($live_feed, 0, 6) as $f): ?>
                <div class="feed-item">
                    <div class="feed-icon <?= $f['waktu_keluar'] ? 'keluar' : 'masuk' ?>">
                        <i class="fas fa-<?= $f['waktu_keluar'] ? 'sign-out-alt' : 'sign-in-alt' ?>"></i>
                    </div>
                    <div class="feed-body">
                        <div class="feed-plat"><?= esc($f['no_plat']) ?></div>
                        <div class="feed-meta"><?= esc($f['nama_zona'] ?? 'Zona -') ?> · <?= $f['waktu_keluar'] ? 'Keluar' : 'Masuk' ?></div>
                    </div>
                    <div class="feed-time"><?= date('H:i', strtotime($f['waktu_masuk'])) ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="padding:30px"><i class="fas fa-satellite-dish"></i><h3>Belum ada aktivitas</h3></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const chartLabels = <?= json_encode($chart_labels ?? []) ?>;
    const chartData   = <?= json_encode($chart_values ?? []) ?>;
    createLineChart('chartPendapatan', chartLabels, chartData, 'Pendapatan');
</script>
<?= $this->endSection() ?>