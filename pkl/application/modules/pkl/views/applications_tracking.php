<style>
    /* Timeline vertical */
    .timeline {
        position: relative;
        margin: 20px 0;
        padding-left: 40px;
        border-left: 3px solid #0d6efd;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -11px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #0d6efd;
    }

    .timeline-item.approved::before {
        background: #198754;
        /* hijau untuk approved */
        border-color: #198754;
    }

    .timeline-item.rejected::before {
        background: #dc3545;
        /* merah untuk rejected */
        border-color: #dc3545;
    }

    .timeline-item.pending::before {
        background: #ffc107;
        /* kuning untuk pending */
        border-color: #ffc107;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4"><?= $title ?></h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= $application->title ?></h5>
            <p><strong>Status Saat Ini:</strong>
                <span class="badge bg-info"><?= $application->status ?></span>
            </p>
            <p><strong>Tanggal Pengajuan:</strong> <?= $application->submission_date ?></p>
        </div>
    </div>

    <div class="timeline">
        <?php if (!empty($workflow)) : ?>
            <?php foreach ($workflow as $w) : ?>
                <div class="timeline-item <?= $w->status ?>">
                    <h6 class="mb-1"><?= $w->step_name ?></h6>
                    <small class="text-muted">
                        <?= ucfirst($w->status) ?> oleh
                        <?= $w->actor_name ?: ucfirst($w->role) ?>
                        (<?= $w->action_date ?>)
                    </small>
                    <?php if ($w->remarks) : ?>
                        <p class="mb-0"><em>Catatan: <?= $w->remarks ?></em></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-muted">Belum ada progress approval.</p>
        <?php endif; ?>
    </div>

    <a href="<?= site_url('pkl/applications') ?>" class="btn btn-secondary mt-3">Kembali</a>
</div>