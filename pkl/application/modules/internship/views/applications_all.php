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
                            <th>NIM</th>
                            <th>Program Studi</th>
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
                                <td><?= html_escape($application->student_nim ?? '-') ?></td>
                                <td><?= html_escape($application->study_program_name ?? '-') ?></td>
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
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $application->id ?>">Detail</button>

                                    <?php if ($application->status === 'approved_kadep') : ?>
                                        <a href="<?= site_url('internship/applications/upload_recommendation/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-primary">Unggah Surat Rekomendasi</a>
                                    <?php endif; ?>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal<?= $application->id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pengajuan PKL</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="detail-content-<?= $application->id ?>">
                                                        <!-- Detail content will be loaded here -->
                                                        <div class="text-center">
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<script>
    // Status label helper function
    function getStatusLabel(status) {
        var statusLabels = {
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

    document.addEventListener('DOMContentLoaded', function() {
        var detailModals = document.querySelectorAll('.modal[id^="detailModal"]');

        detailModals.forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var applicationId = button.getAttribute('data-bs-target').replace('#detailModal', '');
                fetchDetail(applicationId);
            });
        });

        function fetchDetail(applicationId) {
            var contentDiv = document.getElementById('detail-content-' + applicationId);
            contentDiv.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            fetch('<?php echo site_url('internship/applications/get_application_detail/'); ?>' + applicationId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        contentDiv.innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
                        return;
                    }
                    displayDetail(applicationId, data);
                })
                .catch(error => {
                    console.error('Error fetching detail:', error);
                    contentDiv.innerHTML = '<div class="alert alert-danger">Gagal memuat detail pengajuan.</div>';
                });
        }

        function displayDetail(applicationId, data) {
            var contentDiv = document.getElementById('detail-content-' + applicationId);

            if (!data.application) {
                contentDiv.innerHTML = '<div class="alert alert-danger">Data pengajuan tidak ditemukan.</div>';
                return;
            }

            var submissionDate = data.application.submission_date ? new Date(data.application.submission_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
            var periodStart = data.application.activity_period_start ? new Date(data.application.activity_period_start).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';
            var periodEnd = data.application.activity_period_end ? new Date(data.application.activity_period_end).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : '-';

            var documentsHtml = '';
            if (data.documents && data.documents.length > 0) {
                documentsHtml = '<ul class="list-group">';
                data.documents.forEach(function(doc) {
                    var docName = doc.doc_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    documentsHtml += `<li class="list-group-item"><a href="<?php echo base_url(); ?>${doc.file_path}" target="_blank">${docName}</a></li>`;
                });
                documentsHtml += '</ul>';
            } else {
                documentsHtml = '<p class="text-muted">Tidak ada dokumen yang diunggah.</p>';
            }

            var student = data.student || {};
            var application = data.application || {};

            contentDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Data Mahasiswa</h6><hr>
                        <dl class="row">
                            <dt class="col-sm-4">Nama</dt><dd class="col-sm-8">${student.name || '-'}</dd>
                            <dt class="col-sm-4">NIM</dt><dd class="col-sm-8">${student.id || '-'}</dd>
                            <dt class="col-sm-4">Email</dt><dd class="col-sm-8">${student.email || '-'}</dd>
                            <dt class="col-sm-4">Program Studi</dt><dd class="col-sm-8">${student.study_program || '-'}</dd>
                        </dl>
                        <h6>Data Pengajuan</h6><hr>
                        <dl class="row">
                            <dt class="col-sm-4">Dosen Pembimbing</dt><dd class="col-sm-8">${application.lecturer_name || '-'}</dd>
                            <dt class="col-sm-4">Instansi</dt><dd class="col-sm-8">${application.place_name || '-'}</dd>
                            <dt class="col-sm-4">Alamat Instansi</dt><dd class="col-sm-8">${application.place_address || '-'}</dd>
                            <dt class="col-sm-4">Tanggal Pengajuan</dt><dd class="col-sm-8">${submissionDate}</dd>
                            <dt class="col-sm-4">Status</dt><dd class="col-sm-8"><span class="badge ${application.status && application.status.includes('rejected') ? 'bg-danger' : 'bg-success'}">${getStatusLabel(application.status)}</span></dd>
                            <dt class="col-sm-4">Periode Kegiatan</dt><dd class="col-sm-8">${periodStart} s/d ${periodEnd}</dd>
                            <dt class="col-sm-4">Nomor Telepon</dt><dd class="col-sm-8">+62${application.phone_number || '-'}</dd>
                            <dt class="col-sm-4">Surat Ditujukan</dt><dd class="col-sm-8">${application.addressed_to || '-'}</dd>
                            <dt class="col-sm-4">Kegiatan Setara</dt><dd class="col-sm-8">${application.equivalent_activity || '-'}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6>Dokumen Pengajuan</h6><hr>
                        ${documentsHtml}
                    </div>
                </div>
            `;
        }
    });
</script>