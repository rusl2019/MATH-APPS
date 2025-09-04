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
                <h3 class="mb-0"><?= ($form_data) ? 'Form Pengajuan Ulang PKL' : 'Form Pengajuan PKL' ?></h3>
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
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?= form_open_multipart('internship/applications/create'); ?>

        <!-- Student Data Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Mahasiswa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="<?= html_escape($student_detail->name ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="<?= html_escape($student_detail->id ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email UB</label>
                        <input type="email" class="form-control" value="<?= html_escape($student_detail->email ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" class="form-control" value="<?= html_escape($student_detail->study_program ?? '') ?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Details Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Pengajuan PKL</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Semester Pengajuan <span class="text-danger">*</span></label>
                        <select name="semester_id" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            <?php foreach ($semesters ?? [] as $semester) : ?>
                                <option value="<?= $semester->id ?>" <?= ($semester->id == ($form_data->semester_id ?? $active_semester_id)) ? 'selected' : '' ?>>
                                    <?= $semester->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Dosen Pembimbing PKL <span class="text-danger">*</span></label>
                        <select name="lecturer_id" class="form-select" required>
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            <?php foreach ($lecturers ?? [] as $lecturer) : ?>
                                <option value="<?= $lecturer->id ?? '' ?>" <?= ($lecturer->id == ($form_data->lecturer_id ?? '')) ? 'selected' : '' ?>><?= $lecturer->name ?? '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Instansi / Perusahaan <span class="text-danger">*</span></label>
                        <select name="place_id" class="form-select" required>
                            <option value="">-- Pilih Instansi --</option>
                            <?php foreach ($places ?? [] as $place) : ?>
                                <option value="<?= $place->id ?? '' ?>" <?= ($place->id == ($form_data->place_id ?? '')) ? 'selected' : '' ?>><?= $place->name ?? '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor Telepon / WA Aktif <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input type="tel" name="phone_number" class="form-control" required value="<?= html_escape($form_data->phone_number ?? '') ?>" placeholder="8123456789">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Ditujukan Kepada <span class="text-danger">*</span></label>
                        <input type="text" name="addressed_to" class="form-control" placeholder="Contoh: Kepala HRD / Direktur" required value="<?= html_escape($form_data->addressed_to ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kegiatan Setara (Opsional)</label>
                        <input type="text" name="equivalent_activity" class="form-control" placeholder="Isi jika kegiatan setara" value="<?= html_escape($form_data->equivalent_activity ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Periode Kegiatan (Mulai) <span class="text-danger">*</span></label>
                        <input type="date" name="activity_period_start" class="form-control" required value="<?= html_escape($form_data->activity_period_start ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Periode Kegiatan (Selesai) <span class="text-danger">*</span></label>
                        <input type="date" name="activity_period_end" class="form-control" required value="<?= html_escape($form_data->activity_period_end ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Uploads Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Berkas Persyaratan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="portfolio_file" class="form-label">Unggah Portofolio <span class="text-danger">*</span></label>
                    <input type="file" name="portfolio_file" id="portfolio_file" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah dalam format PDF. <?php if (!empty($form_data->portfolio_file)) : ?>File sebelumnya: <?= $form_data->portfolio_file ?><?php endif; ?></div>
                </div>
                <div class="mb-3">
                    <label for="consultation_file" class="form-label">Unggah Lembar Konsultasi Bimbingan PKL <span class="text-danger">*</span></label>
                    <input type="file" name="consultation_file" id="consultation_file" class="form-control" accept=".pdf" required>
                    <div class="form-text">Unggah dalam format PDF. <?php if (!empty($form_data->consultation_file)) : ?>File sebelumnya: <?= $form_data->consultation_file ?><?php endif; ?></div>
                </div>
                <div class="mb-3">
                    <label for="proposal_file" class="form-label">Unggah Proposal (Opsional)</label>
                    <input type="file" name="proposal_file" id="proposal_file" class="form-control" accept=".pdf">
                    <div class="form-text">Unggah dalam format PDF jika diminta oleh instansi. <?php if (!empty($form_data->proposal_file)) : ?>File sebelumnya: <?= $form_data->proposal_file ?><?php endif; ?></div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= site_url('internship/applications') ?>" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </div>
        <?= form_close(); ?>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->