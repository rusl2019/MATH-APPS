<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?= form_open_multipart('pkl/applications/upload_recommendation/' . $application_id); ?>

    <div class="mb-3">
        <label class="form-label">Unggah Surat Rekomendasi (PDF) <span class="text-danger">*</span></label>
        <input type="file" name="recommendation_file" class="form-control" accept=".pdf" required>
    </div>

    <button type="submit" class="btn btn-primary">Unggah</button>
    <a href="<?= site_url('pkl/applications/all_applications') ?>" class="btn btn-secondary">Batal</a>
    <?= form_close(); ?>
</div>