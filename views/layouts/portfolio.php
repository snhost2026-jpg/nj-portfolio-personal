<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\PortfolioAsset;
use yii\helpers\Html;
use yii\helpers\Url;

PortfolioAsset::register($this);

$settings = $this->params['settings'] ?? [];
$seoTitle = $this->title ?: ($settings['seo_title'] ?? 'Nitesh Jangda — Creative Director | NJ Interiors');
$seoDesc = $settings['seo_description'] ?? 'A Legacy of Craftsmanship. A Vision for Modern Living. Luxury residential and commercial interior design by Nitesh Jangda.';
$seoKeywords = $settings['seo_keywords'] ?? 'Nitesh Jangda, NJ Interiors, Interior Design';
$ogImage = Url::to('/media/projects/hero-living.webp', true);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($seoTitle) ?></title>
    <meta name="description" content="<?= Html::encode($seoDesc) ?>">
    <meta name="keywords" content="<?= Html::encode($seoKeywords) ?>">
    <meta name="author" content="Nitesh Jangda — NJ Interiors">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= Html::encode($seoTitle) ?>">
    <meta property="og:description" content="<?= Html::encode($seoDesc) ?>">
    <meta property="og:image" content="<?= Html::encode($ogImage) ?>">
    <meta property="og:url" content="<?= Html::encode(Url::canonical()) ?>">

    <!-- Structured Data -->
    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Person',
                'name' => 'Nitesh Jangda',
                'jobTitle' => 'Creative Director',
                'worksFor' => [
                    '@type' => 'Organization',
                    'name' => 'NJ Interiors',
                ],
                'description' => $seoDesc,
                'image' => Url::to('/media/nitesh-jangda.webp', true),
            ],
            [
                '@type' => 'InteriorDesignBusiness',
                'name' => 'NJ Interiors',
                'founder' => 'Nitesh Jangda',
                'foundingDate' => '2016',
                'description' => $seoDesc,
                'email' => $settings['contact_email'] ?? 'studio@njinteriors.in',
                'telephone' => $settings['contact_phone'] ?? '',
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>

    <?php $this->head() ?>
</head>
<body class="nj-body">
<?php $this->beginBody() ?>

<!-- Subtle Architectural Film Grain Overlay -->
<div class="nj-grain" aria-hidden="true"></div>

<!-- Top Scroll Progress Indicator -->
<div class="nj-scroll-progress-track" aria-hidden="true">
    <div id="nj-scroll-progress-bar" class="nj-scroll-progress-bar"></div>
</div>

<!-- Custom Architectural Preloader -->
<div class="nj-preloader" id="nj-preloader" aria-hidden="true">
    <div class="nj-preloader-curtain nj-preloader-curtain--top"></div>
    <div class="nj-preloader-curtain nj-preloader-curtain--bottom"></div>
    <div class="nj-preloader-inner">
        <svg class="nj-preloader-blueprint" viewBox="0 0 120 120" fill="none" aria-hidden="true">
            <rect x="10" y="10" width="100" height="100" stroke="#9A8062" stroke-width="0.75" stroke-dasharray="400" stroke-dashoffset="400" class="nj-bp-line"/>
            <line x1="10" y1="60" x2="110" y2="60" stroke="#D9CCBA" stroke-width="0.5" stroke-opacity="0.4" stroke-dasharray="100" stroke-dashoffset="100" class="nj-bp-line"/>
            <line x1="60" y1="10" x2="60" y2="110" stroke="#D9CCBA" stroke-width="0.5" stroke-opacity="0.4" stroke-dasharray="100" stroke-dashoffset="100" class="nj-bp-line"/>
            <circle cx="60" cy="60" r="32" stroke="#9A8062" stroke-width="0.75" stroke-dasharray="210" stroke-dashoffset="210" class="nj-bp-line"/>
        </svg>
        <div class="nj-preloader-monogram">NJ</div>
        <span class="nj-preloader-brand">NJ INTERIORS &middot; ATELIER</span>
        <p class="nj-preloader-quote">A Legacy of Craftsmanship. A Vision for Modern Living.</p>
        <div class="nj-preloader-line"><span class="nj-preloader-progress" id="nj-preloader-progress"></span></div>
        <div class="nj-preloader-footer">
            <span>INITIALIZING STUDIO</span>
            <span class="nj-preloader-count" id="nj-preloader-count">00%</span>
        </div>
    </div>
</div>

<!-- Custom Dual Architectural Cursor -->
<div class="nj-cursor-dot" id="nj-cursor-dot" aria-hidden="true"></div>
<div class="nj-cursor-ring" id="nj-cursor" aria-hidden="true">
    <span class="nj-cursor-label" id="nj-cursor-label"></span>
</div>

<!-- Floating Architectural Navigation -->
<header class="nj-header" id="nj-header">
    <div class="nj-header-inner">
        <a href="#home" class="nj-logo" data-scroll-to="#home" aria-label="NJ Interiors Home">
            <span class="nj-logo-mark">NJ</span>
            <span class="nj-logo-text">INTERIORS</span>
        </a>

        <nav class="nj-nav" aria-label="Primary Navigation">
            <ul class="nj-nav-list">
                <li><a href="#home" class="nj-nav-link is-active" data-scroll-to="#home">Home</a></li>
                <li><a href="#legacy" class="nj-nav-link" data-scroll-to="#legacy">Legacy</a></li>
                <li><a href="#about" class="nj-nav-link" data-scroll-to="#about">About</a></li>
                <li><a href="#philosophy" class="nj-nav-link" data-scroll-to="#philosophy">Philosophy</a></li>
                <li><a href="#projects" class="nj-nav-link" data-scroll-to="#projects">Projects</a></li>
                <li><a href="#contact" class="nj-nav-link" data-scroll-to="#contact">Contact</a></li>
            </ul>
            <span class="nj-nav-indicator" id="nj-nav-indicator" aria-hidden="true"></span>
        </nav>

        <div class="nj-header-actions">
            <a href="#contact" class="nj-btn-pill magnetic" data-scroll-to="#contact" data-cursor="INQUIRE">
                <span>Let's Talk</span>
                <span class="nj-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
            <button type="button" class="nj-burger" id="nj-burger" aria-label="Open navigation menu" aria-expanded="false" aria-controls="nj-mobile-menu">
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Fullscreen Menu -->
<div class="nj-mobile-menu" id="nj-mobile-menu" aria-hidden="true">
    <div class="nj-mobile-menu-inner">
        <span class="nj-micro-label">NAVIGATION</span>
        <ul class="nj-mobile-links">
            <li><a href="#home" data-scroll-to="#home">01 / Home</a></li>
            <li><a href="#legacy" data-scroll-to="#legacy">02 / Legacy</a></li>
            <li><a href="#about" data-scroll-to="#about">03 / About</a></li>
            <li><a href="#philosophy" data-scroll-to="#philosophy">04 / Philosophy</a></li>
            <li><a href="#projects" data-scroll-to="#projects">05 / Projects</a></li>
            <li><a href="#contact" data-scroll-to="#contact">06 / Contact</a></li>
        </ul>
        <div class="nj-mobile-meta">
            <p>Nitesh Jangda — Creative Director</p>
            <p><?= Html::encode($settings['contact_email'] ?? 'studio@njinteriors.in') ?></p>
        </div>
    </div>
</div>

<main id="nj-main">
    <?= $content ?>
</main>

<!-- Discipline Card Popup Modal -->
<div class="nj-modal" id="nj-discipline-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="nj-modal-title">
    <div class="nj-modal-backdrop" id="nj-modal-backdrop"></div>
    <div class="nj-modal-dialog">
        <button type="button" class="nj-modal-close magnetic" id="nj-modal-close" aria-label="Close popup">&times;</button>
        <div class="nj-modal-media">
            <img id="nj-modal-img" src="" alt="">
        </div>
        <div class="nj-modal-body">
            <span class="nj-index-tag" id="nj-modal-tag">DISCIPLINE</span>
            <h3 class="nj-h3" id="nj-modal-title"></h3>
            <p class="nj-body-copy" id="nj-modal-desc"></p>
        </div>
    </div>
</div>

<!-- Cinematic Project Detail Drawer (Single-Page AJAX Experience) -->
<aside class="nj-drawer" id="nj-project-drawer" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="drawer-project-title">
    <div class="nj-drawer-backdrop" id="nj-drawer-backdrop"></div>
    <div class="nj-drawer-panel" data-lenis-prevent>
        <button type="button" class="nj-drawer-close magnetic" id="nj-drawer-close" aria-label="Close project details">
            <span>CLOSE</span>
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="nj-drawer-content" id="nj-drawer-content">
            <!-- Populated dynamically via AJAX -->
        </div>
    </div>
</aside>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
