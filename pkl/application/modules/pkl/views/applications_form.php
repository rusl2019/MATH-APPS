<div class="container mt-5">
    <h2 class="mb-4">Form Pengajuan PKL</h2>

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?= form_open_multipart('pkl/applications/create'); ?>

    <!-- Data Mahasiswa (readonly dari session) -->
    <div class="mb-3">
        <label class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" value="<?= html_escape($student_detail->name ?? '') ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" class="form-control" value="<?= html_escape($student_detail->id ?? '') ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Email UB</label>
        <input type="email" class="form-control" value="<?= html_escape($student_detail->email ?? '') ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Program Studi</label>
        <input type="text" class="form-control" value="<?= html_escape($student_detail->study_program ?? '') ?>" readonly>
    </div>

    <!-- Input Tambahan -->
    <div class="mb-3">
        <label class="form-label">Nomor Telepon / WhatsApp Aktif <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">+62</span>
            <input type="tel" name="phone_number" class="form-control" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Dosen Pembimbing PKL <span class="text-danger">*</span></label>
        <select name="lecturer_id" class="form-select" required>
            <option value="">-- Pilih Dosen Pembimbing --</option>
            <?php foreach ($lecturers ?? [] as $d) : ?>
                <option value="<?= $d->id ?? '' ?>"><?= $d->name ?? '' ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Instansi / Perusahaan <span class="text-danger">*</span></label>
        <select name="place_id" class="form-select" required>
            <option value="">-- Pilih Instansi --</option>
            <?php foreach ($places ?? [] as $p) : ?>
                <option value="<?= $p->id ?? '' ?>"><?= $p->name ?? '' ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Surat Ditujukan Kepada <span class="text-danger">*</span></label>
        <input type="text" name="addressed_to" class="form-control" placeholder="Contoh: Kepala HRD / Direktur" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Kegiatan Setara (Opsional)</label>
        <input type="text" name="equivalent_activity" class="form-control" placeholder="Isi jika kegiatan setara">
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Periode Kegiatan (Mulai) <span class="text-danger">*</span></label>
            <input type="date" name="activity_period_start" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Periode Kegiatan (Selesai) <span class="text-danger">*</span></label>
            <input type="date" name="activity_period_end" class="form-control" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Judul PKL <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
        <input type="text" name="type" class="form-control" required>
    </div>

    <!-- Upload Dokumen -->
    <div class="mb-3">
        <label class="form-label">Unggah Portofolio (PDF) <span class="text-danger">*</span></label>
        <input type="file" name="portfolio_file" class="form-control" accept=".pdf" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Unggah Proposal (Opsional)</label>
        <input type="file" name="proposal_file" class="form-control" accept=".pdf">
    </div>

    <div class="mb-3">
        <label class="form-label">Unggah Lembar Konsultasi Bimbingan PKL <span class="text-danger">*</span></label>
        <input type="file" name="consultation_file" class="form-control" accept=".pdf" required>
    </div>

    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    <?= form_close(); ?>
</div>