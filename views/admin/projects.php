<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $projects */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Portfolio Projects';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Portfolio Projects</h1>
    <a href="<?= Url::to(['/admin/project-create']) ?>" class="btn btn-nj">+ Add Project</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td>
                            <?php if (!empty($project['cover_image'])): ?>
                                <img src="<?= Html::encode($project['cover_image']) ?>" alt="" style="width:72px;height:48px;object-fit:cover;border-radius:4px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= Html::encode($project['title']) ?></strong><br>
                            <small class="text-muted"><?= Html::encode($project['slug']) ?></small>
                        </td>
                        <td><?= Html::encode($project['category']['name'] ?? '-') ?></td>
                        <td><?= Html::encode($project['location'] ?? '') ?></td>
                        <td>
                            <?= !empty($project['is_sample']) ? '<span class="badge bg-warning text-dark">Sample</span>' : '<span class="badge bg-dark">Live</span>' ?>
                        </td>
                        <td>
                            <?= !empty($project['status']) ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-secondary">Draft</span>' ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= Url::to(['/admin/project-update', 'id' => $project['id']]) ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                            <?= Html::beginForm(['/admin/project-delete', 'id' => $project['id']], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::submitButton('Delete', ['class' => 'btn btn-sm btn-outline-danger', 'onclick' => 'return confirm("Delete this project?")']) ?>
                            <?= Html::endForm() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
