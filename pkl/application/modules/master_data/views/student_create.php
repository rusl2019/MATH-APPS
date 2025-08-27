<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="showModalLabel">Create <?php echo $title; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <?php echo form_open('/master_data/student/store', ['id' => 'formCreate']); ?>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label" for="id">NIM</label>
            <input type="text" class="form-control" id="id" name="id">
        </div>
        <div class="mb-3">
            <label class="form-label" for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label" for="study_program_id">Program Studi</label>
            <select class="form-select" id="study_program_id" name="study_program_id">
                <option value="">Pilih Program Studi</option>
                <?php foreach ($study_programs as $study_program) : ?>
                    <option value="<?php echo $study_program['id']; ?>"><?php echo $study_program['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="roles_create">Roles</label>
            <select class="form-select" multiple="multiple" name="roles[]" id="roles_create" style="width: 100%;">
                <?php foreach ($roles as $role) : ?>
                    <option value="<?php echo $role['id']; ?>">
                        <?php echo ucfirst(strtolower($role['name'])); ?>
                    </option>
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