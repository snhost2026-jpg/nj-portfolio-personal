<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Asset bundle for the luxury single-page NJ Interiors portfolio.
 */
class PortfolioAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&family=Manrope:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap',
        'css/portfolio.css',
    ];

    public $js = [
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
        'https://unpkg.com/lenis@1.1.18/dist/lenis.min.js',
        'https://cdn.jsdelivr.net/npm/three@0.169.0/build/three.min.js',
        'js/portfolio.js',
    ];

    public $jsOptions = [
        'position' => View::POS_END,
        'defer' => true,
    ];
}
