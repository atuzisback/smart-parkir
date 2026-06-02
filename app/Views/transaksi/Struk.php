<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:480px;margin:0 auto">
    <div class="card">
        <div class="card-header" style="justify-content:center">
            <span class="card-title"><i class="fas fa-ticket-alt"></i> Struk Parkir</span>
        </div>
        <div class="card-body">
            <!-- Header struk -->
            <div style="text-align:center;margin-bottom:20px;padding-bottom:20px;border-bottom:2px dashed var(--border)">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--primary),#7C3AED);border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:22px;color:#fff">
                    <i class="fas fa-parking"></i>
                </div>
                <div style="font-size:20px;font-weight:800;letter-spacing:-0.3px">Smart Parkir</div>
                <div style="font-size:12px;color:var(--text-muted)">Sistem Manajemen Parkir Terintegrasi</div>
            </div>

            <!-- Barcode visual -->
            <div style="text-align:center;margin-bottom:20px">
                <div style="font-family:'JetBrains Mono',monospace;font-size:32px;font-weight:800;letter-spacing:4px;color:var(--text-primary)">
                    <?= esc($transaksi['barcode']) ?>
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px">Tunjukkan saat keluar</div>
            </div>

            <!-- Detail -->
            <div style="background:var(--surface-2);border-radius:var(--radius);padding:16px;margin-bottom:16px">
                <?php $rows = [
                    ['No. Plat',     $transaksi['no_plat'], 'mono'],
                    ['Zona',         $transaksi['nama_zona'] ?? '-', ''],
                    ['Slot',         $transaksi['no_slot'] ?? '-', 'mono'],
                    ['Gerbang',      $transaksi['nama_gerbang'] ?? '-', ''],
                    ['Waktu Masuk',  date('d/m/Y H:i:s', strtotime($transaksi['waktu_masuk'])), ''],
                    ['Tarif',        'Rp ' . number_format($transaksi['tarif_jam'] ?? 0, 0, ',', '.') . '/jam', ''],
                ]; ?>
                <?php foreach ($rows as [$label, $value, $cls]): ?>
                <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border-light)">
                    <span style="font-size:12.5px;color:var(--text-muted)"><?= $label ?></span>
                    <strong class="<?= $cls ?>" style="font-size:13px"><?= esc($value) ?></strong>
                </div>
                <?php endforeach; ?>
            </div>

            <div style="text-align:center;font-size:12px;color:var(--text-muted);margin-bottom:20px">
                Harap jaga struk ini. Kehilangan struk dikenakan tarif maksimum.
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:10px">
                <button onclick="window.print()" class="btn btn-outline" style="flex:1">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>