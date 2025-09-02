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
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <!-- Add Logbook Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">Tambah Logbook Harian</div>
            <div class="card-body">
                <?= form_open('pkl/applications/add_log/' . $application->id); ?>
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
                <button type="submit" class="btn btn-primary">Simpan Logbook</button>
                <a href="<?= site_url('pkl/applications') ?>" class="btn btn-secondary">Kembali</a>
                <?= form_close(); ?>
            </div>
        </div>

        <!-- Weekly Logbook Accordion -->
        <div class="accordion" id="weeklyLogbookAccordion">
            <?php if (empty($weeks)) : ?>
                <div class="alert alert-info">Belum ada data minggu PKL yang bisa ditampilkan.</div>
            <?php else : ?>
                <?php foreach ($weeks as $week_number => $week) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $week_number ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $week_number ?>" aria-expanded="false" aria-controls="collapse<?= $week_number ?>">
                                <strong>Minggu ke-<?= $week_number ?></strong> (<?= date('d M Y', strtotime($week['start_date'])) ?> - <?= date('d M Y', strtotime($week['end_date'])) ?>)
                            </button>
                        </h2>
                        <div id="collapse<?= $week_number ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $week_number ?>" data-bs-parent="#weeklyLogbookAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5>Aktivitas Harian</h5>
                                        <?php if (empty($week['daily_logs'])) : ?>
                                            <p class="text-muted">Belum ada aktivitas yang dicatat minggu ini.</p>
                                        <?php else : ?>
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jam</th>
                                                        <th>Judul Kegiatan</th>
                                                        <th>Penjelasan</th>
                                                        <th>Relevan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($week['daily_logs'] as $log) : ?>
                                                        <tr>
                                                            <td style="width:100px;"><?= date('d/m/Y', strtotime($log->date)) ?></td>
                                                            <td style="width:120px;"><?= html_escape($log->start_time) ?> - <?= html_escape($log->end_time) ?></td>
                                                            <td><?= html_escape($log->activity_title) ?></td>
                                                            <td><?= nl2br(html_escape($log->activity_description)) ?></td>
                                                            <td style="width:80px;"><?= $log->is_relevant ? 'Ya' : 'Tidak' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Logbook Mingguan (TTD)</h5>
                                        <a href="<?= site_url('pkl/applications/print_logbook_weekly/' . $application->id . '/' . $week_number) ?>" target="_blank" class="btn btn-sm btn-info mb-3 w-100"><i class="fas fa-print"></i> Cetak Logbook Minggu Ini</a>

                                        <div class="card">
                                            <div class="card-body">
                                                <?php if ($week['weekly_upload']) : ?>
                                                    <p class="mb-2"><strong>Status:</strong> 
                                                        <?php 
                                                            $status = $week['weekly_upload']->status;
                                                            $badge_class = 'bg-secondary';
                                                            if ($status == 'approved') $badge_class = 'bg-success';
                                                            if ($status == 'rejected') $badge_class = 'bg-danger';
                                                        ?>
                                                        <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                                                    </p>
                                                    <p class="mb-2"><strong>Diunggah pada:</strong> <?= date('d M Y H:i', strtotime($week['weekly_upload']->uploaded_at)) ?></p>
                                                    <a href="<?= base_url($week['weekly_upload']->file_path) ?>" target="_blank" class="btn btn-sm btn-success w-100">Lihat File</a>
                                                    <?php if($status == 'rejected'): ?>
                                                        <p class="mt-2 text-danger"><small><strong>Catatan:</strong> <?= html_escape($week['weekly_upload']->remarks) ?></small></p>
                                                        <hr>
                                                        <p class="text-center">Silakan unggah revisi:</p>
                                                        <!-- Revision Upload Form -->
                                                        <?= form_open_multipart('pkl/applications/upload_logbook_weekly/' . $application->id) ?>
                                                            <input type="hidden" name="week_number" value="<?= $week_number ?>">
                                                            <input type="hidden" name="start_date" value="<?= $week['start_date'] ?>">
                                                            <input type="hidden" name="end_date" value="<?= $week['end_date'] ?>">
                                                            <div class="mb-2">
                                                                <input type="file" name="logbook_file" class="form-control form-control-sm" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-sm btn-warning w-100">Unggah Ulang</button>
                                                        <?= form_close() ?>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <p class="text-muted text-center">Belum ada bukti logbook yang diunggah untuk minggu ini.</p>
                                                    <hr>
                                                    <!-- Upload Form -->
                                                    <?= form_open_multipart('pkl/applications/upload_logbook_weekly/' . $application->id) ?>
                                                        <input type="hidden" name="week_number" value="<?= $week_number ?>">
                                                        <input type="hidden" name="start_date" value="<?= $week['start_date'] ?>">
                                                        <input type="hidden" name="end_date" value="<?= $week['end_date'] ?>">
                                                        <div class="mb-2">
                                                            <label class="form-label"><small>Unggah Bukti TTD (PDF):</small></label>
                                                            <input type="file" name="logbook_file" class="form-control form-control-sm" required accept=".pdf">
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary w-100">Unggah</button>
                                                    <?= form_close() ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
