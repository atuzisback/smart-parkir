<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa-solid fa-users me-2"></i>Kelola Data Pengguna & Petugas</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                    <i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Role / Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($users as $user): ?>

                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($user['username']) ?></td>

                                    <td>
                                        <?php if ($user['role'] == 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php elseif ($user['role'] == 'manajer'): ?>
                                            <span class="badge bg-success">Manajer</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Petugas</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        Tombol Aksi
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                            <td>
                                <?php if (session('id_user') != $user['id_user']): ?>
                                    <a href="<?= base_url('master/hapusUser/' . $user['id_user']) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus user ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('master/simpanUser'); ?>" method="POST">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role / Level</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="manajer">Manajer</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                </div>

            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan User</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>