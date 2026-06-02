<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

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
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>
</div>

<div class="row g-4" style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div class="table-container" style="flex: 1; min-width: 300px;">
        <div class="table-header">
            <span class="table-title"><i class="fas fa-table"></i> Rincian Tipe Kendaraan</span>
            <div class="table-actions">
                <div class="input-group search-input">
                    <span class="input-group-icon"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari..." data-search-table="tbl-statistik">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="tbl-statistik">
                <thead>
                    <tr>
                        <th>Jenis Kendaraan</th>
                        <th style="text-align: center;">Jumlah Masuk</th>
                        <th style="text-align: right;">Total Pendapatan</th>
                        <th style="text-align: center;">Rata-rata Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($statistik)): ?>
                        <?php foreach ($statistik as $row): ?>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas <?= $row['jenis_kendaraan'] == 'Mobil' ? 'fa-car text-primary-color' : ($row['jenis_kendaraan'] == 'Motor' ? 'fa-motorcycle text-success' : 'fa-bus text-warning') ?>" style="margin-right: 8px;"></i>
                                    <?= esc($row['jenis_kendaraan']) ?>
                                </td>
                                <td style="text-align: center;" class="mono fw-bold"><?= number_format($row['jumlah']) ?></td>
                                <td style="text-align: right; color: var(--success);" class="mono fw-bold">
                                    Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?>
                                </td>
                                <td style="text-align: center;" class="mono"><?= number_format($row['avg_durasi'], 1) ?> Jam</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state"><i class="fas fa-chart-pie"></i>
                                    <h3>Tidak ada data pada periode ini</h3>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="chart-container" style="width: 400px; min-width: 300px;">
        <div class="chart-header">
            <span class="chart-title"><i class="fas fa-chart-pie text-primary-color"></i> Grafik Proporsi Kendaraan</span>
        </div>
        <div class="chart-wrapper" style="height: 280px; padding: 16px; display: flex; justify-content: center; align-items: center;">
            <?php if (!empty($statistik)): ?>
                <canvas id="chartStatistik"></canvas>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-image"></i>
                    <h3>Grafik tidak tersedia</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    <?php if (!empty($statistik)): ?>
        const dataStatistik = <?= json_encode($statistik) ?>;
        const labelsArr = dataStatistik.map(item => item.jenis_kendaraan);
        const valuesArr = dataStatistik.map(item => parseInt(item.jumlah));

        const ctxJenis = document.getElementById('chartStatistik').getContext('2d');
        new Chart(ctxJenis, {
            type: 'doughnut',
            data: {
                labels: labelsArr,
                datasets: [{
                    data: valuesArr,
                    backgroundColor: [
                        '#0d6efd', // Mobil -> Biru
                        '#198754', // Motor -> Hijau
                        '#ffc107', // Truk/Bus -> Kuning
                        '#dc3545', '#6f42c1'
                    ],
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>