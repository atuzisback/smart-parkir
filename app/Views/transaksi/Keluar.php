<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 360px;gap:20px">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-sign-out-alt"></i> Gerbang Keluar & Billing</span>
            <span class="tag tag-keluar">CHECK-OUT</span>
        </div>
        <div class="card-body">
            <div style="margin-bottom:20px">
                <label class="form-label">Cari Transaksi (Scan Barcode / Input Plat Nomor)</label>
                <div style="display:grid;grid-template-columns: 1fr 1fr auto;gap:10px">
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-barcode"></i></span>
                        <input type="text" id="barcodeInput" class="form-control mono"
                            placeholder="BC00000000"
                            value="<?= esc($barcode_scan) ?>"
                            style="font-size:14px;font-weight:700;letter-spacing:1px">
                    </div>
                    <div class="input-group">
                        <span class="input-group-icon"><i class="fas fa-font"></i></span>
                        <input type="text" id="noPlatInput" class="form-control mono"
                            placeholder="L1234AB (Struk Hilang)"
                            value="<?= esc($no_plat_scan) ?>"
                            style="font-size:14px;font-weight:700;letter-spacing:1px;text-transform:uppercase">
                    </div>
                    <button class="btn btn-primary" onclick="cariTransaksi()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>

            <?php if ($transaksi): ?>
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
                                <span id="total-display" data-tarif-normal="<?= $transaksi['estimasi_biaya'] ?>">Rp <?= number_format($transaksi['estimasi_biaya'], 0, ',', '.') ?></span>
                            </div>
                            <div style="font-size:11px;color:var(--text-muted)">*Dihitung saat pembayaran</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px">Gerbang Masuk</div>
                            <div style="font-weight:600"><?= esc($transaksi['nama_gerbang']) ?></div>
                        </div>
                    </div>
                </div>

                <form method="post" action="<?= base_url('transaksi/proses-keluar') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">

                    <div style="margin-bottom: 16px; padding: 12px; border: 1px dashed #dc3545; background: #fff5f5; border-radius: var(--radius-md);">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #dc3545; font-weight: bold; margin: 0;">
                            <input type="checkbox" name="struk_hilang" id="struk_hilang" value="1" style="width: 18px; height: 18px; accent-color: #dc3545;">
                            <span style="font-size: 13px;"><i class="fas fa-exclamation-triangle"></i> Pengendara Kehilangan Struk (Denda Rp 25.000)</span>
                        </label>
                    </div>

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
                    <h3>Pencarian Kendaraan Keluar</h3>
                    <p>Scan barcode tiket atau masukkan plat nomor jika pengendara kehilangan struk bukti masuk.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

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
        const noPlat = document.getElementById('noPlatInput').value.trim();

        let url = '<?= base_url('transaksi/keluar') ?>';

        if (barcode) {
            window.location.href = url + '?barcode=' + encodeURIComponent(barcode);
        } else if (noPlat) {
            window.location.href = url + '?no_plat=' + encodeURIComponent(noPlat);
        }
    }

    // Trigger pencarian jika menekan Enter di salah satu input
    document.getElementById('barcodeInput')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') cariTransaksi();
    });
    document.getElementById('noPlatInput')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') cariTransaksi();
    });

    // Update nominal total bayar secara real-time saat checkbox denda dicentang kasir
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxStruk = document.getElementById('struk_hilang');
        const totalDisplay = document.getElementById('total-display');

        if (checkboxStruk && totalDisplay) {
            const tarifNormal = parseInt(totalDisplay.getAttribute('data-tarif-normal'));

            checkboxStruk.addEventListener('change', function() {
                let totalAkhir = tarifNormal;
                if (this.checked) {
                    totalAkhir += 25000; // Akumulasi biaya denda flat karcis ilang
                }
                totalDisplay.innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');
            });
        }
    });
</script>
<?= $this->endSection() ?>