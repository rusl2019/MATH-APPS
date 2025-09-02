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
                Tidak ada pengajuan PKL yang tersedia.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Dosen</th>
                            <th>Instansi</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application) : ?>
                            <tr>
                                <td><?= html_escape($application->student_name ?? '-') ?></td>
                                <td><?= html_escape($application->title ?? '-') ?></td>
                                <td><?= html_escape($application->lecturer_name ?? '-') ?></td>
                                <td><?= html_escape($application->place_name ?? '-') ?></td>
                                <td>
                                    <?= $application->submission_date ? date('d-m-Y', strtotime($application->submission_date)) : '-' ?>
                                </td>
                                <td>
                                    <?php
                                    $status_labels = [
                                        'draft' => 'Draft',
                                        'submitted' => 'Dikirim',
                                        'approved_dosen' => 'Disetujui Dosen',
                                        'approved_kps' => 'Disetujui KPS',
                                        'approved_kadep' => 'Disetujui Kadep',
                                        'recommendation_uploaded' => 'Surat Rekomendasi Diunggah',
                                        'rejected' => 'Ditolak',
                                        'rejected_instansi' => 'Ditolak Instansi',
                                        'ongoing' => 'Sedang Berlangsung',
                                        'field_work_completed' => 'Lapangan Selesai',
                                        'seminar_requested' => 'Pengajuan Seminar',
                                        'seminar_approved' => 'Seminar Disetujui',
                                        'seminar_scheduled' => 'Seminar Dijadwalkan',
                                        'seminar_completed' => 'Seminar Selesai',
                                        'revision' => 'Revisi Laporan',
                                        'revision_submitted' => 'Revisi Dikirim',
                                        'revision_approved' => 'Revisi Disetujui',
                                        'finished' => 'Selesai'
                                    ];
                                    $status = $application->status ?? '';
                                    $badge_class = ($status === 'rejected' || $status === 'rejected_instansi') ? 'bg-danger' : 'bg-success';
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $status_labels[$status] ?? $status ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($application->status === 'approved_kadep') : ?>
                                        <a href="<?= site_url('pkl/applications/upload_recommendation/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-primary">Unggah Surat Rekomendasi</a>
                                    <?php endif; ?>
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