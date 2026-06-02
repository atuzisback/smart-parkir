<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa-solid fa-door-open me-2"></i>Kelola Pos & Gerbang Parkir</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahGerbang">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Gerbang
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Gerbang</th>
                                <th>Jenis Pos</th>
                                <th>Status Operasional</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Gerbang Utara</td>
                                <td><span class="badge bg-secondary">Masuk (In)</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"><i class="fa-solid fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Gerbang Selatan</td>
                                <td><span class="badge bg-dark">Keluar (Out)</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"><i class="fa-solid fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahGerbang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gerbang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('master/simpanGerbang'); ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Gerbang</label>
                        <input type="text" name="nama_gerbang" class="form-control" placeholder="Contoh: Gerbang Barat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Pos</label>
                        <select name="jenis_gerbang" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Masuk">Masuk (In)</option>
                            <option value="Keluar">Keluar (Out)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Gerbang</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>