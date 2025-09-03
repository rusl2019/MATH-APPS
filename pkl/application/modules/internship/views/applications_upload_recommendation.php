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
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                Unggah Surat Rekomendasi
            </div>
            <div class="card-body">
                <p>Silakan unggah surat rekomendasi dalam format PDF.</p>

                <?= form_open_multipart('internship/applications/upload_recommendation/' . $application_id); ?>

                <div class="mb-4">
                    <label class="form-label">Unggah Surat Rekomendasi (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="recommendation_file" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah surat rekomendasi yang telah diterbitkan dan ditandatangani.</div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= site_url('internship/applications/all_applications') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Unggah Surat</button>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->