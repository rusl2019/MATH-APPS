<div class="container mt-5">
    <h2 class="mb-4"><?= ($form_data) ? 'Form Pengajuan Ulang PKL' : 'Form Pengajuan PKL' ?></h2>

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?= form_open_multipart('pkl/applications/create'); ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Student Data (readonly from session) -->
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

    <div class="mb-3">
        <label class="form-label">Semester Pengajuan <span class="text-danger">*</span></label>
        <select name="semester_id" class="form-select" required>
            <option value="">-- Pilih Semester --</option>
            <?php foreach ($semesters ?? [] as $semester) : ?>
                <option value="<?= $semester->id ?>" <?= ($semester->id == ($form_data->semester_id ?? $active_semester_id)) ? 'selected' : '' ?>>
                    <?= $semester->name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Additional Inputs -->
    <div class="mb-3">
        <label class="form-label">Nomor Telepon / WhatsApp Aktif <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">+62</span>
            <input type="tel" name="phone_number" class="form-control" required value="<?= html_escape($form_data->phone_number ?? '') ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Dosen Pembimbing PKL <span class="text-danger">*</span></label>
        <select name="lecturer_id" class="form-select" required>
            <option value="">-- Pilih Dosen Pembimbing --</option>
            <?php foreach ($lecturers ?? [] as $lecturer) : ?>
                <option value="<?= $lecturer->id ?? '' ?>" <?= ($lecturer->id == ($form_data->lecturer_id ?? '')) ? 'selected' : '' ?>><?= $lecturer->name ?? '' ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Instansi / Perusahaan <span class="text-danger">*</span></label>
        <select name="place_id" class="form-select" required>
            <option value="">-- Pilih Instansi --</option>
            <?php foreach ($places ?? [] as $place) : ?>
                <option value="<?= $place->id ?? '' ?>" <?= ($place->id == ($form_data->place_id ?? '')) ? 'selected' : '' ?>><?= $place->name ?? '' ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Surat Ditujukan Kepada <span class="text-danger">*</span></label>
        <input type="text" name="addressed_to" class="form-control" placeholder="Contoh: Kepala HRD / Direktur" required value="<?= html_escape($form_data->addressed_to ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Kegiatan Setara (Opsional)</label>
        <input type="text" name="equivalent_activity" class="form-control" placeholder="Isi jika kegiatan setara" value="<?= html_escape($form_data->equivalent_activity ?? '') ?>">
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Periode Kegiatan (Mulai) <span class="text-danger">*</span></label>
            <input type="date" name="activity_period_start" class="form-control" required value="<?= html_escape($form_data->activity_period_start ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Periode Kegiatan (Selesai) <span class="text-danger">*</span></label>
            <input type="date" name="activity_period_end" class="form-control" required value="<?= html_escape($form_data->activity_period_end ?? '') ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Judul PKL <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" required value="<?= html_escape($form_data->title ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
        <input type="text" name="type" class="form-control" required value="<?= html_escape($form_data->type ?? '') ?>">
    </div>

    <!-- Document Uploads -->
    <div class="mb-3">
        <label class="form-label">Unggah Portofolio (PDF) <span class="text-danger">*</span></label>
        <input type="file" name="portfolio_file" class="form-control" accept=".pdf" required>
        <?php if (!empty($form_data->portfolio_file)) : ?>
            <div class="form-text">File sebelumnya: <?= $form_data->portfolio_file ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Unggah Proposal (Opsional)</label>
        <input type="file" name="proposal_file" class="form-control" accept=".pdf">
        <?php if (!empty($form_data->proposal_file)) : ?>
            <div class="form-text">File sebelumnya: <?= $form_data->proposal_file ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Unggah Lembar Konsultasi Bimbingan PKL <span class="text-danger">*</span></label>
        <input type="file" name="consultation_file" class="form-control" accept=".pdf" required>
        <?php if (!empty($form_data->consultation_file)) : ?>
            <div class="form-text">File sebelumnya: <?= $form_data->consultation_file ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    <a href="<?= site_url('pkl/applications') ?>" class="btn btn-secondary">Batal</a>
    <?= form_close(); ?>
</div>