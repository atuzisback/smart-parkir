<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Filter -->
<div class="card mb-20" style="margin-bottom:20px">
    <div class="card-body" style="padding:16px">
        <form method="get" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="<?= esc($dari ?? date('Y-m-01')) ?>">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="<?= esc($sampai ?? date('Y-m-d')) ?>">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:120px">
                <label class="form-label">Tampilkan Per</label>
                <select name="per" class="form-control">
                    <option value="hari" <?= ($per ?? 'hari') == 'hari' ? 'selected' : '' ?>>Harian</option>
                    <option value="bulan" <?= ($per ?? '') == 'bulan' ? 'selected' : '' ?>>Bulanan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="<?= base_url('laporan/pendapatan/export') ?>?dari=<?= $dari ?? '' ?>&sampai=<?= $sampai ?? '' ?>" class="btn btn-outline">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="stats-grid" style="margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value" style="font-size:20px">Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.') ?></div>
            <div class="stat-meta">Periode dipilih</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value"><?= $total_transaksi ?? 0 ?></div>
            <div class="stat-meta">Lunas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-chart-bar"></i></div>
        <div class="stat-body">
            <div class="stat-label">Rata-rata/Hari</div>
            <div class="stat-value" style="font-size:18px">Rp <?= number_format($rata_rata ?? 0, 0, ',', '.') ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
        <div class="stat-body">
            <div class="stat-label">Pendapatan Tertinggi</div>
            <div class="stat-value" style="font-size:18px">Rp <?= number_format($tertinggi ?? 0, 0, ',', '.') ?></div>
            <div class="stat-meta"><?= esc($tgl_tertinggi ?? '-') ?></div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="chart-container" style="margin-bottom:20px">
    <div class="chart-header">
        <span class="chart-title"><i class="fas fa-chart-bar text-primary-color"></i> Grafik Pendapatan</span>
    </div>
    <div class="chart-wrapper" style="height:250px">
        <canvas id="chartPendapatan"></canvas>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <span class="table-title"><i class="fas fa-table"></i> Rekap Pendapatan</span>
        <div class="table-actions">
            <div class="input-group search-input">
                <span class="input-group-icon"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Cari..." data-search-table="tbl-laporan">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="tbl-laporan">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Pendapatan</th>
                    <th>Rata-rata/Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)): ?>
                    <?php foreach ($laporan as $row): ?>
                    <tr>
                        <td class="mono"><?= esc($row['tanggal']) ?></td>
                        <td><?= $row['jumlah_transaksi'] ?></td>
                        <td class="mono fw-bold" style="color:var(--success)">
                            Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?>
                        </td>
                        <td class="mono">
                            Rp <?= $row['jumlah_transaksi'] > 0 ? number_format($row['total_pendapatan'] / $row['jumlah_transaksi'], 0, ',', '.') : 0 ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4"><div class="empty-state"><i class="fas fa-chart-line"></i><h3>Tidak ada data pada periode ini</h3></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const labels = <?= json_encode($chart_labels ?? []) ?>;
    const values = <?= json_encode($chart_values ?? []) ?>;
    createBarChart('chartPendapatan', labels, values, 'Pendapatan');
</script>
<?= $this->endSection() ?>