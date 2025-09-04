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
        <h4 class="mb-4 text-muted">Mahasiswa: <span class="fw-bold"><?= html_escape($student->name) ?> (<?= html_escape($student->id) ?>)</span></h4>

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

        <!-- Action Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Tindakan Diperlukan</h5>
            </div>
            <div class="card-body">
                <?php switch ($application->status): ?><?php case 'seminar_requested': ?>
                    <p>Mahasiswa telah mengunggah draf laporan dan meminta persetujuan seminar. Silakan periksa laporan, berikan penilaian proses bimbingan (Form B-5), lalu setujui atau tolak pengajuan seminar.</p>
                    <a href="<?= base_url($documents[0]->file_path) ?>" class="btn btn-info mb-3" target="_blank"><i class="bi bi-file-earmark-pdf me-2"></i>Lihat Draft Laporan</a>
                    <hr>
                    <h5>Penilaian Proses Pembimbingan (Form B-5)</h5>
                    <?= form_open('internship/seminar/save_guidance_assessment/' . $application->id); ?>
                    <?php
                    $bimbingan_criteria = [
                        'bimbingan_proses' => ['label' => 'Proses bimbingan', 'weight' => '20%'],
                        'bimbingan_disiplin' => ['label' => 'Kedisiplinan bimbingan', 'weight' => '10%'],
                        'bimbingan_topik' => ['label' => 'Pemilihan topik', 'weight' => '10%'],
                        'bimbingan_relevansi' => ['label' => 'Relevansi topik', 'weight' => '15%'],
                        'bimbingan_pembahasan' => ['label' => 'Pembahasan', 'weight' => '30%'],
                        'bimbingan_tata_tulis' => ['label' => 'Tata tulis & bahasa', 'weight' => '15%']
                    ];
                    ?>
                    <?php foreach ($bimbingan_criteria as $key => $props) : ?>
                        <div class="row mb-2">
                            <label class="col-sm-5 col-form-label"><?= $props['label'] ?></label>
                            <div class="col-sm-7">
                                <input type="number" min="0" max="100" name="<?= $key ?>" class="form-control" placeholder="0-100" value="<?= html_escape($guidance_scores[$key] ?? '') ?>" required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Simpan Penilaian Bimbingan</button>
                    </div>
                    <?= form_close(); ?>
                    <hr class="my-4">
                    <div class="text-center">
                        <h5>Tindakan Seminar</h5>
                        <p class="text-muted small">Setelah menyimpan penilaian di atas, Anda dapat menyetujui seminar atau menolak laporan.</p>
                        <a href="<?= site_url('internship/seminar/approve/' . $application->id) ?>" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan ini untuk seminar?')">Setujui Seminar</a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectReportModal">Tolak Laporan</button>
                    </div>
                    <?php break; ?>

                <?php case 'seminar_approved': ?>
                    <p>Laporan mahasiswa telah disetujui. Silakan tentukan jadwal dan lokasi seminar.</p>
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
                    <?php break; ?>

                <?php case 'seminar_scheduled': ?>
                    <p>Seminar telah dijadwalkan. Setelah seminar dilaksanakan, silakan isi form penilaian seminar (Form D-1).</p>
                    <?= form_open_multipart('internship/seminar/save_assessment/' . $application->id); ?>
                    <h5 class="mt-3">Penilaian Seminar (Form D-1)</h5>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-sm-5 col-form-label">Kualitas Presentasi (Bobot 40%) <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="number" min="0" max="100" name="kualitas_presentasi" class="form-control" placeholder="0-100" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 col-form-label">Kualitas Diskusi (Bobot 20%) <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="number" min="0" max="100" name="kualitas_diskusi" class="form-control" placeholder="0-100" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 col-form-label">Penguasaan Materi (Bobot 40%) <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="number" min="0" max="100" name="penguasaan_materi" class="form-control" placeholder="0-100" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Penilaian Seminar</button>
                    <?= form_close(); ?>
                    <?php break; ?>

                <?php case 'seminar_completed': ?>
                    <p>Penilaian seminar telah disimpan. Jika mahasiswa memerlukan revisi, tuliskan catatan dan mulai tahap revisi. Jika tidak, Anda dapat langsung menyetujui laporan akhir.</p>
                    <?= form_open('internship/seminar/start_revision/' . $application->id); ?>
                    <div class="mb-3">
                        <label class="form-label">Catatan Revisi (Form D-4)</label>
                        <textarea name="remarks" class="form-control" rows="4" placeholder="Tuliskan catatan revisi untuk mahasiswa. Kosongkan jika tidak ada revisi."></textarea>
                    </div>
                    <button type="submit" name="action" value="start_revision" class="btn btn-warning">Mulai Revisi</button>
                    <button type="submit" name="action" value="no_revision" class="btn btn-success" onclick="return confirm('Apakah Anda yakin tidak ada revisi dan laporan akhir sudah dapat disetujui?')">Setujui Tanpa Revisi</button>
                    <?= form_close(); ?>
                    <?php break; ?>

                <?php case 'revision_submitted': ?>
                    <p>Mahasiswa telah mengunggah laporan hasil revisi. Silakan periksa kembali, lalu berikan persetujuan akhir.</p>
                    <a href="#" class="btn btn-info mb-3" target="_blank"><i class="bi bi-file-earmark-pdf me-2"></i>Lihat Laporan Revisi</a>
                    <hr>
                    <a href="<?= site_url('internship/seminar/approve_final_report/' . $application->id) ?>" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan akhir ini? Proses PKL akan selesai.')"><i class="bi bi-check-circle me-2"></i>Setujui Laporan Akhir (Form E-1)</a>
                    <?php break; ?>

                <?php default: ?>
                    <p class="text-muted">Status saat ini: <strong><?= get_status_label($application->status) ?></strong>. Tidak ada tindakan yang diperlukan dari Anda saat ini.</p>
                <?php endswitch; ?>
            </div>
        </div>

        <!-- Information Tabs -->
        <div class="card shadow-sm">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="manageSeminarTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">Detail & Dokumen</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logbook-tab" data-bs-toggle="tab" data-bs-target="#logbook" type="button">Logbook</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="consultation-tab" data-bs-toggle="tab" data-bs-target="#consultation" type="button">Bimbingan & Konsultasi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">Riwayat & Nilai</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="manageSeminarTabContent">
                    <!-- Details & Documents Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Detail Pengajuan</h5>
                                <dl class="row">
                                    <dt class="col-sm-5">Nama Mahasiswa</dt><dd class="col-sm-7"><?= html_escape($student->name) ?></dd>
                                    <dt class="col-sm-5">NIM</dt><dd class="col-sm-7"><?= html_escape($student->id) ?></dd>
                                    <dt class="col-sm-5">Program Studi</dt><dd class="col-sm-7"><?= html_escape($student->study_program) ?></dd>
                                    <dt class="col-sm-5">Instansi</dt><dd class="col-sm-7"><?= html_escape($application->place_name) ?></dd>
                                    <dt class="col-sm-5">Periode</dt><dd class="col-sm-7"><?= date('d M Y', strtotime($application->activity_period_start)) ?> - <?= date('d M Y', strtotime($application->activity_period_end)) ?></dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h5>Dokumen Terkait</h5>
                                <?php if (empty($documents)) : ?>
                                    <p class="text-muted">Belum ada dokumen.</p>
                                <?php else : ?>
                                    <ul class="list-group">
                                        <?php foreach ($documents as $doc) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="<?= base_url($doc->file_path) ?>" target="_blank"><i class="bi bi-file-earmark-text me-2"></i><?= ucfirst(str_replace('_', ' ', $doc->doc_type)) ?></a>
                                                <span class="badge bg-secondary"><?= date('d/m/Y', strtotime($doc->uploaded_at)) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Logbook Tab -->
                    <div class="tab-pane fade" id="logbook" role="tabpanel">
                        <ul class="nav nav-pills mb-3" id="logbookTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="daily-log-tab" data-bs-toggle="tab" data-bs-target="#daily-log" type="button">Logbook Harian</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="weekly-log-tab" data-bs-toggle="tab" data-bs-target="#weekly-log" type="button">Logbook Mingguan (TTD)</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="logbookTabContent">
                            <div class="tab-pane fade show active" id="daily-log" role="tabpanel">
                                <?php if (empty($daily_logs)) : ?>
                                    <p class="text-muted">Mahasiswa belum mengisi logbook harian.</p>
                                <?php else : ?>
                                    <div class="table-responsive" style="max-height: 400px;">
                                        <table class="table table-sm table-bordered table-hover">
                                            <thead class="table-light position-sticky top-0">
                                                <tr><th>Tanggal</th><th>Kegiatan</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($daily_logs as $log) : ?>
                                                    <tr>
                                                        <td style="width:100px;"><?= date('d/m/Y', strtotime($log->date)) ?></td>
                                                        <td><strong><?= html_escape($log->activity_title) ?></strong><br><small><?= nl2br(html_escape($log->activity_description)) ?></small></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane fade" id="weekly-log" role="tabpanel">
                                <?php if (empty($weekly_logs)) : ?>
                                    <p class="text-muted">Mahasiswa belum mengunggah logbook mingguan.</p>
                                <?php else : ?>
                                    <ul class="list-group">
                                        <?php foreach ($weekly_logs as $log) : ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div><strong>Minggu ke-<?= $log->week_number ?></strong> <span class="badge bg-<?= $log->status == 'approved' ? 'success' : ($log->status == 'rejected' ? 'danger' : 'secondary') ?>"><?= ucfirst($log->status) ?></span></div>
                                                <a href="<?= base_url($log->file_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Consultation Tab -->
                    <div class="tab-pane fade" id="consultation" role="tabpanel">
                        <h5>Lembar Konsultasi</h5>
                        <p>Catat semua proses bimbingan atau konsultasi dengan mahasiswa di sini.</p>
                        <?= form_open('internship/seminar/add_lembar_konsultasi/' . $application->id); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">Tanggal <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" required></div>
                                    <div class="col-md-8 mb-3"><label class="form-label">Materi <span class="text-danger">*</span></label><input type="text" name="material" class="form-control" placeholder="Materi konsultasi" required></div>
                                </div>
                                <div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan"></textarea></div>
                                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                        <h6>Riwayat Konsultasi:</h6>
                        <?php if (!empty($lembar_konsultasi)) : ?>
                            <div class="list-group">
                                <?php foreach ($lembar_konsultasi as $konsultasi) : ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= html_escape($konsultasi->material) ?></h6>
                                            <small><?= date('d M Y', strtotime($konsultasi->date)) ?></small>
                                        </div>
                                        <?php if (!empty($konsultasi->notes)) : ?><p class="mb-1 text-muted"><em>"<?= html_escape($konsultasi->notes) ?>"</em></p><?php endif; ?>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $konsultasi->id ?>">Edit</button>
                                            <a href="<?= site_url('internship/seminar/delete_lembar_konsultasi/' . $konsultasi->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">Belum ada riwayat konsultasi.</p>
                        <?php endif; ?>
                    </div>

                    <!-- History & Scores Tab -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Rekapitulasi Hasil Penilaian</h5>
                                <?php if ($recap_scores['is_complete']) : ?>
                                    <dl class="row">
                                        <dt class="col-sm-8">Nilai Lapangan (25%)</dt><dd class="col-sm-4 text-end fw-bold"><?= number_format($recap_scores['avg_lapangan'], 2) ?></dd>
                                        <dt class="col-sm-8">Nilai Bimbingan (25%)</dt><dd class="col-sm-4 text-end fw-bold"><?= number_format($recap_scores['avg_bimbingan'], 2) ?></dd>
                                        <dt class="col-sm-8">Nilai Seminar (50%)</dt><dd class="col-sm-4 text-end fw-bold"><?= number_format($recap_scores['avg_seminar'], 2) ?></dd>
                                    </dl>
                                    <hr>
                                    <dl class="row"><dt class="col-sm-8 h5">NILAI AKHIR</dt><dd class="col-sm-4 text-end h5"><?= number_format($recap_scores['final_score'], 2) ?></dd></dl>
                                <?php else : ?>
                                    <p class="text-muted">Nilai akhir akan ditampilkan setelah semua komponen penilaian diisi.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h5>Riwayat Proses</h5>
                                <?php if (empty($workflow)) : ?>
                                    <p class="text-muted">Belum ada riwayat.</p>
                                <?php else : ?>
                                    <div class="timeline-small">
                                        <?php foreach ($workflow as $step) : ?>
                                            <div class="timeline-item-small"><div class="timeline-item-small-content"><p class="mb-0"><strong><?= html_escape($step->step_name) ?></strong></p><small class="text-muted"><?= date('d/m/Y H:i', strtotime($step->action_date)) ?> oleh <?= html_escape($step->role) ?></small></div></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <?php foreach ($lembar_konsultasi as $konsultasi) : ?>
            <div class="modal fade" id="editModal<?= $konsultasi->id ?>" tabindex="-1">
                <div class="modal-dialog"><div class="modal-content">
                    <?= form_open('internship/seminar/edit_lembar_konsultasi/' . $konsultasi->id); ?>
                    <div class="modal-header"><h5 class="modal-title">Edit Lembar Konsultasi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Tanggal <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" value="<?= $konsultasi->date ?>" required></div>
                        <div class="mb-3"><label class="form-label">Materi <span class="text-danger">*</span></label><input type="text" name="material" class="form-control" value="<?= html_escape($konsultasi->material) ?>" required></div>
                        <div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="3"><?= html_escape($konsultasi->notes) ?></textarea></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
                    <?= form_close(); ?>
                </div></div>
            </div>
        <?php endforeach; ?>
        <div class="modal fade" id="rejectReportModal" tabindex="-1">
            <div class="modal-dialog"><div class="modal-content">
                <?= form_open(site_url('internship/seminar/reject_report/' . $application->id)); ?>
                <div class="modal-header"><h5 class="modal-title">Alasan Penolakan Laporan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Berikan alasan atau catatan revisi:</label><textarea name="remarks" class="form-control" rows="4" placeholder="Tuliskan alasan penolakan atau catatan untuk revisi..." required></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Tolak Laporan</button></div>
                <?= form_close(); ?>
            </div></div>
        </div>

        <div class="mt-4">
            <a href="<?= site_url('internship/applications/approvals') ?>" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Approval</a>
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