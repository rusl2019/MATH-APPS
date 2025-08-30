<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Unggah Surat Rekomendasi
        </div>
        <div class="card-body">
            <p>Silakan unggah surat rekomendasi dalam format PDF.</p>
            
            <?= form_open_multipart('pkl/applications/upload_recommendation/' . $application_id); ?>
            
            <div class="mb-4">
                <label class="form-label">Unggah Surat Rekomendasi (PDF) <span class="text-danger">*</span></label>
                <input type="file" name="recommendation_file" class="form-control" accept=".pdf" required>
                <div class="form-text">Unggah surat rekomendasi yang telah diterbitkan dan ditandatangani.</div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= site_url('pkl/applications/all_applications') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Unggah Surat</button>
            </div>
            
            <?= form_close(); ?>
        </div>
    </div>
</div>