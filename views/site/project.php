<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $project */
/** @var array $settings */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['settings'] = $settings;
$this->title = $project['title'] . ' — Nitesh Jangda';
?>

<section class="nj-section" style="padding-top: 140px;">
    <div class="nj-container">
        <a href="<?= Url::to(['/site/index']) ?>#projects" class="nj-btn-pill" style="margin-bottom: 2rem;">
            &larr; Back to Portfolio
        </a>

        <div class="nj-section-header">
            <span class="nj-index-tag"><?= Html::encode($project['category']['name'] ?? 'Interior Design') ?></span>
            <h1 class="nj-h2"><?= Html::encode($project['title']) ?></h1>
            <p class="nj-lead"><?= Html::encode($project['location'] ?? '') ?> &middot; <?= Html::encode($project['year'] ?? '') ?></p>
        </div>

        <div class="nj-drawer-hero" style="margin-bottom: 3rem;">
            <img src="<?= Html::encode($project['cover_image']) ?>" alt="<?= Html::encode($project['title']) ?>" style="width:100%; border-radius:4px;">
        </div>

        <div class="nj-drawer-specs">
            <div>
                <span class="nj-micro-tag">OVERVIEW</span>
                <p class="nj-body-copy"><?= Html::encode($project['description'] ?? '') ?></p>
            </div>
            <div>
                <span class="nj-micro-tag">DESIGN CONCEPT</span>
                <p class="nj-body-copy"><?= Html::encode($project['design_concept'] ?? '') ?></p>
            </div>
            <div>
                <span class="nj-micro-tag">MATERIALS &amp; FINISHES</span>
                <p class="nj-body-copy"><?= Html::encode($project['materials_finishes'] ?? '') ?></p>
            </div>
        </div>

        <?php if (!empty($project['images'])): ?>
            <div class="nj-drawer-gallery" style="margin-top: 3rem;">
                <?php foreach ($project['images'] as $img): ?>
                    <figure>
                        <img src="<?= Html::encode($img['image_path']) ?>" alt="<?= Html::encode($img['caption'] ?? $project['title']) ?>" loading="lazy">
                        <?php if (!empty($img['caption'])): ?>
                            <figcaption><?= Html::encode($img['caption']) ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
