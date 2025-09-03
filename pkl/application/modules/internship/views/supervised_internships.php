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

        <?php if (empty($applications)) : ?>
            <div class="alert alert-info">
                Tidak ada data mahasiswa bimbingan yang ditemukan.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Dosen Pembimbing</th>
                            <th>Instansi</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $index => $app) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= html_escape($app->student_name ?? '-') ?></td>
                                <td><?= html_escape($app->lecturer_name ?? '-') ?></td>
                                <td><?= html_escape($app->place_name ?? '-') ?></td>
                                <td>
                                    <?= $app->submission_date ? date('d M Y', strtotime($app->submission_date)) : '-' ?>
                                </td>
                                <td>
                                    <span class="badge <?= get_status_badge($app->status) ?>">
                                        <?= get_status_label($app->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= site_url('internship/seminar/manage/' . ($app->id ?? '')) ?>" class="btn btn-sm btn-info">Lihat Progress</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->