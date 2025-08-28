<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

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
            <?php foreach ($applications ?? [] as $a) : ?>
                <tr>
                    <td><?= $a->student_name ?? '-' ?></td>
                    <td><?= $a->title ?? '-' ?></td>
                    <td><?= $a->lecturer_name ?? '-' ?></td>
                    <td><?= $a->place_name ?? '-' ?></td>
                    <td><?= $a->submission_date ?? '-' ?></td>
                    <td>
                        <a href="<?= site_url('pkl/applications/approve/' . ($a->id ?? '')) ?>" class="btn btn-sm btn-success">Setujui</a>
                        <!-- tombol reject dengan modal alasan -->
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $a->id ?? '' ?>">Tolak</button>

                        <!-- Modal reject -->
                        <div class="modal fade" id="rejectModal<?= $a->id ?? '' ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="<?= site_url('pkl/applications/reject/' . ($a->id ?? '')) ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Alasan Penolakan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <textarea name="remarks" class="form-control" placeholder="Tuliskan alasan penolakan..." required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>