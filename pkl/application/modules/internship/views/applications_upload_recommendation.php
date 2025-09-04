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
                <h5 class="card-title mb-0">Unggah Surat Rekomendasi</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Silakan unggah surat rekomendasi PKL yang telah diterbitkan dan ditandatangani untuk mahasiswa terkait.</p>

                <?= form_open_multipart('internship/applications/upload_recommendation/' . $application_id); ?>

                <div class="mb-4">
                    <label for="recommendation_file" class="form-label">File Surat Rekomendasi (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="recommendation_file" id="recommendation_file" class="form-control" accept=".pdf" required>
                    <div class="form-text">Pastikan file yang diunggah sudah benar dan dalam format PDF.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= site_url('internship/applications/all_applications') ?>" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-2"></i>Unggah Surat</button>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->