<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0"><?php echo $title; ?></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Laporan Keputusan Instansi</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Silakan pilih keputusan dari instansi terkait pengajuan PKL Anda dan unggah surat balasan resmi sebagai bukti.</p>

                <?= form_open_multipart('internship/applications/report_decision/' . $application->id); ?>

                <div class="mb-4">
                    <h6 class="form-label">Keputusan Instansi <span class="text-danger">*</span></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="decision" id="decision_accepted" value="accepted" required>
                                <label class="form-check-label" for="decision_accepted">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span>Diterima</span>
                                    <small class="d-block">Pilih jika instansi menerima pengajuan PKL Anda.</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="decision" id="decision_rejected" value="rejected" required>
                                <label class="form-check-label" for="decision_rejected">
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                    <span>Ditolak</span>
                                    <small class="d-block">Pilih jika instansi menolak pengajuan PKL Anda.</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="response_letter" class="form-label">Unggah Surat Balasan dari Instansi (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="response_letter" id="response_letter" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah surat balasan resmi dari instansi sebagai bukti keputusan.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= site_url('internship/applications') ?>" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Kirim Laporan</button>
                </div>

                <?= form_close(); ?>
            </div>
        </div>

<style>
.card-radio {
    border: 1px solid #dee2e6;
    border-radius: .375rem;
    padding: 1rem;
    cursor: pointer;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
.card-radio:hover {
    border-color: #86b7fe;
}
.card-radio input[type="radio"] {
    display: none;
}
.card-radio input[type="radio"]:checked + .form-check-label {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
.form-check-label {
    width: 100%;
    border: 1px solid transparent;
    border-radius: .375rem;
    padding: 1rem;
}
.form-check-label span {
    font-size: 1.1rem;
    font-weight: 500;
}
.form-check-label i {
    font-size: 1.5rem;
    margin-right: 0.5rem;
}
</style>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->