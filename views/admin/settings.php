<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $settings */

use yii\helpers\Html;

$this->title = 'Site Settings & SEO';
$labels = [
    'seo_title' => 'SEO Page Title',
    'seo_description' => 'SEO Meta Description',
    'seo_keywords' => 'SEO Keywords',
    'about_heading' => 'Journey Heading',
    'about_body' => 'Journey Narrative',
    'vision_quote' => 'Personal Vision Quote',
    'contact_email' => 'Studio Email',
    'contact_phone' => 'Studio Phone',
    'contact_address' => 'Studio Location',
    'social_instagram' => 'Instagram URL',
    'social_linkedin' => 'LinkedIn URL',
    'social_behance' => 'Behance URL',
    'social_youtube' => 'YouTube URL',
];
?>

<h1 class="h3 mb-4">About, Contact, Social &amp; SEO Metadata</h1>

<div class="card p-4">
    <?= Html::beginForm(['/admin/settings'], 'post') ?>
    <?php foreach ($settings as $key => $value): ?>
        <div class="mb-3">
            <label class="form-label fw-semibold" for="setting-<?= Html::encode($key) ?>">
                <?= Html::encode($labels[$key] ?? $key) ?>
            </label>
            <?php if (strlen((string)$value) > 90 || str_contains($key, 'body') || str_contains($key, 'quote') || str_contains($key, 'description')): ?>
                <textarea
                    id="setting-<?= Html::encode($key) ?>"
                    name="Settings[<?= Html::encode($key) ?>]"
                    class="form-control"
                    rows="3"
                ><?= Html::encode($value) ?></textarea>
            <?php else: ?>
                <input
                    type="text"
                    id="setting-<?= Html::encode($key) ?>"
                    name="Settings[<?= Html::encode($key) ?>]"
                    class="form-control"
                    value="<?= Html::encode($value) ?>"
                >
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="mt-4">
        <?= Html::submitButton('Save All Settings', ['class' => 'btn btn-nj px-4']) ?>
    </div>
    <?= Html::endForm() ?>
</div>
