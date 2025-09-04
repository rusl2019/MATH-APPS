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

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (empty($applications)) : ?>
                    <div class="text-center p-5">
                        <i class="bi bi-folder2-open text-muted fs-1"></i>
                        <h5 class="mt-3">Belum Ada Pengajuan</h5>
                        <p class="text-muted">Tidak ada data pengajuan PKL yang tersedia di dalam sistem.</p>
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Instansi & Dosen</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $application) : ?>
                                    <tr>
                                        <td>
                                            <strong><?= html_escape($application->student_name ?? '-') ?></strong><br>
                                            <small class="text-muted"><?= html_escape($application->student_nim ?? '-') ?> - <?= html_escape($application->study_program_name ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <strong><?= html_escape($application->place_name ?? '-') ?></strong><br>
                                            <small class="text-muted">Dosen: <?= html_escape($application->lecturer_name ?? '-') ?></small>
                                        </td>
                                        <td><?= $application->submission_date ? date('d M Y', strtotime($application->submission_date)) : '-' ?></td>
                                        <td>
                                            <?php
                                            $status = $application->status ?? '';
                                            $badge_class = 'bg-secondary';
                                            if (strpos($status, 'rejected') !== false) $badge_class = 'bg-danger';
                                            if (strpos($status, 'approved') !== false || $status === 'finished') $badge_class = 'bg-success';
                                            if (strpos($status, 'seminar') !== false || $status === 'ongoing' || $status === 'revision') $badge_class = 'bg-info';
                                            if ($status === 'submitted') $badge_class = 'bg-warning';
                                            ?>
                                            <span class="badge <?= $badge_class ?>"><?= get_status_label($status) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" onclick="fetchDetail(<?= $application->id ?>)">Detail</button>
                                            <?php if ($application->status === 'approved_kadep') : ?>
                                                <a href="<?= site_url('internship/applications/upload_recommendation/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-primary">Unggah Surat</a>
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

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Pengajuan PKL</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="detailModalBody">
                        <!-- Content loaded by JS -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->

<script>
    let detailModal;

    document.addEventListener('DOMContentLoaded', function() {
        detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    });

    function get_status_label(status) {
        const statusLabels = {
            'draft': 'Draft',
            'submitted': 'Dikirim',
            'approved_dosen': 'Disetujui Dosen',
            'approved_kps': 'Disetujui KPS',
            'approved_kadep': 'Disetujui Kadep',
            'recommendation_uploaded': 'Surat Rekomendasi Diunggah',
            'rejected': 'Ditolak',
            'rejected_instansi': 'Ditolak Instansi',
            'ongoing': 'Sedang Berlangsung',
            'field_work_completed': 'Lapangan Selesai',
            'seminar_requested': 'Pengajuan Seminar',
            'seminar_approved': 'Seminar Disetujui',
            'seminar_scheduled': 'Seminar Dijadwalkan',
            'seminar_completed': 'Seminar Selesai',
            'revision': 'Revisi Laporan',
            'revision_submitted': 'Revisi Dikirim',
            'revision_approved': 'Revisi Disetujui',
            'finished': 'Selesai'
        };
        return statusLabels[status] || status;
    }

    function fetchDetail(applicationId) {
        const modalBody = document.getElementById('detailModalBody');
        modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        detailModal.show();

        fetch(`<?= site_url('internship/applications/get_application_detail/') ?>${applicationId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    modalBody.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }
                displayDetail(data);
            })
            .catch(error => {
                console.error('Error fetching detail:', error);
                modalBody.innerHTML = '<div class="alert alert-danger">Gagal memuat detail pengajuan.</div>';
            });
    }

    function displayDetail(data) {
        const modalBody = document.getElementById('detailModalBody');
        const app = data.application;
        const student = data.student;

        const submissionDate = app.submission_date ? new Date(app.submission_date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }) : '-';
        const period = `${new Date(app.activity_period_start).toLocaleDateString('id-ID')} - ${new Date(app.activity_period_end).toLocaleDateString('id-ID')}`;

        let documentsHtml = '<p class="text-muted">Tidak ada dokumen.</p>';
        if (data.documents && data.documents.length > 0) {
            documentsHtml = '<ul class="list-group list-group-flush">';
            data.documents.forEach(doc => {
                const docName = doc.doc_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                documentsHtml += `<li class="list-group-item"><a href="<?= base_url() ?>${doc.file_path}" target="_blank"><i class="bi bi-file-earmark-text me-2"></i>${docName}</a></li>`;
            });
            documentsHtml += '</ul>';
        }

        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Data Mahasiswa</h6><hr class="mt-1">
                    <dl class="row"><dt class="col-sm-4">Nama</dt><dd class="col-sm-8">${student.name || '-'}</dd><dt class="col-sm-4">NIM</dt><dd class="col-sm-8">${student.id || '-'}</dd><dt class="col-sm-4">Program Studi</dt><dd class="col-sm-8">${student.study_program || '-'}</dd></dl>
                </div>
                <div class="col-md-6">
                    <h6>Data Pengajuan</h6><hr class="mt-1">
                    <dl class="row"><dt class="col-sm-4">Dosen</dt><dd class="col-sm-8">${app.lecturer_name || '-'}</dd><dt class="col-sm-4">Instansi</dt><dd class="col-sm-8">${app.place_name || '-'}</dd><dt class="col-sm-4">Periode</dt><dd class="col-sm-8">${period}</dd><dt class="col-sm-4">Status</dt><dd class="col-sm-8"><span class="badge bg-primary">${get_status_label(app.status)}</span></dd></dl>
                </div>
            </div>
            <h6 class="mt-3">Dokumen Pengajuan</h6><hr class="mt-1">
            ${documentsHtml}
        `;
    }
</script>