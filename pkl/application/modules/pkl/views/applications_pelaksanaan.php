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
                        <label for="log_date" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                        <input type="date" name="log_date" class="form-control" required max="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-9 mb-3">
                        <label for="activity" class="form-label">Uraian Kegiatan <span class="text-danger">*</span></label>
                        <textarea name="activity" class="form-control" rows="3" placeholder="Deskripsikan kegiatan yang Anda lakukan..." required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Logbook</button>
                <a href="<?= site_url('pkl/applications') ?>" class="btn btn-secondary">Kembali</a>
                <?= form_close(); ?>
            </div>
        </div>

        <!-- Logbook History -->
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">Riwayat Logbook</div>
            <div class="card-body">
                <?php if (empty($logs)) : ?>
                    <p class="text-center text-muted">Belum ada logbook yang diisi.</p>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 15%;">Tanggal</th>
                                    <th>Uraian Kegiatan</th>
                                    <th style="width: 15%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log) : ?>
                                    <tr>
                                        <td><?= date('d-m-Y', strtotime($log->log_date)) ?></td>
                                        <td><?= nl2br(html_escape($log->activity)) ?></td>
                                        <td>
                                            <?php if ($log->is_approved ?? false) : ?>
                                                <span class="badge bg-success">Disetujui</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->