<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <?php echo form_open('/master_data/role/store', ['id' => 'formCreate']); ?>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label" for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Deskripsi</label>
            <input type="text" class="form-control" id="description" name="description">
        </div>
        <div class="mb-3">
            <label class="form-label">Akses Fitur</label>
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Fitur</th>
                        <th class="text-center">Create</th>
                        <th class="text-center">Read</th>
                        <th class="text-center">Update</th>
                        <th class="text-center">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module) : ?>
                        <tr>
                            <td><?php echo $module['description']; ?></td>
                            <td class="text-center">
                                <?php echo form_checkbox('can_create[]', $module['id'], FALSE, 'class="form-check-input"'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo form_checkbox('can_read[]', $module['id'], FALSE, 'class="form-check-input"'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo form_checkbox('can_update[]', $module['id'], FALSE, 'class="form-check-input"'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo form_checkbox('can_delete[]', $module['id'], FALSE, 'class="form-check-input"'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
    <?php echo form_close(); ?>
</div>