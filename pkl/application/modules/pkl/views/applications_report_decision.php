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
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                Laporan Keputusan Instansi
            </div>
            <div class="card-body">
                <p>Silakan pilih keputusan dari instansi terkait pengajuan PKL Anda dan unggah surat balasan resmi.</p>

                <?= form_open_multipart('pkl/applications/report_decision/' . $application->id); ?>

                <div class="mb-4">
                    <h5>Keputusan Instansi</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="decision" id="decision_accepted" value="accepted" required>
                        <label class="form-check-label" for="decision_accepted">
                            <span class="badge bg-success">Diterima</span> oleh Instansi
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="decision" id="decision_rejected" value="rejected" required>
                        <label class="form-check-label" for="decision_rejected">
                            <span class="badge bg-danger">Ditolak</span> oleh Instansi
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="response_letter" class="form-label">Unggah Surat Balasan dari Instansi (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="response_letter" id="response_letter" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah surat resmi dari instansi sebagai bukti keputusan.</div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= site_url('pkl/applications') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->