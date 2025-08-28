<div class="container mt-5">
    <h2 class="mb-4">Form Pengajuan PKL</h2>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?php echo form_open_multipart('pkl/applications/create'); ?>

    <div class="mb-3">
        <label class="form-label">Dosen Pembimbing</label>
        <select name="lecturer_id" class="form-select" required>
            <option value="">-- Pilih Dosen --</option>
            <?php foreach ($lecturers as $d) : ?>
                <option value="<?= $d->id ?>"><?= $d->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Tempat PKL</label>
        <select name="place_id" class="form-select" required>
            <option value="">-- Pilih Instansi --</option>
            <?php foreach ($places as $p) : ?>
                <option value="<?= $p->id ?>"><?= $p->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Judul PKL</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Jenis Kegiatan</label>
        <input type="text" name="type" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Upload Portofolio (PDF/DOC)</label>
        <input type="file" name="portofolio" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Upload Proposal (opsional)</label>
        <input type="file" name="proposal" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    <?php echo form_close(); ?>
</div>