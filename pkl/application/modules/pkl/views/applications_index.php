<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?php if (empty($applications)) : ?>
        <!-- If no applications exist -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-danger shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-danger">Belum Mendaftar PKL</h5>
                        <p class="card-text">Anda belum memiliki data pengajuan PKL.</p>
                        <a href="<?= site_url('pkl/applications/create') ?>" class="btn btn-success">
                            + Ajukan PKL Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-info shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-info">Pelacakan Progress PKL</h5>
                        <p class="card-text">
                            Setelah Anda mendaftar, progress PKL (tahap 1–18) akan ditampilkan di sini
                            dalam bentuk timeline.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    <?php else : ?>
        <!-- If applications exist -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">Data Formulir Pengajuan PKL</div>
                    <div class="card-body">
                        <?php $application = $applications[0] ?? null; // Get first application ?>
                        <dl class="row">
                            <dt class="col-sm-4">Nama</dt>
                            <dd class="col-sm-8"><?= html_escape($student_detail->name ?? '') ?></dd>

                            <dt class="col-sm-4">NIM</dt>
                            <dd class="col-sm-8"><?= html_escape($student_detail->id ?? '') ?></dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8"><?= html_escape($student_detail->email ?? '') ?></dd>

                            <dt class="col-sm-4">Program Studi</dt>
                            <dd class="col-sm-8"><?= html_escape($student_detail->study_program ?? '') ?></dd>

                            <dt class="col-sm-4">Semester</dt>
                            <dd class="col-sm-8"><?= html_escape($application->semester_name ?? '-') ?></dd>

                            <dt class="col-sm-4">No. WA</dt>
                            <dd class="col-sm-8">+62<?= html_escape($application->phone_number ?? '-') ?></dd>

                            <dt class="col-sm-4">Surat Ditujukan Kepada</dt>
                            <dd class="col-sm-8"><?= html_escape($application->addressed_to ?? '-') ?></dd>

                            <dt class="col-sm-4">Perusahaan / Instansi</dt>
                            <dd class="col-sm-8"><?= html_escape($application->place_name ?? '') ?></dd>

                            <dt class="col-sm-4">Alamat Instansi</dt>
                            <dd class="col-sm-8"><?= html_escape($application->place_address ?? '-') ?></dd>

                            <dt class="col-sm-4">Kegiatan Setara</dt>
                            <dd class="col-sm-8"><?= html_escape($application->equivalent_activity ?? '-') ?></dd>

                            <dt class="col-sm-4">Periode Kegiatan</dt>
                            <dd class="col-sm-8">
                                <?= $application->activity_period_start ? date('d-m-Y', strtotime($application->activity_period_start)) : '-' ?> s/d
                                <?= $application->activity_period_end ? date('d-m-Y', strtotime($application->activity_period_end)) : '-' ?>
                            </dd>

                            <dt class="col-sm-4">Dosen Pembimbing</dt>
                            <dd class="col-sm-8"><?= html_escape($application->lecturer_name ?? '-') ?></dd>
                        </dl>

                        <h6 class="mt-4">File Dokumen</h6>
                        <ul>
                            <?php if (!empty($documents)) : ?>
                                <?php foreach ($documents as $doc) : ?>
                                    <li>
                                        <a href="<?= base_url($doc->file_path ?? '') ?>" target="_blank">
                                            <?= ucfirst(str_replace('_', ' ', $doc->doc_type ?? '')) ?>
                                        </a>
                                        <?php if (!empty($doc->status)) : ?>
                                            <span class="badge bg-secondary"><?= $doc->status ?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li><em>Belum ada dokumen diupload</em></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Progress Timeline -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">Progress PKL</div>
                    <div class="card-body">
                        <ul class="timeline">
                            <?php
                            $steps = [
                                'submitted' => 'Pengajuan Dikirim',
                                'approved_dosen' => 'Disetujui Dosen Pembimbing',
                                'approved_kps' => 'Disetujui KPS',
                                'approved_kadep' => 'Disetujui Ketua Departemen',
                                'recommendation_uploaded' => 'Surat Rekomendasi Diunggah',
                                'rejected' => 'Ditolak',
                                'ongoing' => 'Sedang PKL',
                            ];
                            $currentStatus = $application->status ?? '';
                            foreach ($steps as $key => $label) :
                                $active = ($key === $currentStatus) ? 'active' : '';
                            ?>
                                <li class="timeline-item <?= $active ?>">
                                    <span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= $label ?>
                                    </span>
                                </li>
                                <?php if ($key === 'rejected' && $currentStatus === 'rejected') break; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php if (($application->status ?? '') === 'recommendation_uploaded') : ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning text-dark"><strong>Tindak Lanjut dari Instansi</strong></div>
                        <div class="card-body text-center">
                            <p class="card-text">Silakan laporkan hasil dari pengajuan surat rekomendasi ke instansi.</p>
                            <a href="<?= site_url('pkl/applications/report_decision/' . ($application->id ?? '')) ?>" class="btn btn-primary">Laporkan Keputusan Instansi</a>
                        </div>
                    </div>
                <?php elseif (in_array($application->status ?? '', ['rejected'])) : ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-danger text-white"><strong>Tindak Lanjut Diperlukan</strong></div>
                        <div class="card-body text-center">
                            <p class="card-text">Pengajuan Anda ditolak. Silakan perbaiki data Anda dan ajukan kembali.</p>
                            <a href="<?= site_url('pkl/applications/create/' . ($application->id ?? '')) ?>" class="btn btn-warning">Ajukan Ulang</a>
                        </div>
                    </div>
                <?php elseif (($application->status ?? '') === 'ongoing') : ?>
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-primary text-white"><strong>Pelaksanaan PKL</strong></div>
                        <div class="card-body text-center">
                            <p class="card-text">Status PKL Anda sedang berlangsung. Silakan isi logbook harian dan laporkan penyelesaian PKL di halaman pelaksanaan.</p>
                            <a href="<?= site_url('pkl/applications/pelaksanaan/' . ($application->id ?? '')) ?>" class="btn btn-info">Buka Halaman Pelaksanaan</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

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
        background: #198754;
    }
</style>