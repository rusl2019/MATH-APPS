<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_status_label')) {
    function get_status_label($status) {
        $status_labels = array(
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
        );
        return isset($status_labels[$status]) ? $status_labels[$status] : $status;
    }
}
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
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if (empty($applications)) : ?>
            <div class="alert alert-info">
                Tidak ada pengajuan PKL yang memerlukan persetujuan.
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
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application) : ?>
                            <tr>
                                <td><?php echo html_escape(isset($application->student_name) ? $application->student_name : '-'); ?></td>
                                <td><?php echo html_escape(isset($application->title) ? $application->title : '-'); ?></td>
                                <td><?php echo html_escape(isset($application->lecturer_name) ? $application->lecturer_name : '-'); ?></td>
                                <td><?php echo html_escape(isset($application->place_name) ? $application->place_name : '-'); ?></td>
                                <td><?php echo isset($application->submission_date) ? date('d-m-Y', strtotime($application->submission_date)) : '-'; ?></td>
                                <td>
                                    <?php
                                    $status_labels = array(
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
                                    );
                                    $status = isset($application->status) ? $application->status : '';
                                    $badge_class = ($status === 'rejected' || $status === 'rejected_instansi') ? 'bg-danger' : 'bg-success';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo isset($status_labels[$status]) ? $status_labels[$status] : $status; ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Detail Button -->
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo isset($application->id) ? $application->id : ''; ?>">Detail</button>

                                    <?php if ($application->status === 'submitted' || strpos($application->status, 'approved') !== false) : ?>
                                        <a href="<?php echo site_url('pkl/applications/approve/' . (isset($application->id) ? $application->id : '')); ?>" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">Setujui Pengajuan</a>

                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo isset($application->id) ? $application->id : ''; ?>">Tolak Pengajuan</button>

                                    <?php elseif ($application->status === 'seminar_requested') : ?>
                                        <a href="<?php echo site_url('pkl/seminar/approve/' . (isset($application->id) ? $application->id : '')); ?>" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan ini untuk seminar?')">Setujui Seminar</a>

                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectReportModal<?php echo isset($application->id) ? $application->id : ''; ?>">Tolak Laporan</button>

                                    <?php elseif (in_array($application->status, array('seminar_approved', 'seminar_scheduled', 'seminar_completed', 'report_rejected', 'revision_submitted'))) : ?>
                                        <a href="<?php echo site_url('pkl/seminar/manage/' . (isset($application->id) ? $application->id : '')); ?>" class="btn btn-sm btn-primary">Kelola Seminar</a>
                                    <?php endif; ?>


                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal<?php echo isset($application->id) ? $application->id : ''; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pengajuan PKL</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="detail-content-<?php echo isset($application->id) ? $application->id : ''; ?>">
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

                                    <!-- Reject Modal for Application -->
                                    <div class="modal fade" id="rejectModal<?php echo isset($application->id) ? $application->id : ''; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <?php echo form_open(site_url('pkl/applications/reject/' . (isset($application->id) ? $application->id : ''))); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Alasan Penolakan Pengajuan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Berikan alasan penolakan:</label>
                                                        <textarea name="remarks" class="form-control" rows="4" placeholder="Tuliskan alasan penolakan..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal for Report -->
                                    <div class="modal fade" id="rejectReportModal<?php echo isset($application->id) ? $application->id : ''; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <?php echo form_open(site_url('pkl/seminar/reject_report/' . (isset($application->id) ? $application->id : ''))); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Alasan Penolakan Laporan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Berikan alasan atau catatan revisi:</label>
                                                        <textarea name="remarks" class="form-control" rows="4" placeholder="Tuliskan alasan penolakan atau catatan untuk revisi..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak Laporan</button>
                                                </div>
                                                <?php echo form_close(); ?>
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
    // Add event listeners to all detail buttons
    var detailButtons = document.querySelectorAll('button[data-bs-toggle="modal"][data-bs-target^="#detailModal"]');
    
    detailButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('tr');
            var applicationId = '';
            
            // Try to get the application ID from the approve link
            var approveLink = row.querySelector('a[href*="approve"]');
            if (approveLink) {
                var match = approveLink.href.match(/\/(\d+)$/);
                if (match) {
                    applicationId = match[1];
                }
            }
            
            // If not found, try to get it from the modal target
            if (!applicationId) {
                var modalTarget = this.dataset.bsTarget;
                var match = modalTarget.match(/(\d+)/);
                if (match) {
                    applicationId = match[1];
                }
            }
            
            if (applicationId) {
                fetchDetail(applicationId);
            }
        });
    });
    
    function fetchDetail(applicationId) {
        fetch('<?php echo site_url('pkl/applications/get_application_detail/'); ?>' + applicationId)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                displayDetail(applicationId, data);
            })
            .catch(function(error) {
                console.error('Error fetching detail:', error);
                document.getElementById('detail-content-' + applicationId).innerHTML = 
                    '<div class="alert alert-danger">Gagal memuat detail pengajuan.</div>';
            });
    }
    
    function displayDetail(applicationId, data) {
        var contentDiv = document.getElementById('detail-content-' + applicationId);
        
        if (!data.application) {
            contentDiv.innerHTML = '<div class="alert alert-danger">Data pengajuan tidak ditemukan.</div>';
            return;
        }
        
        // Format dates
        var submissionDate = '-';
        if (data.application.submission_date) {
            var submissionDateObj = new Date(data.application.submission_date);
            submissionDate = submissionDateObj.toLocaleDateString('id-ID');
        }
        
        var periodStart = '-';
        if (data.application.activity_period_start) {
            var periodStartObj = new Date(data.application.activity_period_start);
            periodStart = periodStartObj.toLocaleDateString('id-ID');
        }
            
        var periodEnd = '-';
        if (data.application.activity_period_end) {
            var periodEndObj = new Date(data.application.activity_period_end);
            periodEnd = periodEndObj.toLocaleDateString('id-ID');
        }
        
        // Build document list
        var documentsHtml = '';
        if (data.documents && data.documents.length > 0) {
            documentsHtml = '<ul class="list-group">';
            data.documents.forEach(function(doc) {
                var docName = 'Dokumen';
                if (doc.doc_type) {
                    docName = doc.doc_type.replace(/_/g, ' ')
                        .replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                }
                documentsHtml += '<li class="list-group-item">' +
                    '<a href="<?php echo base_url(); ?>' + doc.file_path + '" target="_blank">' + docName + '</a>' +
                    (doc.status ? '<span class="badge bg-secondary ms-2">' + doc.status + '</span>' : '') +
                    '</li>';
            });
            documentsHtml += '</ul>';
        } else {
            documentsHtml = '<p class="text-muted">Tidak ada dokumen yang diunggah.</p>';
        }
        
        contentDiv.innerHTML = 
            '<div class="row">' +
                '<div class="col-md-6">' +
                    '<h6>Data Mahasiswa</h6>' +
                    '<hr>' +
                    '<dl class="row">' +
                        '<dt class="col-sm-4">Nama</dt>' +
                        '<dd class="col-sm-8">' + (data.student && data.student.name ? data.student.name : '-') + '</dd>' +
                        '<dt class="col-sm-4">NIM</dt>' +
                        '<dd class="col-sm-8">' + (data.student && data.student.id ? data.student.id : '-') + '</dd>' +
                        '<dt class="col-sm-4">Email</dt>' +
                        '<dd class="col-sm-8">' + (data.student && data.student.email ? data.student.email : '-') + '</dd>' +
                        '<dt class="col-sm-4">Program Studi</dt>' +
                        '<dd class="col-sm-8">' + (data.student && data.student.study_program ? data.student.study_program : '-') + '</dd>' +
                    '</dl>' +
                    '<h6>Data Pengajuan</h6>' +
                    '<hr>' +
                    '<dl class="row">' +
                        '<dt class="col-sm-4">Judul PKL</dt>' +
                        '<dd class="col-sm-8">' + (data.application.title || '-') + '</dd>' +
                        '<dt class="col-sm-4">Jenis Kegiatan</dt>' +
                        '<dd class="col-sm-8">' + (data.application.type || '-') + '</dd>' +
                        '<dt class="col-sm-4">Dosen Pembimbing</dt>' +
                        '<dd class="col-sm-8">' + (data.application.lecturer_name || '-') + '</dd>' +
                        '<dt class="col-sm-4">Instansi</dt>' +
                        '<dd class="col-sm-8">' + (data.application.place_name || '-') + '</dd>' +
                        '<dt class="col-sm-4">Alamat Instansi</dt>' +
                        '<dd class="col-sm-8">' + (data.application.place_address || '-') + '</dd>' +
                        '<dt class="col-sm-4">Tanggal Pengajuan</dt>' +
                        '<dd class="col-sm-8">' + submissionDate + '</dd>' +
                        '<dt class="col-sm-4">Status</dt>' +
                        '<dd class="col-sm-8"><span class="badge ' + (data.application.status && data.application.status.includes('rejected') ? 'bg-danger' : 'bg-success') + '">' + getStatusLabel(data.application.status) + '</span></dd>' +
                        '<dt class="col-sm-4">Periode Kegiatan</dt>' +
                        '<dd class="col-sm-8">' + periodStart + ' s/d ' + periodEnd + '</dd>' +
                        '<dt class="col-sm-4">Nomor Telepon</dt>' +
                        '<dd class="col-sm-8">+62' + (data.application.phone_number || '-') + '</dd>' +
                        '<dt class="col-sm-4">Surat Ditujukan Kepada</dt>' +
                        '<dd class="col-sm-8">' + (data.application.addressed_to || '-') + '</dd>' +
                        '<dt class="col-sm-4">Kegiatan Setara</dt>' +
                        '<dd class="col-sm-8">' + (data.application.equivalent_activity || '-') + '</dd>' +
                    '</dl>' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<h6>Dokumen Pengajuan</h6>' +
                    '<hr>' +
                    documentsHtml +
                '</div>' +
            '</div>';
    }
});
</script>