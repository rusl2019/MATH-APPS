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
                                <a href="<?= site_url('pkl/applications/approve/' . ($application->id ?? '')) ?>" class="btn btn-sm btn-success" 
                                   onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">Setujui</a>
                                
                                <!-- Reject button with modal -->
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $application->id ?? '' ?>">Tolak</button>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal<?= $application->id ?? '' ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <?= form_open(site_url('pkl/applications/reject/' . ($application->id ?? ''))) ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Alasan Penolakan</h5>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>