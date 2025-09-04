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
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add Logbook Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-journal-plus me-2"></i>Tambah Logbook Harian</h5>
            </div>
            <div class="card-body">
                <?= form_open('internship/applications/add_log/' . $application->id); ?>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" required min="<?= $application->activity_period_start ?>" max="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label for="activity_title" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="activity_title" id="activity_title" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="activity_description" class="form-label">Penjelasan Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="activity_description" id="activity_description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Relevansi Keilmuan <span class="text-danger">*</span></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_relevant" id="relevant_yes" value="1" required>
                            <label class="form-check-label" for="relevant_yes">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_relevant" id="relevant_no" value="0" required>
                            <label class="form-check-label" for="relevant_no">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('internship/applications') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan Logbook</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>

        <!-- Weekly Logbook List -->
        <h4 class="mt-5">Logbook Mingguan</h4>
        <hr>
        <?php if (empty($weeks)) : ?>
            <div class="alert alert-info">Belum ada data minggu PKL yang bisa ditampilkan.</div>
        <?php else : ?>
            <?php foreach ($weeks as $week_number => $week) : ?>
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><strong>Minggu ke-<?= $week_number ?></strong> (<?= date('d M Y', strtotime($week['start_date'])) ?> - <?= date('d M Y', strtotime($week['end_date'])) ?>)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Aktivitas Harian</h6>
                                <?php if (empty($week['daily_logs'])) : ?>
                                    <p class="text-muted">Belum ada aktivitas yang dicatat minggu ini.</p>
                                <?php else : ?>
                                    <div class="table-responsive" style="max-height: 250px;">
                                        <table class="table table-sm table-bordered">
                                            <tbody>
                                                <?php foreach ($week['daily_logs'] as $log) : ?>
                                                    <tr>
                                                        <td class="text-muted" style="width:100px;"><?= date('d/m/Y', strtotime($log->date)) ?></td>
                                                        <td><?= html_escape($log->activity_title) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 border-start">
                                <h6>Logbook Mingguan (TTD)</h6>
                                <a href="<?= site_url('internship/applications/print_logbook_weekly/' . $application->id . '/' . $week_number) ?>" target="_blank" class="btn btn-sm btn-secondary w-100 mb-3"><i class="bi bi-printer me-2"></i>Cetak Form Logbook</a>
                                <?php if ($week['weekly_upload']) : ?>
                                    <?php
                                    $status = $week['weekly_upload']->status;
                                    $badge_class = 'bg-secondary';
                                    $text_class = 'text-muted';
                                    if ($status == 'approved') {
                                        $badge_class = 'bg-success';
                                        $text_class = 'text-success';
                                    } elseif ($status == 'rejected') {
                                        $badge_class = 'bg-danger';
                                        $text_class = 'text-danger';
                                    } elseif ($status == 'uploaded') {
                                        $badge_class = 'bg-info';
                                        $text_class = 'text-info';
                                    }
                                    ?>
                                    <div class="text-center">
                                        <p class="mb-1">Status: <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span></p>
                                        <small class="text-muted">Diunggah pada: <?= date('d M Y H:i', strtotime($week['weekly_upload']->uploaded_at)) ?></small>
                                        <a href="<?= base_url($week['weekly_upload']->file_path) ?>" target="_blank" class="btn btn-sm btn-success w-100 mt-2">Lihat File</a>
                                    </div>
                                    <?php if ($status == 'rejected') : ?>
                                        <div class="alert alert-danger mt-2 p-2">
                                            <small><strong>Catatan:</strong> <?= html_escape($week['weekly_upload']->remarks) ?></small>
                                        </div>
                                        <hr>
                                        <p class="text-center small">Silakan unggah revisi:</p>
                                        <?= form_open_multipart('internship/applications/upload_logbook_weekly/' . $application->id) ?>
                                        <input type="hidden" name="week_number" value="<?= $week_number ?>">
                                        <input type="hidden" name="start_date" value="<?= $week['start_date'] ?>">
                                        <input type="hidden" name="end_date" value="<?= $week['end_date'] ?>">
                                        <input type="file" name="logbook_file" class="form-control form-control-sm" required>
                                        <button type="submit" class="btn btn-sm btn-warning w-100 mt-2">Unggah Ulang</button>
                                        <?= form_close() ?>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <div class="text-center text-muted">
                                        <p class="mb-2 small">Belum ada bukti logbook yang diunggah untuk minggu ini.</p>
                                        <?= form_open_multipart('internship/applications/upload_logbook_weekly/' . $application->id) ?>
                                        <input type="hidden" name="week_number" value="<?= $week_number ?>">
                                        <input type="hidden" name="start_date" value="<?= $week['start_date'] ?>">
                                        <input type="hidden" name="end_date" value="<?= $week['end_date'] ?>">
                                        <input type="file" name="logbook_file" class="form-control form-control-sm" required accept=".pdf">
                                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-2"><i class="bi bi-upload me-2"></i>Unggah</button>
                                        <?= form_close() ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->