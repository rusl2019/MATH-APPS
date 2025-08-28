<div class="container mt-5">
    <h2 class="mb-4">Daftar Pengajuan PKL Saya</h2>
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <a href="<?= site_url('pkl/applications/create') ?>" class="btn btn-success mb-3">+ Pengajuan Baru</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Judul</th>
                <th>Dosen Pembimbing</th>
                <th>Instansi</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $a) : ?>
                <tr>
                    <td><?= $a->title ?></td>
                    <td><?= $a->lecturer_name ?></td>
                    <td><?= $a->place_name ?></td>
                    <td><span class="badge bg-info"><?= $a->status ?></span></td>
                    <td><?= $a->submission_date ?></td>
                    <td>
                        <a href="<?= site_url('pkl/applications/tracking/' . $a->id) ?>" class="btn btn-sm btn-primary">
                            Lihat Tracking
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>