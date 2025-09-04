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
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if (empty($applications)) : ?>
            <div class="card card-body text-center shadow-sm">
                <h5 class="card-title text-danger">Anda Belum Terdaftar PKL</h5>
                <p class="card-text mb-3">Silakan ajukan pendaftaran PKL untuk memulai proses dan melacak progress Anda di halaman ini.</p>
                <div class="d-flex justify-content-center">
                    <a href="<?= site_url('internship/applications/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Ajukan PKL Sekarang
                    </a>
                </div>
            </div>
        <?php else : ?>
            <?php $application = $applications[0] ?? null; ?>

            <!-- Action Card -->
            <div class="card shadow-sm mb-4">
                <?php
                $status = $application->status ?? '';
                $action_card_data = [
                    'recommendation_uploaded' => ['header_class' => 'bg-warning text-dark', 'title' => 'Langkah Selanjutnya: Lapor Keputusan Instansi', 'text' => 'Silakan laporkan hasil dari pengajuan surat rekomendasi ke instansi.', 'button_text' => 'Laporkan Keputusan Instansi', 'button_url' => site_url('internship/applications/report_decision/' . ($application->id ?? '')), 'button_class' => 'btn-primary'],
                    'rejected' => ['header_class' => 'bg-danger text-white', 'title' => 'Tindak Lanjut Diperlukan: Pengajuan Ditolak', 'text' => 'Pengajuan Anda ditolak. Silakan perbaiki data Anda dan ajukan kembali.', 'button_text' => 'Ajukan Ulang', 'button_url' => site_url('internship/applications/create/' . ($application->id ?? '')), 'button_class' => 'btn-warning'],
                    'rejected_instansi' => ['header_class' => 'bg-danger text-white', 'title' => 'Tindak Lanjut Diperlukan: Ditolak Instansi', 'text' => 'Pengajuan Anda ditolak oleh instansi. Anda dapat mencari tempat baru dan mengajukan ulang.', 'button_text' => 'Ajukan Ulang', 'button_url' => site_url('internship/applications/create/' . ($application->id ?? '')), 'button_class' => 'btn-warning'],
                    'ongoing' => ['header_class' => 'bg-primary text-white', 'title' => 'Pelaksanaan PKL Sedang Berlangsung', 'text' => 'Status PKL Anda sedang berlangsung. Silakan isi logbook harian secara rutin. Jika sudah menyelesaikan PKL di lapangan, silakan klik tombol "Selesaikan PKL".', 'button_text' => ['Buka Logbook', 'Selesaikan PKL'], 'button_url' => [site_url('internship/applications/pelaksanaan/' . ($application->id ?? '')), site_url('internship/applications/finish_internship/' . ($application->id ?? ''))], 'button_class' => ['btn-info', 'btn-success']],
                    'field_work_completed' => ['header_class' => 'bg-primary text-white', 'title' => 'Langkah Selanjutnya: Proses Seminar', 'text' => 'Anda telah menyelesaikan PKL di lapangan. Tahap selanjutnya adalah penulisan laporan dan seminar.', 'button_text' => 'Mulai Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-primary'],
                    'seminar_requested' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'seminar_approved' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'seminar_scheduled' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'seminar_completed' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'report_rejected' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'revision' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'revision_submitted' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'revision_approved' => ['header_class' => 'bg-info text-white', 'title' => 'Proses Seminar Berlangsung', 'text' => 'Anda sedang dalam tahap seminar dan revisi laporan. Klik tombol di bawah untuk melihat detail.', 'button_text' => 'Lanjutkan Proses Seminar', 'button_url' => site_url('internship/seminar/index/' . ($application->id ?? '')), 'button_class' => 'btn-info'],
                    'finished' => ['header_class' => 'bg-success text-white', 'title' => 'PKL Selesai', 'text' => 'Selamat! Anda telah menyelesaikan seluruh rangkaian kegiatan PKL.', 'button_text' => null],
                ];
                $action_data = $action_card_data[$status] ?? null;
                ?>
                <?php if ($action_data) : ?>
                    <div class="card-header <?= $action_data['header_class'] ?>">
                        <h5 class="card-title mb-0"><?= $action_data['title'] ?></h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text"><?= $action_data['text'] ?></p>
                        <?php if ($action_data['button_text']) : ?>
                            <?php if (is_array($action_data['button_text'])) : ?>
                                <?php for ($i = 0; $i < count($action_data['button_text']); $i++) : ?>
                                    <a href="<?= $action_data['button_url'][$i] ?>" class="btn <?= $action_data['button_class'][$i] ?> me-2"><?= $action_data['button_text'][$i] ?></a>
                                <?php endfor; ?>
                            <?php else : ?>
                                <a href="<?= $action_data['button_url'] ?>" class="btn <?= $action_data['button_class'] ?>"><?= $action_data['button_text'] ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Information Tabs -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab" aria-controls="progress" aria-selected="true">Progress PKL</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail" type="button" role="tab" aria-controls="detail" aria-selected="false">Detail Pengajuan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Dokumen</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <!-- Progress Tab -->
                        <div class="tab-pane fade show active" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                            <h5 class="mb-3">Timeline Progress PKL</h5>
                            <ul class="timeline">
                                <?php
                                $stages = [
                                    1 => ['name' => 'Tahap 1: Pengajuan', 'statuses' => ['submitted', 'approved_dosen', 'approved_kps', 'approved_kadep', 'recommendation_uploaded', 'rejected', 'rejected_instansi']],
                                    2 => ['name' => 'Tahap 2: Pelaksanaan', 'statuses' => ['ongoing']],
                                    3 => ['name' => 'Tahap 3: Laporan', 'statuses' => ['field_work_completed']],
                                    4 => ['name' => 'Tahap 4: Seminar', 'statuses' => ['seminar_requested', 'seminar_approved', 'seminar_scheduled', 'seminar_completed']],
                                    5 => ['name' => 'Tahap 5: Penyelesaian', 'statuses' => ['revision', 'revision_submitted', 'revision_approved', 'finished']]
                                ];
                                $currentStage = 0;
                                foreach ($stages as $stageNum => $stage) {
                                    if (in_array($status, $stage['statuses'])) {
                                        $currentStage = $stageNum;
                                        break;
                                    }
                                }
                                foreach ($stages as $stageNum => $stage) {
                                    $isCurrentStage = ($stageNum == $currentStage);
                                    $isCompletedStage = ($stageNum < $currentStage);
                                    $stageClass = $isCurrentStage ? 'active' : ($isCompletedStage ? 'completed' : '');
                                ?>
                                    <li class="timeline-item <?= $stageClass ?>">
                                        <span class="badge <?= $isCurrentStage ? 'bg-warning text-dark' : ($isCompletedStage ? 'bg-success' : 'bg-secondary') ?>">
                                            <?= $stage['name'] ?>
                                        </span>
                                        <?php if ($isCurrentStage) : ?>
                                            <div class="mt-2">
                                                <small>Status saat ini: <strong><?= get_status_label($status) ?></strong></small>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <!-- Detail Tab -->
                        <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                            <dl class="row">
                                <dt class="col-sm-4">Nama</dt>
                                <dd class="col-sm-8"><?= html_escape($student_detail->name ?? '') ?></dd>
                                <dt class="col-sm-4">NIM</dt>
                                <dd class="col-sm-8"><?= html_escape($student_detail->id ?? '') ?></dd>
                                <dt class="col-sm-4">Program Studi</dt>
                                <dd class="col-sm-8"><?= html_escape($student_detail->study_program ?? '') ?></dd>
                                <hr class="my-2">
                                <dt class="col-sm-4">Semester</dt>
                                <dd class="col-sm-8"><?= html_escape($application->semester_name ?? '-') ?></dd>
                                <dt class="col-sm-4">Dosen Pembimbing</dt>
                                <dd class="col-sm-8"><?= html_escape($application->lecturer_name ?? '-') ?></dd>
                                <dt class="col-sm-4">Instansi</dt>
                                <dd class="col-sm-8"><?= html_escape($application->place_name ?? '') ?></dd>
                                <dt class="col-sm-4">Alamat Instansi</dt>
                                <dd class="col-sm-8"><?= html_escape($application->place_address ?? '-') ?></dd>
                                <dt class="col-sm-4">Periode Kegiatan</dt>
                                <dd class="col-sm-8">
                                    <?= $application->activity_period_start ? date('d M Y', strtotime($application->activity_period_start)) : '-' ?> s/d
                                    <?= $application->activity_period_end ? date('d M Y', strtotime($application->activity_period_end)) : '-' ?>
                                </dd>
                            </dl>
                        </div>
                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                            <ul class="list-group">
                                <?php if (!empty($documents)) : ?>
                                    <?php foreach ($documents as $doc) : ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="<?= base_url($doc->file_path ?? '') ?>" target="_blank">
                                                <i class="bi bi-file-earmark-pdf me-2"></i>
                                                <?= ucfirst(str_replace('_', ' ', $doc->doc_type ?? '')) ?>
                                            </a>
                                            <span class="badge bg-secondary rounded-pill"><?= $doc->status ?? 'uploaded' ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li class="list-group-item">Belum ada dokumen yang diunggah.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->

<style>
    .timeline {
        list-style: none;
        padding: 0;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 7px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #6c757d;
    }

    .timeline-item.active::before {
        background: #ffc107;
    }

    .timeline-item.completed::before {
        background: #198754;
    }
</style>