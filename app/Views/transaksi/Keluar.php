<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 360px;gap:20px">

    <!-- Scan & Billing -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-sign-out-alt"></i> Gerbang Keluar & Billing</span>
            <span class="tag tag-keluar">CHECK-OUT</span>
        </div>
        <div class="card-body">
            <!-- Scan barcode -->
            <div style="margin-bottom:20px">
                <label class="form-label">Scan / Input Barcode Struk</label>
                <div style="display:flex;gap:10px">
                    <div class="input-group" style="flex:1">
                        <span class="input-group-icon"><i class="fas fa-barcode"></i></span>
                        <input type="text" id="barcodeInput" class="form-control mono"
                               placeholder="BC00000000"
                               value="<?= esc($barcode_scan) ?>"
                               style="font-size:16px;font-weight:700;letter-spacing:2px">
                    </div>
                    <button class="btn btn-primary" onclick="cariTransaksi()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>

            <?php if ($transaksi): ?>
            <!-- Detail Transaksi -->
            <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:20px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">No. Plat</div>
                        <div style="font-size:22px;font-weight:800;font-family:'JetBrains Mono',monospace;letter-spacing:2px"><?= esc($transaksi['no_plat']) ?></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Barcode</div>
                        <div style="font-size:16px;font-weight:700;font-family:'JetBrains Mono',monospace;color:var(--primary)"><?= esc($transaksi['barcode']) ?></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Zona / Slot</div>
                        <div style="font-size:14px;font-weight:600"><?= esc($transaksi['nama_zona']) ?> / <?= esc($transaksi['no_slot']) ?></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Tarif</div>
                        <div style="font-size:14px;font-weight:600">Rp <?= number_format($transaksi['tarif_jam'], 0, ',', '.') ?>/jam</div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Waktu Masuk</div>
                        <div style="font-size:14px;font-weight:600"><?= date('d/m/Y H:i:s', strtotime($transaksi['waktu_masuk'])) ?></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Durasi Parkir</div>
                        <div style="font-size:14px;font-weight:600;color:var(--warning)"><?= esc($transaksi['durasi_display']) ?></div>
                    </div>
                </div>

                <div style="margin-top:16px;padding-top:16px;border-top:2px dashed var(--border);display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <div style="font-size:12px;color:var(--text-muted)">Total Biaya</div>
                        <div style="font-size:30px;font-weight:800;color:var(--primary)">
                            Rp <?= number_format($transaksi['estimasi_biaya'], 0, ',', '.') ?>
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">*Dihitung saat pembayaran</div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px">Gerbang</div>
                        <div style="font-weight:600"><?= esc($transaksi['nama_gerbang']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <form method="post" action="<?= base_url('transaksi/proses-keluar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="id_metode" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            <?php foreach ($metode as $m): ?>
                            <option value="<?= $m['id_metode'] ?>"><?= esc($m['nama_metode']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gerbang Keluar</label>
                        <select name="id_gerbang_keluar" class="form-control">
                            <option value="">-- Pilih Gerbang --</option>
                            <?php foreach ($gerbang_keluar as $g): ?>
                            <option value="<?= $g['id_gerbang'] ?>"><?= esc($g['nama_gerbang']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg btn-block">
                    <i class="fas fa-check-double"></i> Konfirmasi Pembayaran & Buka Palang
                </button>
            </form>

            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-barcode"></i>
                <h3>Scan Barcode Struk</h3>
                <p>Scan atau input barcode dari struk parkir untuk melihat detail transaksi</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Antrian & Status -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-list"></i> Antrian Keluar</span>
            </div>
            <div class="card-body" style="padding:0 20px">
                <div style="padding:12px 0;text-align:center;color:var(--text-muted);font-size:13px">
                    <i class="fas fa-car-side" style="font-size:24px;margin-bottom:8px;display:block;opacity:.4"></i>
                    Tidak ada antrian keluar
                </div>
            </div>
        </div>

        <div class="card" style="border-color:#D1FAE5;background:#ECFDF5">
            <div class="card-body" style="padding:16px">
                <div style="font-weight:700;color:var(--success);margin-bottom:10px;font-size:13px">
                    <i class="fas fa-shield-alt"></i> Metode Pembayaran Tersedia
                </div>
                <?php foreach ($metode as $m): ?>
                <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid #A7F3D0;font-size:13px;color:var(--text-secondary)">
                    <i class="fas fa-check-circle" style="color:var(--success)"></i>
                    <?= esc($m['nama_metode']) ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function cariTransaksi() {
    const barcode = document.getElementById('barcodeInput').value.trim();
    if (barcode) {
        window.location.href = '<?= base_url('transaksi/keluar') ?>?barcode=' + encodeURIComponent(barcode);
    }
}
document.getElementById('barcodeInput')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') cariTransaksi();
});
</script>
<?= $this->endSection() ?>