<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $stats */
/** @var array $recentInquiries */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Studio Content Management</h1>
        <p class="text-muted mb-0">NJ Interiors — Single-Page Portfolio (JSON-backed, zero database)</p>
    </div>
    <a href="<?= Url::to(['/admin/project-create']) ?>" class="btn btn-nj">+ New Project</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3">
            <span class="text-muted small text-uppercase">Portfolio Projects</span>
            <span class="h2 mb-0 mt-1"><?= (int)$stats['projects'] ?></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <span class="text-muted small text-uppercase">Categories</span>
            <span class="h2 mb-0 mt-1"><?= (int)$stats['categories'] ?></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <span class="text-muted small text-uppercase">Legacy Entries</span>
            <span class="h2 mb-0 mt-1"><?= (int)$stats['timeline'] ?></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <span class="text-muted small text-uppercase">Unread Inquiries</span>
            <span class="h2 mb-0 mt-1"><?= (int)$stats['unread'] ?> / <?= (int)$stats['inquiries'] ?></span>
        </div>
    </div>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Recent Contact Inquiries</h2>
        <a href="<?= Url::to(['/admin/inquiries']) ?>" class="btn btn-sm btn-outline-dark">View All</a>
    </div>
    <?php if (empty($recentInquiries)): ?>
        <p class="text-muted mb-0">No inquiries recorded yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Project Type</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentInquiries as $inq): ?>
                        <tr>
                            <td><?= Html::encode($inq['name'] ?? '') ?></td>
                            <td><?= Html::encode($inq['email'] ?? '') ?></td>
                            <td><?= Html::encode($inq['project_type'] ?? '') ?></td>
                            <td><?= date('d M Y, H:i', (int)($inq['created_at'] ?? time())) ?></td>
                            <td>
                                <span class="badge <?= !empty($inq['is_read']) ? 'bg-secondary' : 'bg-success' ?>">
                                    <?= !empty($inq['is_read']) ? 'Read' : 'New' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
