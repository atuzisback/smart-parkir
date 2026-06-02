<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa-solid fa-tags me-2"></i>Kelola Zona & Tarif Parkir</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahZona">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Zona
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Zona</th>
                                <th>Jenis Kendaraan</th>
                                <th>Tarif Per Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mobil - Utama</td>
                                <td>Mobil</td>
                                <td>Rp 5.000</td>
                                <td>
                                    <button class="btn btn-warning btn-sm"><i class="fa-solid fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Motor - Regular</td>
                                <td>Motor</td>
                                <td>Rp 2.000</td>
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

<div class="modal fade" id="modalTambahZona" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Zona Parkir Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('master/simpanZona'); ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Zona</label>
                        <input type="text" name="nama_zona" class="form-control" placeholder="Contoh: Mobil VIP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarif (Per Jam)</label>
                        <input type="number" name="tarif" class="form-control" placeholder="Contoh: 3000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Zona</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>