<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-sign-in-alt"></i> Input Kendaraan Masuk</span>
            <span class="tag tag-masuk">CHECK-IN</span>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('transaksi/proses-masuk') ?>">
                <?= csrf_field() ?>
                
                <input type="hidden" name="id_user" value="<?= session()->get('id_user') ?? 1; ?>">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-car text-primary-color"></i> No. Plat Kendaraan</label>
                        <input type="text" name="no_plat" class="form-control mono"
                               placeholder="Contoh: B1234XYZ" maxlength="10"
                               value="<?= old('no_plat') ?>" required
                               style="text-transform:uppercase;font-size:16px;font-weight:700;letter-spacing:1px">
                        <small class="text-muted">Deteksi otomatis via ANPR atau input manual</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Motor" <?= old('jenis_kendaraan') == 'Motor' ? 'selected' : '' ?>>Motor</option>
                            <option value="Mobil" <?= old('jenis_kendaraan') == 'Mobil' ? 'selected' : '' ?>>Mobil</option>
                            <option value="Truk" <?= old('jenis_kendaraan') == 'Truk' ? 'selected' : '' ?>>Truk</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Gerbang Masuk</label>
                        <select name="id_gerbang" class="form-control" required>
                            <option value="">-- Pilih Gerbang --</option>
                            <?php foreach ($gerbang_masuk as $g): ?>
                            <option value="<?= $g['id_gerbang'] ?>" <?= old('id_gerbang') == $g['id_gerbang'] ? 'selected' : '' ?>>
                                <?= esc($g['nama_gerbang']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Slot Parkir</label>
                        <select name="id_slot" class="form-control" required>
                            <option value="">-- Pilih Slot --</option>
                            <?php foreach ($slot_tersedia as $s): ?>
                            <option value="<?= $s['id_slot'] ?>" <?= old('id_slot') == $s['id_slot'] ? 'selected' : '' ?>>
                                <?= esc($s['no_slot']) ?> — <?= esc($s['nama_zona']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted"><?= count($slot_tersedia) ?> slot tersedia</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Simulasi Kamera LPR</label>
                    <div class="lpr-preview" id="lprPreview"
                         style="background:#0F172A;border-radius:var(--radius);height:160px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;cursor:pointer;border:2px dashed #334155"
                         onclick="simulateLPR()">
                        <i class="fas fa-camera" style="font-size:32px;color:#64748B"></i>
                        <span style="color:#64748B;font-size:13px">Klik untuk simulasi kamera ANPR</span>
                    </div>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
                    <button type="reset" class="btn btn-outline">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check-circle"></i> Cetak Struk & Buka Palang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-pie"></i> Kapasitas Real-time</span>
            </div>
            <div class="card-body" style="padding:16px">
                <div style="text-align:center;padding:16px 0">
                    <div style="font-size:48px;font-weight:800;color:var(--primary)"><?= count($slot_tersedia) ?></div>
                    <div style="color:var(--text-muted);font-size:13px">Slot Tersedia</div>
                </div>
                <div style="background:var(--surface-2);border-radius:var(--radius);padding:12px;font-size:13px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span class="text-muted">Tersedia</span>
                        <strong style="color:var(--success)"><?= count($slot_tersedia) ?></strong>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <span class="text-muted">Status</span>
                        <span class="badge-status <?= count($slot_tersedia) > 0 ? 'lunas' : 'gagal' ?>">
                            <?= count($slot_tersedia) > 0 ? 'Ada Slot' : 'Penuh' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="border-color:#DBEAFE;background:#EFF6FF">
            <div class="card-body" style="padding:16px">
                <div style="font-weight:700;color:var(--primary);margin-bottom:10px;font-size:13px">
                    <i class="fas fa-info-circle"></i> Panduan Penggunaan
                </div>
                <ol style="font-size:12.5px;color:var(--text-secondary);line-height:1.8;padding-left:16px">
                    <li>Arahkan kamera ke plat nomor kendaraan</li>
                    <li>Pilih jenis kendaraan sesuai kategori</li>
                    <li>Tentukan gerbang dan slot yang tersedia</li>
                    <li>Tekan tombol cetak untuk generate barcode</li>
                    <li>Berikan struk kepada pengendara</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function simulateLPR() {
    const plates = ['B1234XYZ', 'D5678ABC', 'B9999VIP', 'B7777CAR', 'D3333MOT', 'F2468ZZZ'];
    const plate = plates[Math.floor(Math.random() * plates.length)];
    const input = document.querySelector('input[name="no_plat"]');
    if (input) {
        input.value = plate;
        const preview = document.getElementById('lprPreview');
        preview.innerHTML = `
            <div style="background:#0F172A;width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border-radius:var(--radius)">
                <i class="fas fa-check-circle" style="font-size:28px;color:#10B981"></i>
                <div style="font-family:'JetBrains Mono',monospace;font-size:22px;font-weight:800;color:#fff;letter-spacing:3px">\${plate}</div>
                <span style="color:#64748B;font-size:11px">Plat terdeteksi ✓</span>
            </div>
        `;
    }
}
</script>
<?= $this->endSection() ?>