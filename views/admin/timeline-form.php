<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $entry */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Edit Timeline Entry';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Timeline Entry</h1>
    <a href="<?= Url::to(['/admin/timeline']) ?>" class="btn btn-outline-dark">Back</a>
</div>

<div class="card p-4">
    <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>
    <div class="mb-3">
        <label class="form-label">Era Label</label>
        <input type="text" name="Timeline[era_label]" class="form-control" value="<?= Html::encode($entry['era_label'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="Timeline[title]" class="form-control" value="<?= Html::encode($entry['title'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Subtitle</label>
        <input type="text" name="Timeline[subtitle]" class="form-control" value="<?= Html::encode($entry['subtitle'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="Timeline[description]" class="form-control" rows="4" required><?= Html::encode($entry['description'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Replace Image (auto-converted to WebP)</label>
        <input type="file" name="timeline_image" class="form-control" accept="image/jpeg,image/png,image/webp">
    </div>
    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="Timeline[sort_order]" class="form-control" value="<?= (int)($entry['sort_order'] ?? 1) ?>">
        </div>
        <div class="col-md-4 pt-4">
            <label class="form-check-label">
                <input type="checkbox" name="Timeline[is_active]" value="1" class="form-check-input" <?= !empty($entry['is_active']) ? 'checked' : '' ?>> Active on site
            </label>
        </div>
    </div>
    <?= Html::submitButton('Save Entry', ['class' => 'btn btn-nj']) ?>
    <?= Html::endForm() ?>
</div>
