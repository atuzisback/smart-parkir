<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="table-container">
    <div class="table-header">
        <span class="table-title"><i class="fas fa-map-marked-alt"></i> Data Slot Parkir</span>
        <div class="table-actions">
            <div class="input-group search-input">
                <span class="input-group-icon"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Cari slot..."
                       data-search-table="tbl-slot">
            </div>
            <button class="btn btn-primary" data-modal="modal-tambah">
                <i class="fas fa-plus"></i> Tambah Slot
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="tbl-slot">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Slot</th>
                    <th>Zona</th>
                    <th>Tarif/Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($slots)): ?>
                    <?php foreach ($slots as $i => $s): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td class="mono fw-bold"><?= esc($s['no_slot']) ?></td>
                        <td><?= esc($s['nama_zona']) ?></td>
                        <td class="mono">Rp <?= number_format($s['tarif_jam'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge-status <?= $s['status_tersedia'] ? 'lunas' : 'gagal' ?>">
                                <?= $s['status_tersedia'] ? 'Tersedia' : 'Terisi' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <button class="btn btn-outline btn-sm btn-icon" title="Edit"
                                        onclick="editSlot(<?= esc(json_encode($s)) ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= base_url('master/slot/hapus/'.$s['id_slot']) ?>"
                                   class="btn btn-danger btn-sm btn-icon"
                                   data-confirm="Hapus slot <?= esc($s['no_slot']) ?>?"
                                   title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6"><div class="empty-state"><i class="fas fa-parking"></i><h3>Belum ada slot</h3></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <span>Total: <?= count($slots) ?> slot</span>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-plus text-primary-color"></i> Tambah Slot Parkir</span>
            <button class="modal-close" onclick="document.getElementById('modal-tambah').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="post" action="<?= base_url('master/slot/simpan') ?>">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">No. Slot</label>
                    <input type="text" name="no_slot" class="form-control mono" placeholder="Contoh: A-07" required maxlength="10" style="text-transform:uppercase">
                </div>
                <div class="form-group">
                    <label class="form-label">Zona Parkir</label>
                    <select name="id_zona" class="form-control" required>
                        <option value="">-- Pilih Zona --</option>
                        <?php foreach ($zonas as $z): ?>
                        <option value="<?= $z['id_zona'] ?>"><?= esc($z['nama_zona']) ?> — Rp <?= number_format($z['tarif_jam'], 0, ',', '.') ?>/jam</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Awal</label>
                    <select name="status_tersedia" class="form-control">
                        <option value="1">Tersedia</option>
                        <option value="0">Terisi</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-tambah').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-edit text-primary-color"></i> Edit Slot Parkir</span>
            <button class="modal-close" onclick="document.getElementById('modal-edit').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="post" action="<?= base_url('master/slot/update') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id_slot" id="edit_id_slot">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">No. Slot</label>
                    <input type="text" name="no_slot" id="edit_no_slot" class="form-control mono" required maxlength="10" style="text-transform:uppercase">
                </div>
                <div class="form-group">
                    <label class="form-label">Zona Parkir</label>
                    <select name="id_zona" id="edit_id_zona" class="form-control" required>
                        <?php foreach ($zonas as $z): ?>
                        <option value="<?= $z['id_zona'] ?>"><?= esc($z['nama_zona']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status_tersedia" id="edit_status" class="form-control">
                        <option value="1">Tersedia</option>
                        <option value="0">Terisi</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-edit').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function editSlot(data) {
    document.getElementById('edit_id_slot').value = data.id_slot;
    document.getElementById('edit_no_slot').value = data.no_slot;
    document.getElementById('edit_id_zona').value = data.id_zona;
    document.getElementById('edit_status').value = data.status_tersedia;
    document.getElementById('modal-edit').classList.add('show');
}
</script>
<?= $this->endSection() ?>