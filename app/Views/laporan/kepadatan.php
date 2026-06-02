<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card mb-20" style="margin-bottom:20px">
    <div class="card-body" style="padding:16px">
        <form method="get" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Pilih Tanggal Analisis</label>
                <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggal ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Aktualkan
            </button>
        </form>
    </div>
</div>

<div class="row g-4" style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div class="table-container" style="flex: 1; min-width: 300px;">
        <div class="table-header">
            <span class="table-title"><i class="fas fa-layer-group"></i> Tingkat Kepadatan Lokasi / Zona Parkir</span>
        </div>
        <div class="table-responsive">
            <table id="tbl-kepadatan">
                <thead>
                    <tr>
                        <th>Nama Zona</th>
                        <th style="text-align: center;">Kapasitas Maksimal</th>
                        <th style="text-align: center;">Terisi Saat Ini</th>
                        <th style="text-align: right;">Persentase Kepadatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kepadatan)): ?>
                        <?php foreach ($kepadatan as $row): ?>
                            <?php
                            // Menghitung persentase isi zona
                            $persen = $row['kapasitas'] > 0 ? round(($row['terisi'] / $row['kapasitas']) * 100) : 0;
                            // Menentukan warna status berdasarkan kepadatan
                            $color = 'var(--success)';
                            if ($persen >= 80) {
                                $color = '#dc3545';
                            } // Red/Penuh
                            elseif ($persen >= 50) {
                                $color = '#ffc107';
                            } // Yellow/Sedang
                            ?>
                            <tr>
                                <td class="fw-bold"><?= esc($row['nama_zona']) ?></td>
                                <td style="text-align: center;" class="mono"><?= number_format($row['kapasitas']) ?> Slot</td>
                                <td style="text-align: center;" class="mono fw-bold"><?= number_format($row['terisi']) ?></td>
                                <td style="text-align: right; color: <?= $color ?>;" class="mono fw-bold">
                                    <?= $persen ?>%
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state"><i class="fas fa-parking"></i>
                                    <h3>Tidak ada log aktivitas kepadatan zona hari ini</h3>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>