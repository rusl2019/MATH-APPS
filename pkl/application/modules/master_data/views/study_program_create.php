<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <?php echo form_open('/master_data/study_program/store', ['id' => 'formCreate']); ?>
    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="lecturer_id" class="form-label">Ketua Program Studi</label>
            <select class="form-select" id="lecturer_id_create" name="lecturer_id" style="width: 100%;">
                <option value="" selected disabled>Pilih Ketua Program Studi</option>
                <?php foreach ($lecturers as $lecturer) : ?>
                    <option value="<?php echo $lecturer['id']; ?>"><?php echo $lecturer['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
    <?php echo form_close(); ?>
</div>