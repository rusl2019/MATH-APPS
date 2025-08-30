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
            Tidak ada pengajuan PKL yang tersedia.
        </div>
    <?php else : ?>
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
                <?php foreach ($applications as $a) : ?>
                    <tr>
                        <td><?= html_escape($a->student_name ?? '-') ?></td>
                        <td><?= html_escape($a->title ?? '-') ?></td>
                        <td><?= html_escape($a->lecturer_name ?? '-') ?></td>
                        <td><?= html_escape($a->place_name ?? '-') ?></td>
                        <td>
                            <?= $a->submission_date ? date('d-m-Y', strtotime($a->submission_date)) : '-' ?>
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
                                'ongoing' => 'Sedang Berlangsung',
                                'finished' => 'Selesai'
                            ];
                            $status = $a->status ?? '';
                            $badge_class = ($status === 'rejected' || $status === 'rejected_instansi') ? 'bg-danger' : 'bg-success';
                            ?>
                            <span class="badge <?= $badge_class ?>">
                                <?= $status_labels[$status] ?? $status ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($a->status === 'approved_kadep') : ?>
                                <a href="<?= site_url('pkl/applications/upload_recommendation/' . ($a->id ?? '')) ?>" class="btn btn-sm btn-primary">Unggah Surat Rekomendasi</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>