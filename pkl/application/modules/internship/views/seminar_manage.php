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
                            <?= form_open('internship/seminar/save_schedule/' . $application->id); ?>
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
                            <?= form_open_multipart('internship/seminar/save_assessment/' . $application->id); ?>
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
                            <?= form_open('internship/seminar/start_revision/' . $application->id); ?>
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
                            <a href="<?= site_url('internship/seminar/approve_final_report/' . $application->id) ?>" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan akhir ini?')">Setujui Laporan Akhir</a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Lembar Konsultasi Section -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Lembar Konsultasi</h5>
                    </div>
                    <div class="card-body">
                        <p>Daftar konsultasi dengan mahasiswa terkait laporan PKL dan seminar.</p>
                        
                        <!-- Add New Consultation Form -->
                        <?= form_open('internship/seminar/add_lembar_konsultasi/' . $application->id); ?>
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Tambah Lembar Konsultasi</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="material" class="form-label">Materi <span class="text-danger">*</span></label>
                                        <input type="text" name="material" class="form-control" placeholder="Materi konsultasi" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan"></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                        
                        <!-- Existing Consultations -->
                        <?php if (!empty($lembar_konsultasi)) : ?>
                            <div class="mt-3">
                                <h6>Riwayat Konsultasi:</h6>
                                <?php foreach ($lembar_konsultasi as $konsultasi) : ?>
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong><?= date('d M Y', strtotime($konsultasi->date)) ?></strong>
                                                    <div><?= html_escape($konsultasi->material) ?></div>
                                                </div>
                                                <div class="d-flex">
                                                    <?php if (!empty($konsultasi->notes)) : ?>
                                                        <small class="text-muted me-2"><?= html_escape($konsultasi->notes) ?></small>
                                                    <?php endif; ?>
                                                    <div>
                                                        <!-- Edit and Delete buttons -->
                                                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $konsultasi->id ?>">Edit</button>
                                                        <a href="<?= site_url('internship/seminar/delete_lembar_konsultasi/' . $konsultasi->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lembar konsultasi ini?')">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?= $konsultasi->id ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <?= form_open('internship/seminar/edit_lembar_konsultasi/' . $konsultasi->id); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Lembar Konsultasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                                        <input type="date" name="date" class="form-control" value="<?= $konsultasi->date ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="material" class="form-label">Materi <span class="text-danger">*</span></label>
                                                        <input type="text" name="material" class="form-control" value="<?= html_escape($konsultasi->material) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes" class="form-label">Catatan</label>
                                                        <textarea name="notes" class="form-control" rows="3"><?= html_escape($konsultasi->notes) ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info">Belum ada lembar konsultasi yang ditambahkan.</div>
                        <?php endif; ?>
                    </div>
                </div>
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
                                            <span class="badge bg-<?= $step->status === 'approved' ? 'bg-success' : ($step->status === 'rejected' ? 'danger' : 'secondary') ?>">
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

                <!-- Logbook Card -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title">Logbook Mahasiswa</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs" id="logbookTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="daily-log-tab" data-bs-toggle="tab" data-bs-target="#daily-log" type="button" role="tab" aria-controls="daily-log" aria-selected="true">Logbook Harian</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="weekly-log-tab" data-bs-toggle="tab" data-bs-target="#weekly-log" type="button" role="tab" aria-controls="weekly-log" aria-selected="false">Logbook Mingguan (TTD)</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="logbookTabContent">
                            <div class="tab-pane fade show active p-3" id="daily-log" role="tabpanel" aria-labelledby="daily-log-tab">
                                <?php if (empty($daily_logs)) : ?>
                                    <p class="text-muted">Mahasiswa belum mengisi logbook harian.</p>
                                <?php else : ?>
                                    <div class="table-responsive" style="max-height: 400px;">
                                        <table class="table table-sm table-bordered table-hover">
                                            <thead class="table-light position-sticky top-0">
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Kegiatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($daily_logs as $log) : ?>
                                                    <tr>
                                                        <td style="width:100px;"><?= date('d/m/Y', strtotime($log->date)) ?></td>
                                                        <td>
                                                            <strong><?= html_escape($log->activity_title) ?></strong><br>
                                                            <small><?= nl2br(html_escape($log->activity_description)) ?></small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade p-3" id="weekly-log" role="tabpanel" aria-labelledby="weekly-log-tab">
                                <?php if (empty($weekly_logs)) : ?>
                                    <p class="text-muted">Mahasiswa belum mengunggah logbook mingguan.</p>
                                <?php else : ?>
                                    <ul class="list-group">
                                        <?php foreach ($weekly_logs as $log) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Minggu ke-<?= $log->week_number ?></strong>
                                                    <span class="badge bg-<?= $log->status == 'approved' ? 'success' : ($log->status == 'rejected' ? 'danger' : 'secondary') ?>"><?= ucfirst($log->status) ?></span>
                                                </div>
                                                <a href="<?= base_url($log->file_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="<?= site_url('internship/applications/approvals') ?>" class="btn btn-light">Kembali ke Daftar Approval</a>
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