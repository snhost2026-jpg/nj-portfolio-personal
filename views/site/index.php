<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $settings */
/** @var array $timeline */
/** @var array $categories */
/** @var array $projects */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['settings'] = $settings;
$this->title = $settings['seo_title'] ?? 'Nitesh Jangda — Architectural & Spatial Designer | Personal Portfolio';
?>

<!-- =========================================================
     SECTION 1 — CINEMATIC HERO
     Spacious, unobstructed architectural typography + 3D WebGL
     ========================================================= -->
<section id="home" class="nj-hero" data-section="home">
    <div class="nj-hero-bg">
        <img
            src="<?= Html::encode(Url::to('@web/media/projects/hero-living.webp')) ?>"
            alt="Luxury contemporary living room designed by Nitesh Jangda"
            class="nj-hero-bg-img"
            width="1920"
            height="1080"
            fetchpriority="high"
        >
        <div class="nj-hero-scrim"></div>
        <canvas id="nj-hero-canvas" class="nj-hero-canvas" aria-hidden="true"></canvas>
    </div>

    <div class="nj-hero-inner">
        <div class="nj-hero-top-meta">
            <span class="nj-micro-tag">SPATIAL DESIGNER &nbsp;|&nbsp; ARCHITECTURAL INNOVATOR</span>
            <span class="nj-micro-tag">FOUR GENERATIONS OF CRAFT &nbsp;//&nbsp; PERSONAL ARCHIVE</span>
        </div>

        <div class="nj-hero-stage">
            <div class="nj-hero-copy">
                <div class="nj-hero-kicker">
                    <span class="nj-kicker-line"></span>
                    <span>ARCHITECTURE &middot; INTERIORS &middot; BESPOKE LIVING</span>
                </div>

                <h1 class="nj-hero-display" aria-label="Nitesh Jangda">
                    <span class="nj-clip-line"><span class="nj-clip-text">NITESH</span></span>
                    <span class="nj-clip-line nj-clip-line--indent"><span class="nj-clip-text">JANGDA</span></span>
                </h1>
            </div>

            <div class="nj-hero-portrait-wrap nj-tilt-3d">
                <div class="nj-hero-portrait-frame">
                    <img
                        src="<?= Html::encode(Url::to('@web/media/nitesh-jangda.webp')) ?>"
                        alt="Nitesh Jangda, Architectural & Spatial Designer"
                        class="nj-hero-portrait"
                        width="960"
                        height="1280"
                    >
                </div>
                <span class="nj-portrait-caption">NITESH JANGDA — SPATIAL DESIGNER</span>
            </div>
        </div>

        <div class="nj-hero-bottom">
            <div class="nj-hero-tagline">
                <p class="nj-tagline-main">
                    A Legacy of Craftsmanship.<br>
                    <em>A Vision for Modern Living.</em>
                </p>
                <p class="nj-tagline-sub">
                    Transforming spaces into timeless expressions of personality, functionality and craftsmanship.
                </p>
            </div>

            <a href="#legacy" class="nj-scroll-indicator magnetic" data-scroll-to="#legacy" data-cursor="EXPLORE" aria-label="Scroll to The Legacy">
                <span class="nj-scroll-text">SCROLL TO EXPLORE</span>
                <span class="nj-scroll-line"><span></span></span>
            </a>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 2 — THE LEGACY
     Four Generations. One Passion for Craftsmanship.
     ========================================================= -->
<section id="legacy" class="nj-section nj-legacy" data-section="legacy">
    <div class="nj-container">
        <div class="nj-section-header">
            <span class="nj-index-tag">01 // THE HERITAGE</span>
            <h2 class="nj-h2">
                FOUR GENERATIONS.<br>
                <span class="nj-serif-italic">One Passion for Craftsmanship.</span>
            </h2>
            <p class="nj-lead">
                For Nitesh Jangda, spatial design is more than a profession — it is a passion deeply rooted in a four-generation legacy of craftsmanship, engineering and construction.
            </p>
        </div>

        <div class="nj-timeline-shell">
            <div class="nj-timeline-track" aria-hidden="true">
                <div class="nj-timeline-progress" id="nj-timeline-progress"></div>
            </div>

            <div class="nj-timeline-grid">
                <?php foreach ($timeline as $idx => $entry): ?>
                    <article class="nj-timeline-item" data-timeline-index="<?= (int)$idx ?>">
                        <div class="nj-timeline-media">
                            <div class="nj-img-reveal nj-tilt-3d" data-clip-reveal>
                                <img
                                    src="<?= Html::encode(Url::to('@web/' . ltrim($entry['image_path'] ?: 'media/legacy/heritage-workshop.webp', '/'))) ?>"
                                    alt="<?= Html::encode($entry['title']) ?>"
                                    decoding="async"
                                    width="800"
                                    height="600"
                                >
                            </div>
                            <span class="nj-timeline-node" aria-hidden="true">0<?= $idx + 1 ?></span>
                        </div>
                        <div class="nj-timeline-body">
                            <span class="nj-micro-tag"><?= Html::encode($entry['era_label']) ?></span>
                            <h3 class="nj-h3"><?= Html::encode($entry['title']) ?></h3>
                            <?php if (!empty($entry['subtitle'])): ?>
                                <p class="nj-timeline-sub"><?= Html::encode($entry['subtitle']) ?></p>
                            <?php endif; ?>
                            <p class="nj-body-copy"><?= Html::encode($entry['description']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 3 — THE JOURNEY (ABOUT NITESH)
     Clean portrait framing + Interactive Engineering <-> Design
     ========================================================= -->
<section id="about" class="nj-section nj-journey nj-section--dark" data-section="about">
    <div class="nj-container">
        <div class="nj-journey-grid">
            <div class="nj-journey-left">
                <span class="nj-index-tag nj-index-tag--light">02 // THE JOURNEY</span>
                <div class="nj-milestone-year" aria-hidden="true">2016</div>
                <h2 class="nj-h2 nj-h2--light">
                    <?= Html::encode($settings['about_heading'] ?? 'FROM ENGINEERING TO SPATIAL DESIGN.') ?>
                </h2>
                <p class="nj-body-copy nj-body-copy--light">
                    <?= Html::encode($settings['about_body'] ?? 'An Electronics Engineering graduate, Nitesh initially began his professional journey in technical engineering. However, his passion for architecture, spatial forms, and fine craftsmanship inspired him to chart his own creative path. In 2016, he founded NJ Interiors as his studio practice, harmonizing technical precision with bespoke architectural design and his family\'s multi-generational heritage.') ?>
                </p>

                <div class="nj-director-card nj-tilt-3d">
                    <div class="nj-director-photo">
                        <img
                            src="<?= Html::encode(Url::to('@web/media/nitesh-jangda.webp')) ?>"
                            alt="Nitesh Jangda, Architectural & Spatial Designer"
                            decoding="async"
                            width="960"
                            height="1280"
                        >
                    </div>
                    <div class="nj-director-info">
                        <span class="nj-stat-kicker">SPATIAL DESIGNER &amp; FOUNDER</span>
                        <strong class="nj-director-name">Nitesh Jangda</strong>
                        <span class="nj-director-role">B.E. Electronics &middot; Founder, NJ Interiors (2016)</span>
                    </div>
                </div>
            </div>

            <div class="nj-journey-right">
                <div class="nj-compare-header">
                    <span class="nj-micro-tag">INTERACTIVE COMPARISON // DRAG OR HOVER</span>
                </div>
                <div class="nj-split-compare" id="nj-split-compare" data-cursor="COMPARE">
                    <figure class="nj-compare-layer nj-compare-layer--blueprint">
                        <img
                            src="<?= Html::encode(Url::to('@web/media/legacy/engineering-sketch.webp')) ?>"
                            alt="Architectural elevation drawings and material samples"
                            decoding="async"
                            width="1200"
                            height="900"
                        >
                        <figcaption>TECHNICAL PRECISION // DRAFTING &amp; MATERIALS</figcaption>
                    </figure>
                    <figure class="nj-compare-layer nj-compare-layer--interior" id="nj-compare-overlay">
                        <img
                            src="<?= Html::encode(Url::to('@web/media/projects/hero-living.webp')) ?>"
                            alt="Completed luxury residential living room"
                            decoding="async"
                            width="1200"
                            height="900"
                        >
                        <figcaption>BUILT REALITY // CONTEMPORARY LIVING</figcaption>
                    </figure>
                    <div class="nj-compare-divider" id="nj-compare-divider" aria-hidden="true">
                        <span>ENGINEERING &harr; DESIGN</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 4 — CREATIVE PHILOSOPHY
     3D Perspective Fan Deck + 3 Core Pillars
     ========================================================= -->
<section id="philosophy" class="nj-section nj-philosophy" data-section="philosophy">
    <div class="nj-container">
        <div class="nj-philosophy-header">
            <div>
                <span class="nj-index-tag">03 // CREATIVE PHILOSOPHY</span>
                <h2 class="nj-h2">
                    DESIGNING SPACES.<br>
                    <span class="nj-serif-italic">Creating Experiences.</span>
                </h2>
            </div>
            <p class="nj-lead">
                Today, with over a decade of hands-on practice, Nitesh creates architectural spaces with a design philosophy that seamlessly blends contemporary aesthetics, practical functionality and exceptional craftsmanship.
            </p>
        </div>

        <!-- 3D Perspective Fan Deck // Disciplines (inspired by njinteriors.co.in) -->
        <div class="nj-fandeck-stage-wrap" id="fanDeckStageWrap">
            <div class="nj-fandeck-header">
                <span class="nj-fandeck-badge">DESIGN DISCIPLINES // PERSONAL ARCHIVE</span>
                <div class="nj-fandeck-tabs" role="tablist" aria-label="Design Disciplines">
                    <button type="button" class="nj-fandeck-tab" data-index="0" role="tab">01 Spatial</button>
                    <button type="button" class="nj-fandeck-tab" data-index="1" role="tab">02 Materials</button>
                    <button type="button" class="nj-fandeck-tab is-active" data-index="2" role="tab">03 Bespoke Living</button>
                    <button type="button" class="nj-fandeck-tab" data-index="3" role="tab">04 Joinery</button>
                    <button type="button" class="nj-fandeck-tab" data-index="4" role="tab">05 Commercial</button>
                </div>
            </div>

            <div class="nj-fandeck-3d-viewport nj-fandeck-stage" id="nj-fandeck" aria-label="Design Disciplines 3D Arc Deck">
                <article class="nj-fandeck-card nj-fan-card" data-fan="0" data-fan-index="0">
                    <div class="nj-fandeck-card-media">
                        <img src="<?= Html::encode(Url::to('@web/media/projects/hero-living.webp')) ?>" alt="Spatial Architecture" decoding="async">
                        <span class="nj-fandeck-card-num">01</span>
                    </div>
                    <div class="nj-fandeck-card-body">
                        <div>
                            <span class="nj-micro-tag">DISCIPLINE 01</span>
                            <h4>SPATIAL ARCHITECTURE</h4>
                            <p>Holistic residential and spatial planning grounded in proportion, natural daylight, and effortless circulation.</p>
                        </div>
                        <span class="nj-fandeck-explore-btn">Explore Discipline &rarr;</span>
                    </div>
                </article>

                <article class="nj-fandeck-card nj-fan-card" data-fan="1" data-fan-index="1">
                    <div class="nj-fandeck-card-media">
                        <img src="<?= Html::encode(Url::to('@web/media/projects/project-kitchen.webp')) ?>" alt="Material Curation" decoding="async">
                        <span class="nj-fandeck-card-num">02</span>
                    </div>
                    <div class="nj-fandeck-card-body">
                        <div>
                            <span class="nj-micro-tag">DISCIPLINE 02</span>
                            <h4>MATERIAL CURATION</h4>
                            <p>Curated natural stone, travertine, smoked timber, and brushed bronze finishes selected for longevity and warmth.</p>
                        </div>
                        <span class="nj-fandeck-explore-btn">Explore Discipline &rarr;</span>
                    </div>
                </article>

                <article class="nj-fandeck-card nj-fan-card is-active is-popped is-center" data-fan="2" data-fan-index="2">
                    <div class="nj-fandeck-card-media">
                        <img src="<?= Html::encode(Url::to('@web/media/projects/project-bedroom.webp')) ?>" alt="Bespoke Living" decoding="async">
                        <span class="nj-fandeck-card-num">03</span>
                    </div>
                    <div class="nj-fandeck-card-body">
                        <div>
                            <span class="nj-micro-tag">DISCIPLINE 03</span>
                            <h4>BESPOKE LIVING</h4>
                            <p>Serene private sanctuaries engineered around acoustic calm, indirect lighting, and bespoke upholstery.</p>
                        </div>
                        <span class="nj-fandeck-explore-btn">Explore Discipline &rarr;</span>
                    </div>
                </article>

                <article class="nj-fandeck-card nj-fan-card" data-fan="3" data-fan-index="3">
                    <div class="nj-fandeck-card-media">
                        <img src="<?= Html::encode(Url::to('@web/media/projects/project-credenza.webp')) ?>" alt="Custom Furniture & Joinery" decoding="async">
                        <span class="nj-fandeck-card-num">04</span>
                    </div>
                    <div class="nj-fandeck-card-body">
                        <div>
                            <span class="nj-micro-tag">DISCIPLINE 04</span>
                            <h4>CUSTOM FURNITURE &amp; JOINERY</h4>
                            <p>Modular kitchens, wardrobes, TV units, storage systems, beds, counters and bespoke cabinetry.</p>
                        </div>
                        <span class="nj-fandeck-explore-btn">Explore Discipline &rarr;</span>
                    </div>
                </article>

                <article class="nj-fandeck-card nj-fan-card" data-fan="4" data-fan-index="4">
                    <div class="nj-fandeck-card-media">
                        <img src="<?= Html::encode(Url::to('@web/media/projects/project-commercial.webp')) ?>" alt="Commercial Identity" decoding="async">
                        <span class="nj-fandeck-card-num">05</span>
                    </div>
                    <div class="nj-fandeck-card-body">
                        <div>
                            <span class="nj-micro-tag">DISCIPLINE 05</span>
                            <h4>COMMERCIAL IDENTITY</h4>
                            <p>Distinctive commercial lounges, executive suites, and brand-defining architectural interiors.</p>
                        </div>
                        <span class="nj-fandeck-explore-btn">Explore Discipline &rarr;</span>
                    </div>
                </article>
            </div>
        </div>

        <!-- Three Pillars -->
        <div class="nj-pillars">
            <article class="nj-pillar">
                <div class="nj-pillar-num">01</div>
                <div class="nj-pillar-content">
                    <h3 class="nj-h3">PERSONALITY</h3>
                    <p class="nj-body-copy">Every space should reflect the people who inhabit it.</p>
                </div>
                <div class="nj-pillar-thumb nj-tilt-3d">
                    <img src="<?= Html::encode(Url::to('@web/media/projects/project-bedroom.webp')) ?>" alt="Personality in bespoke interiors" decoding="async">
                </div>
            </article>

            <article class="nj-pillar">
                <div class="nj-pillar-num">02</div>
                <div class="nj-pillar-content">
                    <h3 class="nj-h3">FUNCTIONALITY</h3>
                    <p class="nj-body-copy">Beauty must work in harmony with everyday living.</p>
                </div>
                <div class="nj-pillar-thumb nj-tilt-3d">
                    <img src="<?= Html::encode(Url::to('@web/media/projects/project-kitchen.webp')) ?>" alt="Functional luxury kitchen" decoding="async">
                </div>
            </article>

            <article class="nj-pillar">
                <div class="nj-pillar-num">03</div>
                <div class="nj-pillar-content">
                    <h3 class="nj-h3">CRAFTSMANSHIP</h3>
                    <p class="nj-body-copy">Every detail matters. Quality is reflected in the smallest elements.</p>
                </div>
                <div class="nj-pillar-thumb nj-tilt-3d">
                    <img src="<?= Html::encode(Url::to('@web/media/legacy/metalwork-precision.webp')) ?>" alt="Handcrafted woodwork and precision metalwork" decoding="async">
                </div>
            </article>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 5 — THE WORK / PORTFOLIO
     ========================================================= -->
<section id="projects" class="nj-section nj-portfolio" data-section="projects">
    <div class="nj-container">
        <div class="nj-portfolio-head">
            <div>
                <span class="nj-index-tag">04 // SELECTED WORKS</span>
                <h2 class="nj-h2">SPACES THAT TELL STORIES.</h2>
            </div>
            <p class="nj-sample-notice">
                Curated architectural studies, bespoke joinery, and spatial concepts designed by Nitesh Jangda.
            </p>
        </div>

        <div class="nj-filter-bar" role="tablist" aria-label="Project Categories">
            <button type="button" class="nj-filter-btn is-active" data-filter="all" role="tab" aria-selected="true">All Works</button>
            <?php foreach ($categories as $cat): ?>
                <button type="button" class="nj-filter-btn" data-filter="<?= Html::encode($cat['slug']) ?>" role="tab" aria-selected="false">
                    <?= Html::encode($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="nj-masonry" id="nj-portfolio-grid">
            <?php foreach ($projects as $project): ?>
                <?php
                $catSlug = $project['category']['slug'] ?? 'residential-interiors';
                $catName = $project['category']['name'] ?? 'Interior Design';
                $ratio = $project['aspect_ratio'] ?? 'landscape';
                ?>
                <article
                    class="nj-project-card nj-project-card--<?= Html::encode($ratio) ?> nj-tilt-3d"
                    data-category="<?= Html::encode($catSlug) ?>"
                    data-project-slug="<?= Html::encode($project['slug']) ?>"
                    data-cursor="VIEW PROJECT"
                    tabindex="0"
                    role="button"
                    aria-label="Open project <?= Html::encode($project['title']) ?>"
                >
                    <div class="nj-project-media">
                        <img
                            src="<?= Html::encode(Url::to('@web/' . ltrim($project['cover_image'] ?: 'media/projects/hero-living.webp', '/'))) ?>"
                            alt="<?= Html::encode($project['title']) ?>"
                            decoding="async"
                            width="1200"
                            height="800"
                        >
                        <?php if (!empty($project['is_sample'])): ?>
                            <span class="nj-sample-badge">SAMPLE STUDY</span>
                        <?php endif; ?>
                    </div>
                    <div class="nj-project-meta">
                        <div class="nj-project-meta-top">
                            <span class="nj-micro-tag"><?= Html::encode($catName) ?></span>
                            <span class="nj-micro-tag"><?= Html::encode($project['year'] ?? '2025') ?></span>
                        </div>
                        <h3 class="nj-project-title"><?= Html::encode($project['title']) ?></h3>
                        <p class="nj-project-loc"><?= Html::encode($project['location'] ?? '') ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 6 — THE CREATIVE PROCESS
     ========================================================= -->
<section id="process" class="nj-section nj-process nj-section--beige" data-section="process">
    <div class="nj-container">
        <div class="nj-process-layout">
            <div class="nj-process-sticky">
                <span class="nj-index-tag">05 // THE METHODOLOGY</span>
                <h2 class="nj-h2">
                    FROM VISION<br>
                    <span class="nj-serif-italic">to Reality.</span>
                </h2>
                <p class="nj-body-copy">
                    A disciplined five-stage architectural workflow where engineering precision meets bespoke craftsmanship.
                </p>
                <div class="nj-process-visual nj-tilt-3d" data-clip-reveal>
                    <img src="<?= Html::encode(Url::to('@web/media/legacy/engineering-sketch.webp')) ?>" alt="Architectural design methodology" decoding="async">
                </div>
            </div>

            <div class="nj-process-steps">
                <?php
                $steps = [
                    ['01', 'DISCOVER', 'Understanding the client\'s vision, lifestyle and aspirations.'],
                    ['02', 'CONCEPTUALISE', 'Translating ideas into cohesive spatial narratives and design concepts.'],
                    ['03', 'DESIGN', 'Developing architectural layouts, material palettes, finishes and millimeter-level details.'],
                    ['04', 'EXECUTE', 'Bringing the design to life through meticulous site supervision and master craftsmanship.'],
                    ['05', 'DELIVER', 'Creating spaces that are meaningful, functional and timeless.'],
                ];
                foreach ($steps as [$num, $title, $desc]):
                ?>
                    <div class="nj-step-item">
                        <span class="nj-step-num"><?= $num ?></span>
                        <div class="nj-step-body">
                            <h3 class="nj-h3"><?= $title ?></h3>
                            <p class="nj-body-copy"><?= $desc ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 7 — CREATIVE DIRECTION & STUDIO FILM
     Dedicated 3D-tilted architectural cinema showcase for nj-showcase.webm
     ========================================================= -->
<section id="direction" class="nj-section nj-direction nj-section--dark" data-section="direction">
    <div class="nj-container">
        <div class="nj-direction-grid">
            <div class="nj-direction-copy">
                <span class="nj-index-tag nj-index-tag--light">06 // CREATIVE DIRECTION</span>
                <h2 class="nj-h2-oversized">
                    CRAFTING LUXURY<br>
                    <em>WITH CARE.</em>
                </h2>
                <p class="nj-lead nj-lead--light">
                    Through his personal creative vision, Nitesh transforms residential and commercial environments through innovative spatial concepts, meticulous millimeter-level execution, and an unwavering commitment to quality.
                </p>

                <ul class="nj-discipline-strip">
                    <li>Spatial Vision</li>
                    <li>Technical Understanding</li>
                    <li>Engineering Precision</li>
                    <li>Material Knowledge</li>
                    <li>Exceptional Craftsmanship</li>
                </ul>
            </div>

            <div class="nj-direction-cinema">
                <div class="nj-cinema-frame" id="nj-cinema-frame" data-cursor="STUDIO FILM">
                    <video
                        class="nj-cinema-video"
                        src="<?= Html::encode(Url::to('@web/media/nj-showcase.webm')) ?>"
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                    ></video>
                    <div class="nj-cinema-caption">
                        <span class="nj-reel-dot"></span>
                        <span>NITESH JANGDA // DESIGN WALKTHROUGH</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 8 — PERSONAL VISION QUOTE
     ========================================================= -->
<section id="vision" class="nj-section nj-vision" data-section="vision">
    <div class="nj-vision-bg" id="visionZoomWrap">
        <img
            id="visionZoomImg"
            src="<?= Html::encode(Url::to('@web/media/projects/project-commercial.webp')) ?>"
            alt="Architectural gallery lounge designed by Nitesh Jangda"
            decoding="async"
            width="1600"
            height="1067"
        >
        <div class="nj-vision-scrim"></div>
    </div>

    <div class="nj-container nj-vision-inner">
        <span class="nj-index-tag nj-index-tag--light">07 // PERSONAL VISION</span>
        <blockquote class="nj-vision-quote">
            &ldquo;<?= Html::encode($settings['vision_quote'] ?? 'To create spaces that go beyond beauty — spaces that tell stories, inspire living and stand the test of time.') ?>&rdquo;
        </blockquote>
        <cite class="nj-vision-cite">— NITESH JANGDA</cite>
    </div>
</section>

<!-- =========================================================
     SECTION 9 — CONTACT & CLOSING FOOTER
     ========================================================= -->
<section id="contact" class="nj-section nj-contact" data-section="contact">
    <div class="nj-container">
        <div class="nj-contact-grid">
            <div class="nj-contact-left">
                <span class="nj-index-tag">08 // BEGIN A CONVERSATION</span>
                <h2 class="nj-h2">
                    LET&rsquo;S CREATE<br>
                    <span class="nj-serif-italic">Something Timeless.</span>
                </h2>
                <p class="nj-lead">
                    Have a space, collaboration, or architectural vision in mind? Connect directly with Nitesh.
                </p>

                <div class="nj-contact-details">
                    <div class="nj-detail-block">
                        <span class="nj-micro-tag">DIRECT EMAIL</span>
                        <a href="mailto:<?= Html::encode($settings['contact_email'] ?? 'studio@njinteriors.in') ?>">
                            <?= Html::encode($settings['contact_email'] ?? 'studio@njinteriors.in') ?>
                        </a>
                    </div>
                    <div class="nj-detail-block">
                        <span class="nj-micro-tag">DIRECT LINE</span>
                        <a href="tel:<?= Html::encode(preg_replace('/\s+/', '', $settings['contact_phone'] ?? '')) ?>">
                            <?= Html::encode($settings['contact_phone'] ?? '+91 98000 00000') ?>
                        </a>
                    </div>
                    <div class="nj-detail-block">
                        <span class="nj-micro-tag">STUDIO BASE</span>
                        <span><?= Html::encode($settings['contact_address'] ?? 'Bhubaneswar & Mumbai, India') ?></span>
                    </div>
                </div>

                <div class="nj-contact-photo nj-tilt-3d" data-clip-reveal>
                    <img src="<?= Html::encode(Url::to('@web/media/legacy/nj-studio-2016.webp')) ?>" alt="Nitesh Jangda design studio consultation desk" decoding="async">
                </div>
            </div>

            <div class="nj-contact-right">
                <form id="nj-contact-form" class="nj-form" action="<?= Url::to(['/site/submit-contact']) ?>" method="post" novalidate>
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">

                    <div class="nj-form-row">
                        <div class="nj-field">
                            <label for="contact-name">Full Name *</label>
                            <input type="text" id="contact-name" name="name" required placeholder="Your name">
                        </div>
                        <div class="nj-field">
                            <label for="contact-email">Email Address *</label>
                            <input type="email" id="contact-email" name="email" required placeholder="you@example.com">
                        </div>
                    </div>

                    <div class="nj-form-row">
                        <div class="nj-field">
                            <label for="contact-phone">Phone Number</label>
                            <input type="tel" id="contact-phone" name="phone" placeholder="+91 98000 00000">
                        </div>
                        <div class="nj-field">
                            <label for="contact-type">Project Type</label>
                            <select id="contact-type" name="project_type">
                                <option value="Residential Interior">Residential Interior</option>
                                <option value="Luxury Living Space">Luxury Living Space</option>
                                <option value="Commercial Interior">Commercial Interior</option>
                                <option value="Kitchen & Bespoke Joinery">Kitchen &amp; Bespoke Joinery</option>
                                <option value="Turnkey Architecture & Design">Turnkey Architecture &amp; Design</option>
                            </select>
                        </div>
                    </div>

                    <div class="nj-field">
                        <label for="contact-message">Project Brief *</label>
                        <textarea id="contact-message" name="message" rows="5" required placeholder="Tell us about your space, location, timeline, and aspirations..."></textarea>
                    </div>

                    <div class="nj-form-footer">
                        <button type="submit" class="nj-btn-submit magnetic" id="nj-contact-submit" data-cursor="SEND">
                            <span>Send Inquiry</span>
                            <span class="nj-btn-arrow" aria-hidden="true">&rarr;</span>
                        </button>
                        <div class="nj-form-status" id="nj-form-status" role="status" aria-live="polite"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="nj-footer">
        <div class="nj-container nj-footer-inner">
            <div class="nj-footer-brand">
                <div class="nj-footer-logo-row">
                    <img src="<?= Html::encode(Url::to('@web/media/logo.webp')) ?>" alt="Nitesh Jangda Logo" class="nj-footer-logo-img" width="44" height="44">
                    <span class="nj-footer-logo">NITESH JANGDA</span>
                </div>
                <span class="nj-footer-tag">ARCHITECTURAL &amp; SPATIAL DESIGN ARCHIVE.</span>
            </div>

            <div class="nj-footer-links">
                <?php if (!empty($settings['social_instagram'])): ?>
                    <a href="<?= Html::encode($settings['social_instagram']) ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
                <?php endif; ?>
                <?php if (!empty($settings['social_linkedin'])): ?>
                    <a href="<?= Html::encode($settings['social_linkedin']) ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                <?php endif; ?>
                <?php if (!empty($settings['social_behance'])): ?>
                    <a href="<?= Html::encode($settings['social_behance']) ?>" target="_blank" rel="noopener noreferrer">Behance</a>
                <?php endif; ?>
                <?php if (!empty($settings['social_youtube'])): ?>
                    <a href="<?= Html::encode($settings['social_youtube']) ?>" target="_blank" rel="noopener noreferrer">YouTube</a>
                <?php endif; ?>
                <a href="<?= Url::to(['/admin/index']) ?>">Studio CMS</a>
            </div>

            <div class="nj-footer-copy">
                &copy; <?= date('Y') ?> Nitesh Jangda. All rights reserved.
            </div>
        </div>
    </footer>
</section>
