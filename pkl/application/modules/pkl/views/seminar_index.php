<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Left Column: Status and Timeline -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Progress Seminar</div>
                <div class="card-body">
                    <p>Status saat ini:
                        <span class="badge bg-primary"><?= get_status_label($application->status) ?></span>
                    </p>

                    <!-- Detailed timeline for seminar process -->
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
                            'revision_approved' => 'Revisi Disetujui',
                            'finished' => 'Selesai'
                        ];

                        $current_status = $application->status;
                        $step_order = array_keys($seminar_steps);
                        $current_index = array_search($current_status, $step_order);

                        foreach ($seminar_steps as $status => $label) {
                            $step_index = array_search($status, $step_order);
                            $is_active = ($status === $current_status);
                            $is_completed = ($step_index < $current_index);
                            $step_class = $is_active ? 'active' : ($is_completed ? 'completed' : '');
                        ?>
                            <li class="timeline-item <?= $step_class ?>">
                                <span class="badge <?= $is_active ? 'bg-warning' : ($is_completed ? 'bg-success' : 'bg-secondary') ?>">
                                    <?= $label ?>
                                </span>
                            </li>
                        <?php } ?>
                    </ul>
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

        <!-- Right Column: Actions -->
        <div class="col-md-6">
            <!-- Action: Upload Laporan Draft -->
            <?php if (in_array($application->status, ['field_work_completed', 'report_rejected'])) : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Langkah 1: Unggah Laporan PKL</div>
                    <div class="card-body">
                        <?php if ($application->status === 'report_rejected') : ?>
                            <div class="alert alert-warning">
                                <strong>Laporan Ditolak!</strong> Silakan perbaiki dan unggah kembali draft laporan Anda.
                            </div>
                        <?php else : ?>
                            <p>Silakan unggah draft laporan PKL Anda untuk meminta persetujuan seminar dari dosen pembimbing.</p>
                        <?php endif; ?>

                        <?= form_open_multipart('pkl/seminar/upload_report/' . $application->id); ?>
                        <div class="mb-3">
                            <label for="report_file" class="form-label">File Laporan PKL (PDF, max 5MB) <span class="text-danger">*</span></label>
                            <input type="file" name="report_file" class="form-control" accept=".pdf" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Unggah & Ajukan Seminar</button>
                        <?= form_close(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status: Menunggu Persetujuan Dosen -->
            <?php if ($application->status === 'seminar_requested') : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Status: Menunggu Persetujuan</div>
                    <div class="card-body">
                        <p>Draft laporan Anda telah dikirim. Saat ini sedang menunggu persetujuan dari Dosen Pembimbing untuk pelaksanaan seminar.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status: Seminar Dijadwalkan -->
            <?php if ($application->status === 'seminar_scheduled') : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Status: Seminar Dijadwalkan</div>
                    <div class="card-body">
                        <p>Selamat! Seminar Anda telah dijadwalkan. Berikut detailnya:</p>
                        <ul class="list-unstyled">
                            <li><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($application->seminar_date)) ?></li>
                            <li><strong>Lokasi:</strong> <?= html_escape($application->seminar_location) ?></li>
                        </ul>
                        <p>Harap persiapkan diri Anda dengan baik.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status: Seminar Selesai -->
            <?php if ($application->status === 'seminar_completed') : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Status: Seminar Telah Dilaksanakan</div>
                    <div class="card-body">
                        <p>Anda telah melaksanakan seminar. Dosen pembimbing akan segera memberikan hasil penilaian dan catatan revisi (jika ada).</p>
                        <p>Silakan tunggu informasi selanjutnya untuk tahap revisi laporan.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action: Upload Revision -->
            <?php if ($application->status === 'revision') : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">Langkah Selanjutnya: Revisi Laporan</div>
                    <div class="card-body">
                        <p>Dosen pembimbing Anda telah memberikan catatan revisi. Silakan perbaiki laporan Anda dan unggah versi terbarunya.</p>
                        <?php if (!empty($revision_notes->remarks)) : ?>
                            <div class="alert alert-info">
                                <strong>Catatan dari Dosen:</strong>
                                <p><?= nl2br(html_escape($revision_notes->remarks)) ?></p>
                            </div>
                        <?php endif; ?>

                        <?= form_open_multipart('pkl/seminar/upload_revision/' . $application->id); ?>
                        <div class="mb-3">
                            <label class="form-label">Unggah Laporan Hasil Revisi (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="revision_file" class="form-control" accept=".pdf" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Laporan Revisi</button>
                        <?= form_close(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action: Upload Final Sheet -->
            <?php if ($application->status === 'revision_approved') : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Langkah Akhir: Unggah Lembar Pengesahan</div>
                    <div class="card-body">
                        <p>Selamat, laporan akhir Anda telah disetujui! Langkah terakhir adalah mengunggah Lembar Pengesahan yang telah ditandatangani lengkap.</p>
                        <?= form_open_multipart('pkl/seminar/upload_final_sheet/' . $application->id); ?>
                        <div class="mb-3">
                            <label class="form-label">Unggah Lembar Pengesahan (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="final_sheet_file" class="form-control" accept=".pdf" required>
                        </div>
                        <button type="submit" class="btn btn-success">Selesaikan PKL</button>
                        <?= form_close(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status: Finished -->
            <?php if ($application->status === 'finished') : ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">PKL Selesai</div>
                    <div class="card-body text-center">
                        <h4 class="card-title">Selamat!</h4>
                        <p class="card-text">Anda telah menyelesaikan seluruh rangkaian kegiatan PKL. Semua dokumen dan laporan telah tersimpan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Document History -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-secondary text-white">Riwayat Dokumen Seminar</div>
        <div class="card-body">
            <?php if (empty($seminar_docs)) : ?>
                <p>Belum ada dokumen seminar yang diunggah.</p>
            <?php else : ?>
                <ul class="list-group">
                    <?php foreach ($seminar_docs as $doc) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= base_url($doc->file_path) ?>" target="_blank">
                                <?= ucfirst(str_replace('_', ' ', $doc->doc_type)) ?>
                            </a>
                            <span class="badge bg-info rounded-pill"><?= date('d-m-Y H:i', strtotime($doc->uploaded_at)) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?= site_url('pkl/applications') ?>" class="btn btn-light">Kembali ke Dashboard PKL</a>
    </div>
</div>

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