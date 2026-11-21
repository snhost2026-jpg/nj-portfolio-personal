<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $project */
/** @var array $categories */

use yii\helpers\Html;
use yii\helpers\Url;

$isNew = empty($project['id']);
$this->title = $isNew ? 'Create Project' : 'Update Project';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
    <a href="<?= Url::to(['/admin/projects']) ?>" class="btn btn-outline-dark">Back to Projects</a>
</div>

<div class="card p-4">
    <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Title *</label>
            <input type="text" name="Project[title]" class="form-control" required value="<?= Html::encode($project['title'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Slug</label>
            <input type="text" name="Project[slug]" class="form-control" placeholder="Auto-generated if blank" value="<?= Html::encode($project['slug'] ?? '') ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Category</label>
            <select name="Project[category_id]" class="form-select">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['id'] ?>" <?= (int)($project['category_id'] ?? 0) === (int)$cat['id'] ? 'selected' : '' ?>>
                        <?= Html::encode($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Location</label>
            <input type="text" name="Project[location]" class="form-control" value="<?= Html::encode($project['location'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Year</label>
            <input type="text" name="Project[year]" class="form-control" value="<?= Html::encode($project['year'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Aspect Ratio</label>
            <select name="Project[aspect_ratio]" class="form-select">
                <option value="landscape" <?= ($project['aspect_ratio'] ?? '') === 'landscape' ? 'selected' : '' ?>>Landscape</option>
                <option value="portrait" <?= ($project['aspect_ratio'] ?? '') === 'portrait' ? 'selected' : '' ?>>Portrait</option>
                <option value="square" <?= ($project['aspect_ratio'] ?? '') === 'square' ? 'selected' : '' ?>>Square</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Summary</label>
        <textarea name="Project[summary]" class="form-control" rows="2"><?= Html::encode($project['summary'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="Project[description]" class="form-control" rows="4"><?= Html::encode($project['description'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Design Concept</label>
        <textarea name="Project[design_concept]" class="form-control" rows="3"><?= Html::encode($project['design_concept'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Materials &amp; Finishes</label>
        <textarea name="Project[materials_finishes]" class="form-control" rows="2"><?= Html::encode($project['materials_finishes'] ?? '') ?></textarea>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Cover Image (auto-converted to WebP)</label>
            <input type="file" name="cover_file" class="form-control" accept="image/jpeg,image/png,image/webp">
            <?php if (!empty($project['cover_image'])): ?>
                <div class="mt-2">
                    <img src="<?= Html::encode($project['cover_image']) ?>" alt="" style="max-height:110px;border-radius:4px;">
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Additional Gallery Images (auto-converted to WebP)</label>
            <input type="file" name="gallery_files[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp">
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="Project[sort_order]" class="form-control" value="<?= (int)($project['sort_order'] ?? 10) ?>">
        </div>
        <div class="col-md-3 pt-4">
            <label class="form-check-label">
                <input type="checkbox" name="Project[is_featured]" value="1" class="form-check-input" <?= !empty($project['is_featured']) ? 'checked' : '' ?>> Featured
            </label>
        </div>
        <div class="col-md-3 pt-4">
            <label class="form-check-label">
                <input type="checkbox" name="Project[is_sample]" value="1" class="form-check-input" <?= !empty($project['is_sample']) ? 'checked' : '' ?>> Sample Study
            </label>
        </div>
        <div class="col-md-3 pt-4">
            <label class="form-check-label">
                <input type="checkbox" name="Project[status]" value="1" class="form-check-input" <?= !empty($project['status']) ? 'checked' : '' ?>> Published
            </label>
        </div>
    </div>

    <?= Html::submitButton('Save Project', ['class' => 'btn btn-nj px-4']) ?>
    <?= Html::endForm() ?>
</div>
