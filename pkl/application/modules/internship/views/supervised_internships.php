<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_status_label')) {
    function get_status_label($status) {
        $labels = [
            'submitted' => 'Menunggu Persetujuan Dosen',
            'approved_dosen' => 'Disetujui Dosen',
            'approved_kps' => 'Disetujui KPS',
            'approved_kadep' => 'Disetujui Kadep',
            'recommendation_uploaded' => 'Menunggu Keputusan Instansi',
            'rejected' => 'Ditolak Departemen',
            'rejected_instansi' => 'Ditolak Instansi',
            'ongoing' => 'Pelaksanaan PKL',
            'field_work_completed' => 'Menunggu Pengajuan Seminar',
            'seminar_requested' => 'Menunggu Persetujuan Seminar',
            'seminar_approved' => 'Seminar Disetujui',
            'seminar_scheduled' => 'Seminar Dijadwalkan',
            'seminar_completed' => 'Seminar Selesai',
            'revision' => 'Revisi Laporan',
            'revision_submitted' => 'Menunggu Persetujuan Revisi',
            'finished' => 'Selesai',
        ];
        return $labels[$status] ?? ucfirst($status);
    }
}

if (!function_exists('get_status_badge')) {
    function get_status_badge($status) {
        if (strpos($status, 'rejected') !== false) return 'bg-danger';
        if (strpos($status, 'approved') !== false || $status === 'finished') return 'bg-success';
        if ($status === 'submitted' || $status === 'seminar_requested' || $status === 'revision_submitted') return 'bg-warning';
        return 'bg-info';
    }
}
?>

<!--begin::App Content Header-->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0"><?= $title; ?></h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (empty($applications)) : ?>
                    <div class="text-center p-5">
                        <i class="bi bi-person-check-fill text-muted fs-1"></i>
                        <h5 class="mt-3">Belum Ada Mahasiswa Bimbingan</h5>
                        <p class="text-muted">Saat ini tidak ada data mahasiswa bimbingan PKL yang ditugaskan kepada Anda.</p>
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Instansi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status Progress</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app) : ?>
                                    <tr>
                                        <td>
                                            <strong><?= html_escape($app->student_name ?? '-') ?></strong><br>
                                            <small class="text-muted"><?= html_escape($app->student_nim ?? '-') ?></small>
                                        </td>
                                        <td><?= html_escape($app->place_name ?? '-') ?></td>
                                        <td><?= $app->submission_date ? date('d M Y', strtotime($app->submission_date)) : '-' ?></td>
                                        <td>
                                            <span class="badge <?= get_status_badge($app->status) ?>">
                                                <?= get_status_label($app->status) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('internship/seminar/manage/' . ($app->id ?? '')) ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-search me-1"></i> Lihat Progress
                                            </a>
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
</div>