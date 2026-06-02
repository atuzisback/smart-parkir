<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body bg-light d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-primary"><i class="fa-solid fa-desktop me-2"></i>Monitoring Slot Parkir Live</h4>
                <div>
                    <span class="badge bg-success me-2 px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Tersedia</span>
                    <span class="badge bg-danger px-3 py-2"><i class="fa-solid fa-circle-xmark me-1"></i> Terisi</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row text-center">
    <div class="col-6 col-md-3 mb-4">
        <div class="card bg-success text-white shadow-sm border-0 h-100 py-4">
            <div class="card-body">
                <h2 class="display-6 fw-bold">A-01</h2>
                <p class="card-text mb-0"><i class="fa-solid fa-square-parking fa-2x mt-2"></i></p>
                <small class="d-block mt-2">KOSONG (TERSEDIA)</small>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3 mb-4">
        <div class="card bg-danger text-white shadow-sm border-0 h-100 py-4">
            <div class="card-body">
                <h2 class="display-6 fw-bold">A-02</h2>
                <p class="card-text mb-0"><i class="fa-solid fa-car fa-2x mt-2"></i></p>
                <small class="d-block mt-2">TERISI - L 1234 AB</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card bg-success text-white shadow-sm border-0 h-100 py-4">
            <div class="card-body">
                <h2 class="display-6 fw-bold">A-03</h2>
                <p class="card-text mb-0"><i class="fa-solid fa-square-parking fa-2x mt-2"></i></p>
                <small class="d-block mt-2">KOSONG (TERSEDIA)</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card bg-success text-white shadow-sm border-0 h-100 py-4">
            <div class="card-body">
                <h2 class="display-6 fw-bold">A-04</h2>
                <p class="card-text mb-0"><i class="fa-solid fa-square-parking fa-2x mt-2"></i></p>
                <small class="d-block mt-2">KOSONG (TERSEDIA)</small>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>