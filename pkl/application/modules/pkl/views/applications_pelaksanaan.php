<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Form Tambah Logbook -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Tambah Logbook Harian</div>
        <div class="card-body">
            <?= form_open('pkl/applications/add_log/' . $application->id); ?>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="log_date" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" name="log_date" class="form-control" required>
                </div>
                <div class="col-md-9 mb-3">
                    <label for="activity" class="form-label">Uraian Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="activity" class="form-control" rows="2" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Logbook</button>
            <?= form_close(); ?>
        </div>
    </div>

    <!-- Daftar Logbook -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">Riwayat Logbook</div>
        <div class="card-body">
            <?php if (empty($logs)) : ?>
                <p class="text-center">Belum ada logbook yang diisi.</p>
            <?php else : ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 20%;">Tanggal</th>
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
                                    <!-- Logika approval logbook bisa ditambahkan di sini -->
                                    <span class="badge bg-secondary">Pending</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>