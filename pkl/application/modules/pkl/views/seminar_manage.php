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
        <h4 class="mb-4 text-muted">Mahasiswa: <?= html_escape($student->name) ?></h4>

        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-7">
                <!-- Scheduling Form -->
                <?php if ($application->status === 'seminar_approved') : ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">Langkah 2: Jadwalkan Seminar</div>
                        <div class="card-body">
                            <p>Laporan mahasiswa telah disetujui. Silakan tentukan jadwal seminar.</p>
                            <?= form_open('pkl/seminar/save_schedule/' . $application->id); ?>
                            <div class="mb-3">
                                <label for="seminar_date" class="form-label">Tanggal & Waktu Seminar <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="seminar_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="seminar_location" class="form-label">Lokasi Seminar <span class="text-danger">*</span></label>
                                <input type="text" name="seminar_location" class="form-control" placeholder="Contoh: Ruang Rapat Departemen" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Assessment Form -->
                <?php if ($application->status === 'seminar_scheduled') : ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-dark text-white">Langkah 3: Penilaian Seminar</div>
                        <div class="card-body">
                            <p>Seminar telah dilaksanakan. Silakan isi form penilaian dan unggah berita acara.</p>
                            <?= form_open_multipart('pkl/seminar/save_assessment/' . $application->id); ?>
                            <h5 class="mt-3">Komponen Penilaian Seminar</h5>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-sm-5 col-form-label">Nilai Presentasi <span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="number" min="0" max="100" name="presentasi" class="form-control" placeholder="0-100" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-5 col-form-label">Nilai Penguasaan Materi <span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="number" min="0" max="100" name="penguasaan" class="form-control" placeholder="0-100" required>
                                </div>
                            </div>

                            <h5 class="mt-4">Dokumen</h5>
                            <hr>
                            <div class="mb-3">
                                <label for="berita_acara_file" class="form-label">Unggah Berita Acara Seminar (PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="berita_acara_file" class="form-control" accept=".pdf" required>
                            </div>

                            <button type="submit" class="btn btn-success">Simpan Penilaian</button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Start Revision Process -->
                <?php if ($application->status === 'seminar_completed') : ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning text-dark">Langkah 4: Mulai Tahap Revisi</div>
                        <div class="card-body">
                            <p>Penilaian seminar telah disimpan. Jika mahasiswa memerlukan revisi, silakan tuliskan catatan revisi di bawah ini dan mulai tahap revisi. Jika tidak ada revisi, Anda dapat langsung menyetujui laporan akhir.</p>
                            <?= form_open('pkl/seminar/start_revision/' . $application->id); ?>
                            <div class="mb-3">
                                <label class="form-label">Catatan Revisi (Opsional)</label>
                                <textarea name="remarks" class="form-control" rows="4" placeholder="Tuliskan catatan revisi untuk mahasiswa..."></textarea>
                            </div>
                            <button type="submit" name="action" value="start_revision" class="btn btn-warning">Mulai Revisi</button>
                            <button type="submit" name="action" value="no_revision" class="btn btn-success" onclick="return confirm('Apakah Anda yakin tidak ada revisi dan laporan akhir sudah dapat disetujui?')">Setujui Tanpa Revisi</button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Final Approval -->
                <?php if ($application->status === 'revision_submitted') : ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-info text-white">Langkah 5: Persetujuan Akhir</div>
                        <div class="card-body">
                            <p>Mahasiswa telah mengunggah laporan hasil revisi. Silakan periksa kembali.</p>
                            <a href="#" class="btn btn-info" target="_blank">Lihat Laporan Revisi</a>
                            <hr>
                            <a href="<?= site_url('pkl/seminar/approve_final_report/' . $application->id) ?>" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan akhir ini?')">Setujui Laporan Akhir</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">Informasi Seminar</div>
                    <div class="card-body">
                        <p>Status Saat Ini: <span class="badge bg-primary"><?= get_status_label($application->status) ?></span></p>
                        <?php if ($application->seminar_date) : ?>
                            <hr>
                            <h5>Jadwal Seminar</h5>
                            <p>
                                <strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($application->seminar_date)) ?><br>
                                <strong>Lokasi:</strong> <?= html_escape($application->seminar_location) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Document List -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">Dokumen Terkait</div>
                    <div class="card-body">
                        <?php if (empty($documents)) : ?>
                            <p class="text-muted">Belum ada dokumen.</p>
                        <?php else : ?>
                            <ul class="list-group">
                                <?php foreach ($documents as $doc) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?= base_url($doc->file_path) ?>" target="_blank">
                                            <?= ucfirst(str_replace('_', ' ', $doc->doc_type)) ?>
                                        </a>
                                        <span class="badge bg-secondary"><?= date('d/m/Y', strtotime($doc->uploaded_at)) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Workflow History -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">Riwayat Proses</div>
                    <div class="card-body">
                        <?php if (empty($workflow)) : ?>
                            <p class="text-muted">Belum ada riwayat proses.</p>
                        <?php else : ?>
                            <div class="timeline">
                                <?php foreach ($workflow as $step) : ?>
                                    <div class="timeline-item">
                                        <div class="d-flex justify-content-between">
                                            <strong><?= html_escape($step->step_name) ?></strong>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($step->action_date)) ?></small>
                                        </div>
                                        <div>
                                            <span class="badge bg-info"><?= html_escape($step->role) ?></span>
                                            <span class="badge bg-<?= $step->status === 'approved' ? 'success' : ($step->status === 'rejected' ? 'danger' : 'secondary') ?>">
                                                <?= html_escape($step->status) ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($step->remarks)) : ?>
                                            <div class="mt-1">
                                                <small class="text-muted"><?= html_escape($step->remarks) ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="<?= site_url('pkl/applications/approvals') ?>" class="btn btn-light">Kembali ke Daftar Approval</a>
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
</style>