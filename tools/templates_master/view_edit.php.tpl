<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Edit <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <?php echo form_open('/{{module_name}}/{{entity_name_lower}}/update', ['id' => 'formEdit']); ?>
    <div class="modal-body">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group mb-2">
            <label for="id_edit">ID</label>
            <input type="text" class="form-control" id="id_edit" value="<?php echo $id; ?>" disabled>
        </div>
        <div class="form-group">
            <label for="name_edit">Name</label>
            <input type="text" class="form-control" id="name_edit" name="name" value="<?php echo $name; ?>" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
    <?php echo form_close(); ?>
</div>