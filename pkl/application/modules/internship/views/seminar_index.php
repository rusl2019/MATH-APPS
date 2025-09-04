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
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Main Action Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Langkah Selanjutnya</h5>
            </div>
            <div class="card-body">
                <?php switch ($application->status): ?><?php
                                                            case 'field_work_completed': ?>
            <?php
                                                            case 'report_rejected': ?>
                <?php if ($application->status === 'report_rejected') : ?>
                    <div class="alert alert-warning"><strong>Laporan Ditolak!</strong> Silakan perbaiki dan unggah kembali draft laporan Anda.</div>
                <?php else : ?>
                    <p>Anda telah menyelesaikan kegiatan PKL di lapangan. Silakan unggah draft laporan PKL untuk meminta persetujuan seminar dari dosen pembimbing.</p>
                <?php endif; ?>
                <?= form_open_multipart('internship/seminar/upload_report/' . $application->id); ?>
                <div class="mb-3">
                    <label for="report_title" class="form-label">Judul Laporan PKL <span class="text-danger">*</span></label>
                    <input type="text" name="report_title" class="form-control" placeholder="Masukkan judul laporan PKL Anda" required value="<?= html_escape($application->report_title ?? '') ?>">
                    <div class="form-text">Masukkan judul yang akan digunakan untuk laporan PKL Anda.</div>
                </div>
                <div class="mb-3">
                    <label for="report_file" class="form-label">File Laporan PKL (PDF, max 5MB) <span class="text-danger">*</span></label>
                    <input type="file" name="report_file" class="form-control" accept=".pdf" required>
                </div>
                <button type="submit" class="btn btn-primary">Unggah & Ajukan Seminar</button>
                <?= form_close(); ?>
                <?php break; ?>

            <?php
                                                            case 'seminar_requested': ?>
                <p class="text-muted">Draft laporan Anda telah dikirim. Saat ini sedang menunggu persetujuan dari Dosen Pembimbing untuk pelaksanaan seminar. Anda dapat menambahkan catatan konsultasi pada tab di bawah.</p>
                <?php break; ?>

            <?php
                                                            case 'seminar_scheduled': ?>
                <div class="alert alert-success">
                    <h5 class="alert-heading">Seminar Dijadwalkan!</h5>
                    <p>Selamat! Seminar Anda telah dijadwalkan. Detail jadwal dapat dilihat pada tab "Progress & Jadwal". Harap persiapkan diri Anda dengan baik.</p>
                </div>
                <?php break; ?>

            <?php
                                                            case 'seminar_completed': ?>
                <p class="text-muted">Anda telah melaksanakan seminar. Dosen pembimbing akan segera memberikan hasil penilaian dan catatan revisi (jika ada). Silakan tunggu informasi selanjutnya.</p>
                <?php break; ?>

            <?php
                                                            case 'revision': ?>
                <p>Dosen pembimbing Anda telah memberikan catatan revisi. Silakan perbaiki laporan Anda dan unggah versi terbarunya.</p>
                <?php if (!empty($revision_notes->remarks)) : ?>
                    <div class="alert alert-info">
                        <strong>Catatan dari Dosen:</strong>
                        <p class="mb-0"><em><?= nl2br(html_escape($revision_notes->remarks)) ?></em></p>
                    </div>
                <?php endif; ?>
                <?= form_open_multipart('internship/seminar/upload_revision/' . $application->id); ?>
                <div class="mb-3">
                    <label class="form-label">Unggah Laporan Hasil Revisi (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="revision_file" class="form-control" accept=".pdf" required>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Laporan Revisi</button>
                <?= form_close(); ?>
                <?php break; ?>

            <?php
                                                            case 'revision_submitted': ?>
                <p class="text-muted">Laporan hasil revisi telah diunggah. Menunggu persetujuan akhir dari dosen pembimbing.</p>
                <?php break; ?>

            <?php
                                                            case 'finished': ?>
                <div class="alert alert-success text-center">
                    <h4 class="alert-heading">Selamat!</h4>
                    <p class="mb-0">Anda telah menyelesaikan seluruh rangkaian kegiatan PKL. Semua dokumen dan laporan telah tersimpan.</p>
                </div>
                <?php break; ?>

            <?php
                                                            default: ?>
                <p class="text-muted">Status Anda saat ini adalah <strong><?= get_status_label($application->status) ?></strong>. Silakan pantau progress Anda pada tab di bawah.</p>
        <?php endswitch; ?>
            </div>
        </div>

        <!-- Information Tabs -->
        <div class="card shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="seminarTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab">Progress & Jadwal</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="consultation-tab" data-bs-toggle="tab" data-bs-target="#consultation" type="button" role="tab">Lembar Konsultasi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="scores-tab" data-bs-toggle="tab" data-bs-target="#scores" type="button" role="tab">Nilai & Riwayat</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumen</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="seminarTabContent">
                    <!-- Progress & Schedule Tab -->
                    <div class="tab-pane fade show active" id="progress" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Timeline Proses Seminar</h5>
                                <ul class="timeline mt-3">
                                    <?php
                                    $seminar_steps = [
                                        'field_work_completed' => 'Laporan PKL Selesai',
                                        'seminar_requested' => 'Pengajuan Seminar',
                                        'seminar_approved' => 'Seminar Disetujui',
                                        'seminar_scheduled' => 'Seminar Dijadwalkan',
                                        'seminar_completed' => 'Seminar Selesai',
                                        'revision' => 'Revisi Laporan',
                                        'revision_submitted' => 'Revisi Dikirim',
                                        'finished' => 'Selesai'
                                    ];
                                    $current_status = $application->status;
                                    $step_order = array_keys($seminar_steps);
                                    $current_index = array_search($current_status, $step_order);
                                    if ($current_status == 'revision_approved') $current_index = array_search('finished', $step_order);

                                    foreach ($seminar_steps as $status => $label) {
                                        $step_index = array_search($status, $step_order);
                                        $is_active = ($status === $current_status);
                                        $is_completed = ($step_index < $current_index);
                                        $step_class = $is_active ? 'active' : ($is_completed ? 'completed' : '');
                                    ?>
                                        <li class="timeline-item <?= $step_class ?>">
                                            <span class="badge <?= $is_active ? 'bg-warning text-dark' : ($is_completed ? 'bg-success' : 'bg-secondary') ?>">
                                                <?= $label ?>
                                            </span>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Jadwal Seminar</h5>
                                <?php if ($application->seminar_date) : ?>
                                    <dl class="row">
                                        <dt class="col-sm-4">Tanggal</dt>
                                        <dd class="col-sm-8"><?= date('d M Y, H:i', strtotime($application->seminar_date)) ?></dd>
                                        <dt class="col-sm-4">Lokasi</dt>
                                        <dd class="col-sm-8"><?= html_escape($application->seminar_location) ?></dd>
                                        <?php if (!empty($application->report_title)) : ?>
                                            <dt class="col-sm-4">Judul Laporan</dt>
                                            <dd class="col-sm-8"><?= html_escape($application->report_title) ?></dd>
                                        <?php endif; ?>
                                    </dl>
                                <?php else : ?>
                                    <p class="text-muted">Jadwal seminar akan ditampilkan di sini setelah ditetapkan oleh dosen pembimbing.</p>
                                <?php endif; ?>
                                
                                <?php if (in_array($application->status, ['field_work_completed', 'report_rejected', 'seminar_requested'])) : ?>
                                    <div class="mt-4">
                                        <h5>Edit Judul Laporan</h5>
                                        <?= form_open('internship/seminar/update_report_title/' . $application->id); ?>
                                        <div class="mb-3">
                                            <label for="report_title" class="form-label">Judul Laporan PKL</label>
                                            <input type="text" name="report_title" class="form-control" value="<?= html_escape($application->report_title ?? '') ?>" placeholder="Masukkan judul laporan PKL Anda">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Update Judul</button>
                                        <?= form_close(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Consultation Tab -->
                    <div class="tab-pane fade" id="consultation" role="tabpanel">
                        <h5>Lembar Konsultasi</h5>
                        <p>Catat semua proses bimbingan atau konsultasi dengan dosen pembimbing Anda di sini.</p>
                        <!-- Add New Consultation Form -->
                        <?= form_open('internship/seminar/add_lembar_konsultasi/' . $application->id); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="material" class="form-label">Materi <span class="text-danger">*</span></label>
                                        <input type="text" name="material" class="form-control" placeholder="Contoh: Revisi Bab 1" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan dari Dosen (Opsional)</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan atau arahan dari dosen"></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Tambah Catatan</button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                        <!-- Existing Consultations -->
                        <h6>Riwayat Konsultasi:</h6>
                        <?php if (!empty($lembar_konsultasi)) : ?>
                            <div class="list-group">
                                <?php foreach ($lembar_konsultasi as $konsultasi) : ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= html_escape($konsultasi->material) ?></h6>
                                            <small><?= date('d M Y', strtotime($konsultasi->date)) ?></small>
                                        </div>
                                        <?php if (!empty($konsultasi->notes)) : ?>
                                            <p class="mb-1 text-muted"><em>"<?= html_escape($konsultasi->notes) ?>"</em></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">Belum ada riwayat konsultasi yang ditambahkan.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Scores & History Tab -->
                    <div class="tab-pane fade" id="scores" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Rekapitulasi Hasil Penilaian</h5>
                                <?php if ($recap_scores['is_complete']) : ?>
                                    <dl class="row">
                                        <dt class="col-sm-8">Nilai Pembimbingan Lapangan (25%)</dt>
                                        <dd class="col-sm-4 text-end fw-bold"><?= number_format($recap_scores['avg_lapangan'], 2) ?></dd>
                                        <dt class="col-sm-8">Nilai Proses Pembimbingan Dosen (25%)</dt>
                                        <dd class="col-sm-4 text-end fw-bold"><?= number_format($recap_scores['avg_bimbingan'], 2) ?></dd>
                                        <dt class="col-sm-8">Nilai Seminar (50%)</dt>
                                        <dd class="col-sm-4 text-end fw-bold"><?= number_format($recap_scores['avg_seminar'], 2) ?></dd>
                                    </dl>
                                    <hr>
                                    <dl class="row">
                                        <dt class="col-sm-8 h5">NILAI AKHIR</dt>
                                        <dd class="col-sm-4 text-end h5"><?= number_format($recap_scores['final_score'], 2) ?></dd>
                                    </dl>
                                <?php else : ?>
                                    <p class="text-muted">Nilai akhir akan ditampilkan setelah semua komponen penilaian diisi oleh dosen pembimbing.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h5>Riwayat Proses</h5>
                                <?php if (empty($workflow)) : ?>
                                    <p class="text-muted">Belum ada riwayat proses.</p>
                                <?php else : ?>
                                    <div class="timeline-small">
                                        <?php foreach ($workflow as $step) : ?>
                                            <div class="timeline-item-small">
                                                <div class="timeline-item-small-content">
                                                    <p class="mb-0"><strong><?= html_escape($step->step_name) ?></strong></p>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($step->action_date)) ?> oleh <?= html_escape($step->role) ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <h5>Dokumen Terkait Seminar</h5>
                        <?php if (empty($seminar_docs)) : ?>
                            <p class="text-muted">Belum ada dokumen seminar yang diunggah.</p>
                        <?php else : ?>
                            <ul class="list-group">
                                <?php foreach ($seminar_docs as $doc) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?= base_url($doc->file_path) ?>" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            <?= ucfirst(str_replace('_', ' ', $doc->doc_type)) ?>
                                        </a>
                                        <span class="badge bg-info rounded-pill"><?= date('d-m-Y H:i', strtotime($doc->uploaded_at)) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="<?= site_url('internship/applications') ?>" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard PKL</a>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->

<style>
    .timeline {
        list-style: none;
        padding: 0;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 7px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #6c757d;
    }

    .timeline-item.active::before {
        background: #ffc107;
    }

    .timeline-item.completed::before {
        background: #198754;
    }
</style>