<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card mb-20" style="margin-bottom:20px">
    <div class="card-body" style="padding:16px">
        <form method="get" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Cari Plat / Barcode</label>
                <input type="text" name="q" class="form-control" placeholder="Nomor plat / barcode..." value="<?= esc($q ?? '') ?>">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:140px">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="lunas" <?= ($status ?? '') == 'lunas' ? 'selected' : '' ?>>Lunas</option>
                    <option value="belum_bayar" <?= ($status ?? '') == 'belum_bayar' ? 'selected' : '' ?>>Belum Bayar</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <span class="table-title"><i class="fas fa-history"></i> Histori Parkir Kendaraan</span>
    </div>
    <div class="table-responsive">
        <table id="tbl-histori">
            <thead>
                <tr>
                    <th>Waktu Masuk</th>
                    <th>No. Plat</th>
                    <th>Jenis</th>
                    <th>Zona</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($histori)): ?>
                    <?php foreach ($histori as $row): ?>
                        <tr>
                            <td class="mono"><?= esc($row['waktu_masuk']) ?></td>
                            <td class="fw-bold"><?= esc($row['no_plat']) ?></td>
                            <td><?= esc($row['jenis_kendaraan'] ?? '-') ?></td>
                            <td><?= esc($row['nama_zona'] ?? '-') ?></td>
                            <td>
                                <span class="badge" style="background-color: <?= $row['status_pembayaran'] == 'lunas' ? 'var(--success)' : '#dc3545' ?>; color: white; padding: 4px 8px; rounded: 4px;">
                                    <?= ucfirst($row['status_pembayaran']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state"><i class="fas fa-folder-open"></i>
                                <h3>Belum ada data histori parkir</h3>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>