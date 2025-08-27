<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Detail <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="form-group mb-2">
            <label>ID</label>
            <input type="text" class="form-control" disabled value="<?php echo $id; ?>">
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" disabled value="<?php echo $name; ?>">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>