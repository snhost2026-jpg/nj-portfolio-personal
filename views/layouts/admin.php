<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\BootstrapAsset;
use yii\helpers\Html;
use yii\helpers\Url;

BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title ? $this->title . ' — NJ Interiors Admin' : 'NJ Interiors Admin') ?></title>
    <?php $this->head() ?>
    <style>
        :root {
            --nj-ivory: #F5F1E9;
            --nj-charcoal: #252522;
            --nj-bronze: #9A8062;
            --nj-brown: #49392F;
        }
        body { background-color: var(--nj-ivory); color: var(--nj-charcoal); font-family: system-ui, -apple-system, sans-serif; }
        .admin-nav { background-color: var(--nj-charcoal); border-bottom: 1px solid rgba(154,128,98,0.25); }
        .admin-nav .navbar-brand { color: var(--nj-ivory); letter-spacing: 0.14em; font-weight: 600; font-size: 0.95rem; }
        .admin-nav .nav-link { color: #D9CCBA; font-size: 0.88rem; letter-spacing: 0.04em; }
        .admin-nav .nav-link:hover, .admin-nav .nav-link.active { color: #fff; }
        .card { border: 1px solid rgba(37,37,34,0.08); border-radius: 6px; box-shadow: 0 4px 20px rgba(37,37,34,0.04); }
        .btn-nj { background-color: var(--nj-charcoal); color: var(--nj-ivory); border: 1px solid var(--nj-charcoal); }
        .btn-nj:hover { background-color: var(--nj-brown); color: #fff; }
        .btn-bronze { background-color: var(--nj-bronze); color: #fff; border: none; }
        .btn-bronze:hover { background-color: #826a4e; color: #fff; }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<nav class="navbar navbar-expand-lg admin-nav py-3">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::to(['/admin/index']) ?>">NJ INTERIORS // CMS</a>
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/index']) ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/projects']) ?>">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/categories']) ?>">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/timeline']) ?>">Legacy Timeline</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/settings']) ?>">Site &amp; SEO</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= Url::to(['/admin/inquiries']) ?>">Inquiries</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= Url::to(['/site/index']) ?>" target="_blank" class="btn btn-sm btn-outline-light">View Live Site</a>
                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) ?>
                <?= Html::submitButton('Logout', ['class' => 'btn btn-sm btn-bronze']) ?>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</nav>

<main class="container py-5">
    <?= Alert::widget() ?>
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
