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
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>