<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Detail <?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="mb-3" for="name">Nama</label>
            <input type="text" class="form-control" name="name" id="name" disabled="" value="<?php echo $name; ?>">
        </div>
        <div class="mb-3">
            <label class="mb-3" for="lecturer_name">Ketua Program Studi</label>
            <input type="text" class="form-control" name="lecturer_name" id="lecturer_name" disabled="" value="<?php echo $lecturer_name; ?>">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>