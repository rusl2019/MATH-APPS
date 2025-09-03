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

        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                Form Laporan Penyelesaian PKL
            </div>
            <div class="card-body">
                <p>Silakan isi form berikut untuk melaporkan bahwa kegiatan PKL Anda telah selesai. Masukkan nilai dari setiap komponen sesuai dengan lembar penilaian yang diberikan oleh pembimbing lapangan.</p>

                <?= form_open_multipart('internship/applications/finish_internship/' . $application_id); ?>

                <h5 class="mt-3">Komponen Penilaian Lapangan</h5>
                <hr>

                <?php
                $criteria = [
                    'pengetahuan' => 'Pengetahuan yang mendukung pekerjaan',
                    'keterampilan' => 'Keterampilan yang mendukung pekerjaan',
                    'inisiatif' => 'Inisiatif',
                    'tanggung_jawab' => 'Tanggung Jawab',
                    'kerjasama_tim' => 'Kerjasama Tim',
                    'kehadiran' => 'Kehadiran',
                    'laporan' => 'Laporan'
                ];
                ?>

                <?php foreach ($criteria as $key => $label) : ?>
                    <div class="row mb-3">
                        <label for="<?= $key ?>" class="col-sm-4 col-form-label"><?= $label ?> <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="number" min="0" max="100" step="1" name="<?= $key ?>" id="<?= $key ?>" class="form-control" placeholder="0-100" required>
                        </div>
                    </div>
                <?php endforeach; ?>

                <h5 class="mt-4">Dokumen Pendukung</h5>
                <hr>

                <div class="mb-4">
                    <label for="certificate_file" class="form-label">Unggah Sertifikat Selesai PKL (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="certificate_file" id="certificate_file" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah sertifikat atau surat keterangan selesai PKL dari instansi.</div>
                </div>

                <div class="mb-4">
                    <label for="evaluation_file" class="form-label">Unggah Lembar Penilaian dari Pembimbing Lapangan (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="evaluation_file" id="evaluation_file" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah lembar penilaian yang sudah diisi dan ditandatangani oleh pembimbing lapangan.</div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= site_url('internship/applications') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin semua data yang diisi sudah benar? Aksi ini akan menyelesaikan proses PKL Anda.')">Kirim Laporan & Selesaikan PKL</button>
                </div>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->