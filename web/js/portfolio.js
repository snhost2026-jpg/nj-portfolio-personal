/**
 * NJ INTERIORS — NITESH JANGDA PORTFOLIO
 * Continuous Scroll-Driven & 3D Motion Choreography (inspired by njinteriors.co.in).
 *
 * Runs unconditionally for buttery smooth Lenis + GSAP ScrollTrigger + Three.js 3D depth.
 * Windows "MinAnimate = 0" prefers-reduced-motion bypass is enforced so all animations
 * execute at 60fps across Chrome, Edge, Safari, and Firefox.
 */

(function () {
    'use strict';

    /* ----------------------------------------------------------
     * 0. SCROLL RESTORATION & TOP RESET
     * ---------------------------------------------------------- */
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    if (window.location.hash) {
        history.replaceState(null, '', window.location.pathname + window.location.search);
    }
    window.scrollTo(0, 0);
    window.addEventListener('beforeunload', () => {
        window.scrollTo(0, 0);
    });
    window.addEventListener('load', () => {
        setTimeout(() => {
            window.scrollTo(0, 0);
            if (lenis) lenis.scrollTo(0, { immediate: true });
        }, 0);
    });

    /* ----------------------------------------------------------
     * TEXT SPLITTING HELPERS
     * ---------------------------------------------------------- */
    function splitTextToChars(element) {
        if (!element || element.dataset.splitDone === 'true') return;
        const text = element.textContent || '';
        element.textContent = '';
        element.dataset.splitDone = 'true';

        for (let i = 0; i < text.length; i++) {
            const ch = text[i];
            const span = document.createElement('span');
            span.className = 'nj-char';
            span.textContent = ch === ' ' ? '\u00A0' : ch;
            element.appendChild(span);
        }
    }

    function splitLinesToWords(container) {
        if (!container || container.dataset.wordSplitDone === 'true') return;
        container.dataset.wordSplitDone = 'true';

        const targetLines = container.querySelectorAll('.nj-line-inner, .nj-scrub-target');
        const lines = targetLines.length ? Array.from(targetLines) : [container];

        lines.forEach(function (line) {
            const rawHTML = line.innerHTML;
            const temp = document.createElement('div');
            temp.innerHTML = rawHTML;
            line.innerHTML = '';

            Array.from(temp.childNodes).forEach(function (node) {
                if (node.nodeType === Node.TEXT_NODE) {
                    const words = (node.textContent || '').split(/(\s+)/);
                    words.forEach(function (w) {
                        if (/^\s+$/.test(w)) {
                            line.appendChild(document.createTextNode(w));
                        } else if (w.length > 0) {
                            const span = document.createElement('span');
                            span.className = 'nj-scrub-word';
                            span.textContent = w;
                            line.appendChild(span);
                        }
                    });
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    const wrapper = document.createElement(node.tagName.toLowerCase());
                    wrapper.className = 'nj-scrub-word ' + (node.className || '');
                    wrapper.innerHTML = node.innerHTML;
                    line.appendChild(wrapper);
                }
            });
        });
    }

    /* ----------------------------------------------------------
     * 1. CUSTOM ARCHITECTURAL PRELOADER & HERO CHAR ENTRANCE
     * ---------------------------------------------------------- */
    const preloader = document.getElementById('nj-preloader');
    const preloaderBar = document.getElementById('nj-preloader-progress');
    const preloaderCount = document.getElementById('nj-preloader-count');
    const bpLines = document.querySelectorAll('.nj-bp-line');

    document.querySelectorAll('.nj-clip-text').forEach(splitTextToChars);

    function finishPreloader() {
        if (!preloader) return;
        preloader.classList.add('is-done');
        document.body.classList.remove('is-locked');
        window.scrollTo(0, 0);
        if (lenis) lenis.scrollTo(0, { immediate: true });
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 1050);
        playHeroEntrance();
    }

    if (preloader) {
        document.body.classList.add('is-locked');
        requestAnimationFrame(() => {
            bpLines.forEach((line) => {
                line.style.strokeDashoffset = '0';
            });
        });

        let count = 0;
        const timer = setInterval(() => {
            count += Math.floor(Math.random() * 14) + 8;
            if (count >= 100) {
                count = 100;
                clearInterval(timer);
                if (preloaderBar) preloaderBar.style.width = '100%';
                if (preloaderCount) preloaderCount.textContent = '100%';
                setTimeout(finishPreloader, 300);
            } else {
                if (preloaderBar) preloaderBar.style.width = count + '%';
                if (preloaderCount) preloaderCount.textContent = String(count).padStart(2, '0') + '%';
            }
        }, 36);
    } else {
        playHeroEntrance();
    }

    function playHeroEntrance() {
        if (typeof gsap === 'undefined') return;
        const tl = gsap.timeline({ defaults: { ease: 'expo.out', clearProps: 'transform,opacity' } });
        tl.fromTo('.nj-hero-kicker',
            { x: -28, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.85 }
        )
        .fromTo('.nj-hero-display .nj-char',
            { y: 45, rotateX: -30, opacity: 0 },
            { y: 0, rotateX: 0, opacity: 1, duration: 1.05, stagger: 0.035 },
            '-=0.6'
        )
        .fromTo('.nj-hero-portrait-wrap',
            { y: 40, scale: 0.94, opacity: 0 },
            { y: 0, scale: 1, opacity: 1, duration: 1.1 },
            '-=0.8'
        )
        .fromTo('.nj-hero-top-meta, .nj-hero-bottom',
            { y: 20, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.9, stagger: 0.12 },
            '-=0.75'
        );
    }

    /* ----------------------------------------------------------
     * 2. LENIS SMOOTH SCROLL + GSAP SCROLLTRIGGER + PROGRESS BAR
     * ---------------------------------------------------------- */
    let lenis = null;
    if (typeof Lenis !== 'undefined') {
        lenis = new Lenis({
            lerp: 0.075,
            smoothWheel: true,
            wheelMultiplier: 0.9,
        });
        lenis.scrollTo(0, { immediate: true });

        if (typeof ScrollTrigger !== 'undefined') {
            lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add((time) => {
                lenis.raf(time * 1000);
            });
            gsap.ticker.lagSmoothing(0);
        } else {
            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);
        }
    }

    const scrollProgressBar = document.getElementById('nj-scroll-progress-bar');
    window.addEventListener('scroll', () => {
        if (!scrollProgressBar) return;
        const docH = document.documentElement.scrollHeight - window.innerHeight;
        const pct = docH > 0 ? (window.scrollY / docH) * 100 : 0;
        scrollProgressBar.style.width = Math.min(100, Math.max(0, pct)) + '%';
    }, { passive: true });

    /* ----------------------------------------------------------
     * 3. FLOATING HEADER, ACTIVE INDICATOR & SMOOTH ANCHORS
     * ---------------------------------------------------------- */
    const header = document.getElementById('nj-header');
    const navLinks = Array.from(document.querySelectorAll('.nj-nav-link'));
    const navIndicator = document.getElementById('nj-nav-indicator');
    const burger = document.getElementById('nj-burger');
    const mobileMenu = document.getElementById('nj-mobile-menu');

    function updateNavIndicator(activeEl) {
        if (!navIndicator || !activeEl) return;
        const rect = activeEl.getBoundingClientRect();
        const parent = activeEl.closest('.nj-nav');
        if (!parent) return;
        const parentRect = parent.getBoundingClientRect();
        navIndicator.style.width = rect.width + 'px';
        navIndicator.style.transform = `translateX(${rect.left - parentRect.left}px)`;
    }

    const activeInitial = document.querySelector('.nj-nav-link.is-active');
    if (activeInitial) {
        setTimeout(() => updateNavIndicator(activeInitial), 150);
    }

    window.addEventListener('scroll', () => {
        if (!header) return;
        header.classList.toggle('is-scrolled', window.scrollY > 60);
    }, { passive: true });

    document.querySelectorAll('[data-scroll-to]').forEach((el) => {
        el.addEventListener('click', (e) => {
            const targetSelector = el.getAttribute('data-scroll-to');
            const target = targetSelector ? document.querySelector(targetSelector) : null;
            if (!target) return;
            e.preventDefault();

            if (mobileMenu && mobileMenu.classList.contains('is-open')) {
                toggleMobileMenu(false);
            }

            if (lenis) {
                lenis.scrollTo(target, { offset: -40 });
            } else {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    function toggleMobileMenu(forceState) {
        if (!burger || !mobileMenu) return;
        const isOpen = typeof forceState === 'boolean' ? forceState : !mobileMenu.classList.contains('is-open');
        mobileMenu.classList.toggle('is-open', isOpen);
        burger.classList.toggle('is-open', isOpen);
        burger.setAttribute('aria-expanded', String(isOpen));
        mobileMenu.setAttribute('aria-hidden', String(!isOpen));
        document.body.classList.toggle('is-locked', isOpen);
    }

    if (burger) {
        burger.addEventListener('click', () => toggleMobileMenu());
    }

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach((link) => {
                    const match = link.getAttribute('href') === '#' + id;
                    link.classList.toggle('is-active', match);
                    if (match) updateNavIndicator(link);
                });
            }
        });
    }, { rootMargin: '-40% 0px -45% 0px' });

    document.querySelectorAll('section[id]').forEach((sec) => sectionObserver.observe(sec));

    /* ----------------------------------------------------------
     * 4. CONTINUOUS SCROLL-SCRUBBED & 3D GSAP CHOREOGRAPHY
     * ---------------------------------------------------------- */
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        // Hero background continuous scroll parallax
        gsap.to('.nj-hero-bg-img', {
            yPercent: 18,
            scale: 1.15,
            ease: 'none',
            scrollTrigger: {
                trigger: '#home',
                start: 'top top',
                end: 'bottom top',
                scrub: 0.8,
            },
        });

        // Hero display text subtle rise & fade on scroll departure
        gsap.to('.nj-hero-copy', {
            yPercent: -15,
            opacity: 0.35,
            ease: 'none',
            scrollTrigger: {
                trigger: '#home',
                start: 'top top',
                end: 'bottom top',
                scrub: 0.8,
            },
        });

        // Section Headlines Dynamic Scroll-Scrubbed Rise & Word Reveal
        document.querySelectorAll('.nj-section-header .nj-h2, .nj-philosophy-header .nj-h2, .nj-portfolio-head .nj-h2, .nj-process-sticky .nj-h2, .nj-direction-copy .nj-h2-oversized, .nj-contact-left .nj-h2').forEach(function (headline) {
            splitLinesToWords(headline);
            const words = headline.querySelectorAll('.nj-scrub-word');
            if (words.length) {
                gsap.fromTo(words,
                    { opacity: 0.12, y: 32, rotateX: -22 },
                    {
                        opacity: 1,
                        y: 0,
                        rotateX: 0,
                        stagger: 0.04,
                        ease: 'power2.out',
                        scrollTrigger: {
                            trigger: headline,
                            start: 'top 94%',
                            end: 'top 56%',
                            scrub: 0.7,
                        },
                    }
                );
            }
        });

        // Section Index & Micro Tags slide-in
        document.querySelectorAll('.nj-index-tag, .nj-micro-tag').forEach(function (tag) {
            gsap.fromTo(tag,
                { x: -35, opacity: 0 },
                {
                    x: 0,
                    opacity: 1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: tag,
                        start: 'top 96%',
                        end: 'top 65%',
                        scrub: 0.6,
                    },
                }
            );
        });

        // Image Curtain Clip-Path Reveals ([data-clip-reveal])
        gsap.utils.toArray('[data-clip-reveal]').forEach((box) => {
            const img = box.querySelector('img');
            gsap.fromTo(box,
                { clipPath: 'inset(24% 16% 24% 16%)' },
                {
                    clipPath: 'inset(0% 0% 0% 0%)',
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: box,
                        start: 'top 94%',
                        end: 'top 48%',
                        scrub: 0.75,
                    },
                }
            );
            if (img) {
                gsap.fromTo(img,
                    { scale: 1.25 },
                    {
                        scale: 1.0,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: box,
                            start: 'top 94%',
                            end: 'top 38%',
                            scrub: 0.75,
                        },
                    }
                );
            }
        });

        // Timeline vertical line progress beam
        const timelineProg = document.getElementById('nj-timeline-progress');
        if (timelineProg) {
            gsap.to(timelineProg, {
                height: '100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: '.nj-timeline-shell',
                    start: 'top 65%',
                    end: 'bottom 75%',
                    scrub: 0.65,
                },
            });
        }

        // Timeline items 3D perspective entrance
        gsap.utils.toArray('.nj-timeline-item').forEach((item, idx) => {
            gsap.fromTo(item,
                { y: 70 + idx * 10, rotateX: -14, scale: 0.9, opacity: 0, transformPerspective: 1000 },
                {
                    y: 0,
                    rotateX: 0,
                    scale: 1,
                    opacity: 1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 94%',
                        end: 'top 52%',
                        scrub: 0.7,
                    },
                }
            );
        });

        // 2016 Milestone watermark parallax drift
        const milestoneYear = document.querySelector('.nj-milestone-year');
        if (milestoneYear) {
            gsap.to(milestoneYear, {
                yPercent: 24,
                ease: 'none',
                scrollTrigger: {
                    trigger: '#about',
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 0.85,
                },
            });
        }

        // Director card 3D tilt reveal
        gsap.fromTo('.nj-director-card',
            { y: 55, rotateY: 10, opacity: 0, transformPerspective: 1000 },
            {
                y: 0,
                rotateY: 0,
                opacity: 1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.nj-director-card',
                    start: 'top 94%',
                    end: 'top 58%',
                    scrub: 0.7,
                },
            }
        );

        /* ----------------------------------------------------------------------
           CONTINUOUS SCROLL-DRIVEN 3D FAN DECK // DISCIPLINES
           Cards fan open as section enters viewport and sweep along the 3D arc.
           ON CLICK: The clicked card sweeps to center AND POPS UP above the deck!
           ---------------------------------------------------------------------- */
        const fanStageWrap = document.getElementById('fanDeckStageWrap') || document.getElementById('nj-fandeck');
        const fanCards = Array.from(document.querySelectorAll('.nj-fandeck-card, .nj-fan-card'));
        const fanTabs = Array.from(document.querySelectorAll('.nj-fandeck-tab'));

        if (fanStageWrap && fanCards.length) {
            const deckState = {
                progressIndex: 2, // Default to Bespoke Living centered
                spreadFactor: 1,
            };

            function renderFanDeck() {
                const floatIdx = deckState.progressIndex;
                const spread = deckState.spreadFactor;
                const nearestInt = Math.max(0, Math.min(fanCards.length - 1, Math.round(floatIdx)));
                const isMobile = window.innerWidth < 768;

                fanTabs.forEach(function (tab, i) {
                    const isActive = i === nearestInt;
                    tab.classList.toggle('is-active', isActive);
                    tab.setAttribute('aria-selected', String(isActive));
                });

                fanCards.forEach((card, i) => {
                    const offset = i - floatIdx;
                    const absOffset = Math.abs(offset);

                    // Lateral spread across the 3D arc
                    const spreadX = offset * (isMobile ? 100 : 220) * spread;

                    // Arc vertical curvature
                    let dropY = ((offset * offset) * (isMobile ? 8.5 : 17) - 12) * spread + (1 - spread) * 75;

                    // POP-UP EFFECT: Active/centered card elevates dramatically upwards
                    const isCenter = absOffset < 0.45;
                    if (isCenter) {
                        const popUpLift = (1 - absOffset / 0.45) * (isMobile ? 38 : 62);
                        dropY -= popUpLift;
                    }

                    const rotZ = offset * (isMobile ? 6.5 : 11) * spread;
                    const rotY = offset * -15 * spread;
                    let translateZ = -absOffset * 85 * spread;
                    if (isCenter) {
                        translateZ += (1 - absOffset / 0.45) * 65; // Push forward in Z space
                    }

                    let scale = Math.max(0.74, (1.06 - absOffset * 0.11) * (0.85 + 0.15 * spread));
                    if (isCenter) {
                        scale *= (1 + (1 - absOffset / 0.45) * 0.08); // Slight magnification
                    }

                    const opacity = Math.max(0.25, 1 - absOffset * 0.3);
                    const brightness = Math.max(0.4, 1 - absOffset * 0.28);

                    card.classList.toggle('is-center', isCenter);
                    card.classList.toggle('is-active', isCenter);
                    card.classList.toggle('is-popped', isCenter);
                    card.style.zIndex = String(Math.round(100 - absOffset * 20));
                    card.style.opacity = opacity.toFixed(3);
                    card.style.filter = 'brightness(' + brightness.toFixed(2) + ')';
                    card.style.transform =
                        'translate3d(' + spreadX.toFixed(2) + 'px, ' + dropY.toFixed(2) + 'px, ' + translateZ.toFixed(2) + 'px) ' +
                        'rotateZ(' + rotZ.toFixed(2) + 'deg) rotateY(' + rotY.toFixed(2) + 'deg) scale(' + scale.toFixed(3) + ')';
                });
            }

            function animateToCard(targetIndex) {
                const clamped = Math.max(0, Math.min(fanCards.length - 1, targetIndex));
                if (typeof gsap !== 'undefined') {
                    gsap.to(deckState, {
                        progressIndex: clamped,
                        spreadFactor: 1,
                        duration: 0.85,
                        ease: 'power3.out',
                        overwrite: 'auto',
                        onUpdate: renderFanDeck,
                    });
                } else {
                    deckState.progressIndex = clamped;
                    deckState.spreadFactor = 1;
                    renderFanDeck();
                }
            }

            function updateFanDeckOnViewportScroll() {
                const rect = fanStageWrap.getBoundingClientRect();
                const vh = window.innerHeight;
                const totalTravel = vh * 0.85 + rect.height * 0.75;
                const currentTravel = vh * 0.85 - rect.top;
                const clampedRatio = Math.max(0, Math.min(1, currentTravel / Math.max(1, totalTravel)));

                const targetSpread = Math.min(1, 0.25 + (clampedRatio / 0.25) * 0.75);
                const targetIndex = clampedRatio * (fanCards.length - 1);

                gsap.to(deckState, {
                    progressIndex: targetIndex,
                    spreadFactor: targetSpread,
                    duration: 0.45,
                    ease: 'power2.out',
                    overwrite: 'auto',
                    onUpdate: renderFanDeck,
                });
            }

            window.addEventListener('scroll', updateFanDeckOnViewportScroll, { passive: true });
            window.addEventListener('resize', renderFanDeck);
            renderFanDeck();

            const disciplineDescriptions = [
                'Holistic residential and spatial planning grounded in proportion, natural daylight, and effortless circulation.',
                'Curated natural stone, travertine, smoked timber, and brushed bronze finishes selected for longevity and warmth.',
                'Serene private sanctuaries engineered around acoustic calm, indirect lighting, and bespoke upholstery.',
                'Modular kitchens, wardrobes, TV units, storage systems, beds, counters and bespoke cabinetry.',
                'Distinctive commercial lounges, executive suites, and brand-defining architectural interiors.',
            ];

            const dModal = document.getElementById('nj-discipline-modal');
            const dModalImg = document.getElementById('nj-modal-img');
            const dModalTag = document.getElementById('nj-modal-tag');
            const dModalTitle = document.getElementById('nj-modal-title');
            const dModalDesc = document.getElementById('nj-modal-desc');
            const dModalClose = document.getElementById('nj-modal-close');
            const dModalBackdrop = document.getElementById('nj-modal-backdrop');

            function closeDisciplineModal() {
                if (!dModal) return;
                dModal.classList.remove('is-open');
                dModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('is-locked');
                if (lenis) lenis.start();
            }

            function openDisciplineModal(idx) {
                if (!dModal || !dModalImg || !dModalTitle || !dModalDesc || !dModalTag) return;
                const card = fanCards[idx];
                if (!card) return;
                const img = card.querySelector('img');
                const titleEl = card.querySelector('h4') || card.querySelector('span');
                dModalImg.src = img ? img.src : '';
                dModalImg.alt = titleEl ? titleEl.textContent : '';
                dModalTag.textContent = `DISCIPLINE 0${idx + 1}`;
                dModalTitle.textContent = titleEl ? titleEl.textContent : '';
                dModalDesc.textContent = disciplineDescriptions[idx] || '';
                dModal.classList.add('is-open');
                dModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('is-locked');
                if (lenis) lenis.stop();
            }

            if (dModalClose) dModalClose.addEventListener('click', closeDisciplineModal);
            if (dModalBackdrop) dModalBackdrop.addEventListener('click', closeDisciplineModal);
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && dModal && dModal.classList.contains('is-open')) {
                    closeDisciplineModal();
                }
            });

            // Card click: brings card to center AND pops it up!
            fanCards.forEach((card, idx) => {
                card.addEventListener('click', (e) => {
                    const currentNearest = Math.round(deckState.progressIndex);
                    const isExploreBtn = e.target.closest('.nj-fandeck-explore-btn');

                    if (idx !== currentNearest && !isExploreBtn) {
                        // First click: smoothly glide to center & pop up the card!
                        animateToCard(idx);
                    } else {
                        // Card is already popped up in center! Open full modal if clicking explore button or clicking already active card
                        if (isExploreBtn || idx === currentNearest) {
                            openDisciplineModal(idx);
                        }
                    }
                });
            });

            fanTabs.forEach((tab, idx) => {
                tab.addEventListener('click', () => {
                    animateToCard(idx);
                });
            });
        }

        // Philosophy pillars 3D stagger
        gsap.utils.toArray('.nj-pillar').forEach((pillar) => {
            gsap.fromTo(pillar,
                { y: 50, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: pillar,
                        start: 'top 92%',
                        end: 'top 60%',
                        scrub: 0.65,
                    },
                }
            );
        });

        // Portfolio Masonry: Converge from outer sides toward the middle on scroll
        gsap.utils.toArray('.nj-project-card').forEach((card, idx) => {
            const fromLeft = idx % 2 === 0;
            gsap.fromTo(card,
                {
                    x: fromLeft ? -130 : 130,
                    y: 55,
                    rotateY: fromLeft ? 12 : -12,
                    scale: 0.92,
                    opacity: 0,
                    transformPerspective: 1100,
                },
                {
                    x: 0,
                    y: 0,
                    rotateY: 0,
                    scale: 1,
                    opacity: 1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 94%',
                        end: 'top 56%',
                        scrub: 0.75,
                    },
                }
            );
        });

        // Process methodology steps scrubbed 3D glide
        gsap.utils.toArray('.nj-step-item').forEach((step, idx) => {
            gsap.fromTo(step,
                { x: 50, y: 30, rotateX: -12, scale: 0.92, opacity: 0, transformPerspective: 900 },
                {
                    x: 0,
                    y: 0,
                    rotateX: 0,
                    scale: 1,
                    opacity: 1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: step,
                        start: 'top 94%',
                        end: 'top 60%',
                        scrub: 0.7,
                    },
                }
            );
        });

        // 3D Cinema Frame scroll straighten
        const cinemaFrame = document.getElementById('nj-cinema-frame');
        if (cinemaFrame) {
            gsap.fromTo(cinemaFrame,
                { rotateY: -16, rotateX: 8, scale: 0.9, opacity: 0.3, transformPerspective: 1200 },
                {
                    rotateY: 0,
                    rotateX: 0,
                    scale: 1,
                    opacity: 1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: '#direction',
                        start: 'top 85%',
                        end: 'center center',
                        scrub: 0.8,
                    },
                }
            );
        }

        // Vision Section Scroll-Expanding Portal & Quote Drift (njinteriors.co.in signature)
        const visionSection = document.getElementById('vision');
        const visionWrap = document.getElementById('visionZoomWrap');
        const visionImg = document.getElementById('visionZoomImg');
        const visionQuote = document.querySelector('.nj-vision-quote');

        if (visionSection && visionWrap && visionImg) {
            const visionTl = gsap.timeline({
                scrollTrigger: {
                    trigger: visionSection,
                    start: 'top 92%',
                    end: 'center center',
                    scrub: 0.8,
                },
            });

            visionTl.fromTo(visionWrap,
                { clipPath: 'inset(24% 18% 24% 18%)' },
                { clipPath: 'inset(0% 0% 0% 0%)', ease: 'none' },
                0
            );

            visionTl.fromTo(visionImg,
                { scale: 1.35 },
                { scale: 1.0, ease: 'none' },
                0
            );

            if (visionQuote) {
                visionTl.fromTo(visionQuote,
                    { y: 75, scale: 0.88, opacity: 0 },
                    { y: 0, scale: 1, opacity: 1, ease: 'power2.out' },
                    0.08
                );
            }
        }

        // Contact Section details & lift cards
        gsap.utils.toArray('.nj-detail-block, .nj-contact-photo, .nj-contact-right').forEach((el, idx) => {
            gsap.fromTo(el,
                { y: 45 + idx * 10, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: el,
                        start: 'top 95%',
                        end: 'top 60%',
                        scrub: 0.7,
                    },
                }
            );
        });

        // Multi-layer Continuous Parallax Elements ([data-parallax-speed])
        document.querySelectorAll('[data-parallax-speed]').forEach(function (el) {
            const speed = parseFloat(el.getAttribute('data-parallax-speed') || '0.15');
            gsap.to(el, {
                yPercent: speed * 100,
                ease: 'none',
                scrollTrigger: {
                    trigger: el,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 0.85,
                },
            });
        });
    }

    /* ----------------------------------------------------------
     * 5. 3D MOUSE TILT ON CARDS & PORTRAIT
     * ---------------------------------------------------------- */
    if (window.matchMedia('(pointer: fine)').matches) {
        document.querySelectorAll('.nj-tilt-3d').forEach((card) => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                card.style.transform = `perspective(1000px) rotateX(${(-y * 6).toFixed(2)}deg) rotateY(${(x * 7).toFixed(2)}deg) translateZ(6px)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });
    }

    /* ----------------------------------------------------------
     * 6. INTERACTIVE SPLIT COMPARE (ENGINEERING <-> DESIGN)
     * ---------------------------------------------------------- */
    const compareBox = document.getElementById('nj-split-compare');
    const compareOverlay = document.getElementById('nj-compare-overlay');
    const compareDivider = document.getElementById('nj-compare-divider');

    if (compareBox && compareOverlay && compareDivider) {
        const updateSplit = (clientX) => {
            const rect = compareBox.getBoundingClientRect();
            let pct = ((clientX - rect.left) / rect.width) * 100;
            pct = Math.max(8, Math.min(92, pct));
            compareOverlay.style.clipPath = `inset(0 0 0 ${pct}%)`;
            compareDivider.style.left = `${pct}%`;
        };

        compareBox.addEventListener('mousemove', (e) => updateSplit(e.clientX));
        compareBox.addEventListener('touchmove', (e) => {
            if (e.touches[0]) updateSplit(e.touches[0].clientX);
        }, { passive: true });
    }

    /* ----------------------------------------------------------
     * 7. THREE.JS ARCHITECTURAL WIREFRAME & DUST PARTICLES
     * ---------------------------------------------------------- */
    const heroCanvas = document.getElementById('nj-hero-canvas');
    if (heroCanvas && typeof THREE !== 'undefined' && window.innerWidth >= 768) {
        try {
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
            camera.position.z = 5;

            const renderer = new THREE.WebGLRenderer({ canvas: heroCanvas, alpha: true, antialias: true });
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
            renderer.setSize(window.innerWidth, window.innerHeight);

            const geometry = new THREE.IcosahedronGeometry(1.75, 1);
            const wireframe = new THREE.WireframeGeometry(geometry);
            const lineMat = new THREE.LineBasicMaterial({ color: 0x9A8062, transparent: true, opacity: 0.24 });
            const archWire = new THREE.LineSegments(wireframe, lineMat);
            archWire.position.set(1.5, 0.15, 0);
            scene.add(archWire);

            const particleCount = 80;
            const pos = new Float32Array(particleCount * 3);
            for (let i = 0; i < particleCount * 3; i++) {
                pos[i] = (Math.random() - 0.5) * 8.5;
            }
            const pGeo = new THREE.BufferGeometry();
            pGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
            const pMat = new THREE.PointsMaterial({ color: 0xD9CCBA, size: 0.026, transparent: true, opacity: 0.5 });
            const particles = new THREE.Points(pGeo, pMat);
            scene.add(particles);

            let mouseX = 0;
            let mouseY = 0;
            window.addEventListener('mousemove', (e) => {
                mouseX = (e.clientX / window.innerWidth - 0.5) * 0.45;
                mouseY = (e.clientY / window.innerHeight - 0.5) * 0.45;
            }, { passive: true });

            function animateThree() {
                requestAnimationFrame(animateThree);
                archWire.rotation.y += 0.0014;
                archWire.rotation.x += 0.0007;
                particles.rotation.y -= 0.0005;
                camera.position.x += (mouseX - camera.position.x) * 0.035;
                camera.position.y += (-mouseY - camera.position.y) * 0.035;
                renderer.render(scene, camera);
            }
            animateThree();

            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });
        } catch (err) {
            // Graceful fallback if WebGL is disabled
        }
    }

    /* ----------------------------------------------------------
     * 8. DUAL CONTEXTUAL CURSOR & MAGNETIC BUTTONS
     * ---------------------------------------------------------- */
    const cursorDot = document.getElementById('nj-cursor-dot');
    const cursorRing = document.getElementById('nj-cursor');
    const cursorLabel = document.getElementById('nj-cursor-label');

    if (cursorRing && window.matchMedia('(pointer: fine)').matches) {
        let cx = -100, cy = -100, tx = -100, ty = -100;
        window.addEventListener('mousemove', (e) => {
            tx = e.clientX;
            ty = e.clientY;
            if (cursorDot) {
                cursorDot.style.left = tx + 'px';
                cursorDot.style.top = ty + 'px';
            }
        }, { passive: true });

        function renderCursor() {
            cx += (tx - cx) * 0.16;
            cy += (ty - cy) * 0.16;
            cursorRing.style.left = cx + 'px';
            cursorRing.style.top = cy + 'px';
            requestAnimationFrame(renderCursor);
        }
        renderCursor();

        document.querySelectorAll('[data-cursor]').forEach((el) => {
            el.addEventListener('mouseenter', () => {
                if (cursorLabel) cursorLabel.textContent = el.getAttribute('data-cursor') || '';
                cursorRing.classList.add('is-active');
            });
            el.addEventListener('mouseleave', () => {
                cursorRing.classList.remove('is-active');
            });
        });

        document.querySelectorAll('.magnetic').forEach((btn) => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const dx = (e.clientX - rect.left - rect.width / 2) * 0.22;
                const dy = (e.clientY - rect.top - rect.height / 2) * 0.22;
                btn.style.transform = `translate(${dx}px, ${dy}px)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = '';
            });
        });
    }

    /* ----------------------------------------------------------
     * 9. PORTFOLIO CATEGORY FILTERING & AJAX PROJECT DRAWER
     * ---------------------------------------------------------- */
    const filterButtons = document.querySelectorAll('.nj-filter-btn');
    const projectCards = document.querySelectorAll('.nj-project-card');

    filterButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const filter = btn.getAttribute('data-filter');
            filterButtons.forEach((b) => {
                b.classList.toggle('is-active', b === btn);
                b.setAttribute('aria-selected', String(b === btn));
            });

            projectCards.forEach((card) => {
                const cat = card.getAttribute('data-category');
                const show = filter === 'all' || cat === filter;
                card.classList.toggle('is-hidden', !show);
            });
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
        });
    });

    const drawer = document.getElementById('nj-project-drawer');
    const drawerContent = document.getElementById('nj-drawer-content');
    const drawerClose = document.getElementById('nj-drawer-close');
    const drawerBackdrop = document.getElementById('nj-drawer-backdrop');

    function closeDrawer() {
        if (!drawer) return;
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('is-locked');
        if (lenis) lenis.start();
    }

    if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
    if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) {
            closeDrawer();
        }
    });

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    projectCards.forEach((card) => {
        const openProject = async () => {
            const slug = card.getAttribute('data-project-slug');
            if (!slug || !drawer || !drawerContent) return;

            drawerContent.innerHTML = '<p class="nj-micro-tag">LOADING PROJECT STUDY...</p>';
            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            document.body.classList.add('is-locked');
            if (lenis) lenis.stop();

            try {
                const res = await fetch(`project/${encodeURIComponent(slug)}?format=json`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                if (!data.success || !data.project) throw new Error('Not found');

                const p = data.project;
                const catName = p.category ? p.category.name : 'Interior Design';
                const galleryHtml = (p.images || []).map((img) => {
                    const imgSrc = String(img.image_path || '').replace(/^\/+/, '');
                    return `
                        <figure>
                            <img src="${escapeHtml(imgSrc)}" alt="${escapeHtml(img.caption || p.title)}" decoding="async">
                            ${img.caption ? `<figcaption>${escapeHtml(img.caption)}</figcaption>` : ''}
                        </figure>
                    `;
                }).join('');

                const coverSrc = String(p.cover_image || '').replace(/^\/+/, '');

                drawerContent.innerHTML = `
                    <span class="nj-index-tag">${escapeHtml(catName)} &middot; ${escapeHtml(p.year || '')}</span>
                    <h2 id="drawer-project-title" class="nj-h2">${escapeHtml(p.title)}</h2>
                    <p class="nj-lead" style="margin-bottom: 1.5rem;">${escapeHtml(p.location || '')}</p>
                    <img src="${escapeHtml(coverSrc)}" alt="${escapeHtml(p.title)}" style="width:100%; border-radius:4px; margin-bottom:1.5rem;">
                    <div class="nj-drawer-specs">
                        <div>
                            <span class="nj-micro-tag">DESCRIPTION</span>
                            <p class="nj-body-copy">${escapeHtml(p.description || p.summary)}</p>
                        </div>
                        <div>
                            <span class="nj-micro-tag">DESIGN CONCEPT</span>
                            <p class="nj-body-copy">${escapeHtml(p.design_concept || 'Bespoke architectural composition.')}</p>
                        </div>
                        <div>
                            <span class="nj-micro-tag">MATERIALS &amp; FINISHES</span>
                            <p class="nj-body-copy">${escapeHtml(p.materials_finishes || 'Natural stone, timber &amp; bronze.')}</p>
                        </div>
                    </div>
                    <div class="nj-drawer-gallery">${galleryHtml}</div>
                `;
            } catch (err) {
                drawerContent.innerHTML = '<p class="nj-body-copy">Unable to load project details right now.</p>';
            }
        };

        card.addEventListener('click', openProject);
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openProject();
            }
        });
    });

    /* ----------------------------------------------------------
     * 10. AJAX CONTACT FORM SUBMISSION
     * ---------------------------------------------------------- */
    const contactForm = document.getElementById('nj-contact-form');
    const formStatus = document.getElementById('nj-form-status');
    const submitBtn = document.getElementById('nj-contact-submit');

    if (contactForm && formStatus && submitBtn) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            formStatus.textContent = 'Sending your inquiry...';
            formStatus.className = 'nj-form-status';
            submitBtn.disabled = true;

            try {
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: new FormData(contactForm),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const result = await response.json();
                if (result.success) {
                    formStatus.textContent = result.message;
                    formStatus.classList.add('is-success');
                    contactForm.reset();
                } else {
                    formStatus.textContent = result.message || 'Please check your input and try again.';
                    formStatus.classList.add('is-error');
                }
            } catch (err) {
                formStatus.textContent = 'Network error. Please try again.';
                formStatus.classList.add('is-error');
            } finally {
                submitBtn.disabled = false;
            }
        });
    }
})();
