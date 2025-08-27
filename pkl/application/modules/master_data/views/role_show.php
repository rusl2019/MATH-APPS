<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Detail <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label" for="name">Nama</label>
            <input type="text" class="form-control" name="name" id="name" disabled="" value="<?php echo $name; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Deskripsi</label>
            <input type="text" class="form-control" name="description" id="description" disabled="" value="<?php echo $description; ?>">
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
                                <?php
                                $create_checked = isset($permissions[$module['id']]['create']) ? $permissions[$module['id']]['create'] : FALSE;
                                echo form_checkbox('can_create[]', $module['id'], $create_checked, ['disabled' => '', 'class' => 'form-check-input']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $read_checked = isset($permissions[$module['id']]['read']) ? $permissions[$module['id']]['read'] : FALSE;
                                echo form_checkbox('can_read[]', $module['id'], $read_checked, ['disabled' => '', 'class' => 'form-check-input']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $update_checked = isset($permissions[$module['id']]['update']) ? $permissions[$module['id']]['update'] : FALSE;
                                echo form_checkbox('can_update[]', $module['id'], $update_checked, ['disabled' => '', 'class' => 'form-check-input']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $delete_checked = isset($permissions[$module['id']]['delete']) ? $permissions[$module['id']]['delete'] : FALSE;
                                echo form_checkbox('can_delete[]', $module['id'], $delete_checked, ['disabled' => '', 'class' => 'form-check-input']);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>