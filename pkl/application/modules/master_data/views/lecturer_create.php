<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <?php echo form_open('/master_data/lecturer/store', ['id' => 'formCreate']); ?>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label" for="id">NIP</label>
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
            <label class="form-label" for="rank">Golongan</label>
            <input type="text" class="form-control" id="rank" name="rank">
        </div>
        <div class="mb-3">
            <label class="form-label" for="position">Jabatan</label>
            <input type="text" class="form-control" id="position" name="position">
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