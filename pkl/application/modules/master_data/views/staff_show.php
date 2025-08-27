<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Detail <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label" for="id">ID</label>
            <input type="text" class="form-control" name="id" id="id" disabled value="<?php echo $id; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" disabled value="<?php echo $name; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="text" class="form-control" name="email" id="email" disabled value="<?php echo $email; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="roles_detail">Roles</label>
            <select class="form-select" multiple="multiple" name="roles[]" id="roles_detail" style="width: 100%;" disabled>
                <?php foreach ($roles as $role) : ?>
                    <option value="<?php echo $role['id']; ?>" <?php echo in_array($role['id'], $role_ids) ? 'selected' : ''; ?>>
                        <?php echo ucfirst(strtolower($role['name'])); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>