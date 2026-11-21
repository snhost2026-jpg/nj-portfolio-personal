<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $categories */

use yii\helpers\Html;

$this->title = 'Categories';
?>

<h1 class="h3 mb-4">Project Categories</h1>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card p-4">
            <h2 class="h5 mb-3">Add Category</h2>
            <?= Html::beginForm('', 'post') ?>
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="Category[name]" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="Category[slug]" class="form-control" placeholder="Auto-generated if blank">
            </div>
            <div class="mb-3">
                <label class="form-label">Sort Order</label>
                <input type="number" name="Category[sort_order]" class="form-control" value="10">
            </div>
            <?= Html::submitButton('Add Category', ['class' => 'btn btn-nj']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-4">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Order</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= Html::encode($cat['name']) ?></td>
                            <td><code><?= Html::encode($cat['slug']) ?></code></td>
                            <td><?= (int)($cat['sort_order'] ?? 0) ?></td>
                            <td class="text-end">
                                <?= Html::beginForm(['/admin/category-delete', 'id' => $cat['id']], 'post', ['class' => 'd-inline']) ?>
                                <?= Html::submitButton('Delete', ['class' => 'btn btn-sm btn-outline-danger']) ?>
                                <?= Html::endForm() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
