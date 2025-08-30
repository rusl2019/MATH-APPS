<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?= form_open_multipart('pkl/applications/report_decision/' . $application->id); ?>

    <div class="mb-3">
        <p>Pilih keputusan dari instansi terkait pengajuan PKL Anda:</p>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="decision" id="decision_accepted" value="accepted" required>
            <label class="form-check-label" for="decision_accepted">
                Diterima oleh Instansi
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="decision" id="decision_rejected" value="rejected" required>
            <label class="form-check-label" for="decision_rejected">
                Ditolak oleh Instansi
            </label>
        </div>
    </div>

    <div class="mb-3" id="upload_section">
        <label for="response_letter" class="form-label">Unggah Surat Balasan dari Instansi (PDF) <span class="text-danger">*</span></label>
        <input type="file" name="response_letter" id="response_letter" class="form-control" accept=".pdf" required>
    </div>

    <button type="submit" class="btn btn-primary">Kirim Laporan</button>
    <a href="<?= site_url('pkl/applications') ?>" class="btn btn-secondary">Batal</a>

    <?= form_close(); ?>
</div>