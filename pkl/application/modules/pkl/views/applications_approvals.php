<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>
    
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
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
                    <?php foreach ($applications ?? [] as $application) : ?>
                        <tr>
                            <td><?= html_escape($application->student_name ?? '-') ?></td>
                            <td><?= html_escape($application->title ?? '-') ?></td>
                            <td><?= html_escape($application->lecturer_name ?? '-') ?></td>
                            <td><?= html_escape($application->place_name ?? '-') ?></td>
                            <td><?= $application->submission_date ? date('d-m-Y', strtotime($application->submission_date)) : '-' ?></td>
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
                                <?php if ($application->status === 'submitted') : ?>
                                    <a href="<?= site_url('pkl/applications/approve/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-success" 
                                       onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">Setujui Pengajuan</a>
                                    
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $application->id ?? '' ?>">Tolak Pengajuan</button>
                                
                                <?php elseif ($application->status === 'seminar_requested') : ?>
                                    <a href="<?= site_url('pkl/seminar/approve/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-success" 
                                       onclick="return confirm('Apakah Anda yakin ingin menyetujui laporan ini untuk seminar?')">Setujui Seminar</a>
                                    
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectReportModal<?= $application->id ?? '' ?>">Tolak Laporan</button>

                                <?php elseif (in_array($application->status, ['seminar_approved', 'seminar_scheduled', 'seminar_completed', 'report_rejected', 'revision_submitted'])) : ?>
                                    <a href="<?= site_url('pkl/seminar/manage/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-primary">Kelola Seminar</a>
                                <?php endif; ?>


                                <!-- Reject Modal for Application -->
                                <div class="modal fade" id="rejectModal<?= $application->id ?? '' ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <?= form_open(site_url('pkl/applications/reject/' . ($application->id ?? ''))) ?>
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
                                            <?= form_close(); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal for Report -->
                                <div class="modal fade" id="rejectReportModal<?= $application->id ?? '' ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <?= form_open(site_url('pkl/seminar/reject_report/' . ($application->id ?? ''))) ?>
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
                                            <?= form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>