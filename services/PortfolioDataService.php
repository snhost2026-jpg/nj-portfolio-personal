<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use app\models\ContactSubmission;

/**
 * File-backed JSON data service for the single-page NJ Interiors portfolio.
 * Requires no database server while keeping all content, inquiries, and CMS edits persistent.
 */
class PortfolioDataService
{
    private string $dataFile;
    private string $inquiriesFile;

    public function __construct()
    {
        $dataDir = Yii::getAlias('@app/data');
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0775, true);
        }
        $this->dataFile = $dataDir . DIRECTORY_SEPARATOR . 'portfolio.json';
        $this->inquiriesFile = $dataDir . DIRECTORY_SEPARATOR . 'contact_submissions.json';
    }

    public function getAllData(): array
    {
        if (is_file($this->dataFile)) {
            $decoded = json_decode((string)file_get_contents($this->dataFile), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $defaults = [
            'settings' => $this->defaultSettings(),
            'timeline' => $this->defaultTimeline(),
            'categories' => $this->defaultCategories(),
            'projects' => $this->defaultProjects(),
        ];

        $this->saveAllData($defaults);
        return $defaults;
    }

    public function saveAllData(array $data): bool
    {
        return file_put_contents(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        ) !== false;
    }

    public function getSettings(): array
    {
        return array_merge($this->defaultSettings(), $this->getAllData()['settings'] ?? []);
    }

    public function saveSettings(array $posted): bool
    {
        $data = $this->getAllData();
        foreach ($data['settings'] as $key => $val) {
            if (array_key_exists($key, $posted)) {
                $data['settings'][$key] = (string)$posted[$key];
            }
        }
        return $this->saveAllData($data);
    }

    public function getTimelineEntries(): array
    {
        $entries = $this->getAllData()['timeline'] ?? [];
        usort($entries, static fn(array $a, array $b): int => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
        return array_values(array_filter($entries, static fn(array $e): bool => !isset($e['is_active']) || (int)$e['is_active'] === 1));
    }

    public function getAllTimelineEntries(): array
    {
        $entries = $this->getAllData()['timeline'] ?? [];
        usort($entries, static fn(array $a, array $b): int => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
        return $entries;
    }

    public function getTimelineEntryById(int $id): ?array
    {
        foreach ($this->getAllTimelineEntries() as $entry) {
            if ((int)$entry['id'] === $id) {
                return $entry;
            }
        }
        return null;
    }

    public function saveTimelineEntry(int $id, array $fields): bool
    {
        $data = $this->getAllData();
        foreach ($data['timeline'] as &$entry) {
            if ((int)$entry['id'] === $id) {
                $entry = array_merge($entry, $fields);
                return $this->saveAllData($data);
            }
        }
        return false;
    }

    public function getCategories(): array
    {
        $cats = $this->getAllData()['categories'] ?? [];
        usort($cats, static fn(array $a, array $b): int => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
        return $cats;
    }

    public function addCategory(string $name, string $slug, int $sortOrder): bool
    {
        $data = $this->getAllData();
        $maxId = 0;
        foreach ($data['categories'] as $cat) {
            $maxId = max($maxId, (int)$cat['id']);
        }
        $data['categories'][] = [
            'id' => $maxId + 1,
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $sortOrder,
        ];
        return $this->saveAllData($data);
    }

    public function deleteCategory(int $id): bool
    {
        $data = $this->getAllData();
        foreach ($data['projects'] as $project) {
            if ((int)($project['category_id'] ?? 0) === $id) {
                return false;
            }
        }
        $data['categories'] = array_values(array_filter(
            $data['categories'],
            static fn(array $c): bool => (int)$c['id'] !== $id
        ));
        return $this->saveAllData($data);
    }

    public function getProjects(bool $publishedOnly = true): array
    {
        $all = $this->getAllData();
        $catMap = [];
        foreach ($all['categories'] ?? [] as $cat) {
            $catMap[(int)$cat['id']] = $cat;
        }

        $projects = $all['projects'] ?? [];
        if ($publishedOnly) {
            $projects = array_values(array_filter($projects, static fn(array $p): bool => (int)($p['status'] ?? 1) === 1));
        }

        foreach ($projects as &$project) {
            $cid = (int)($project['category_id'] ?? 0);
            if (isset($catMap[$cid])) {
                $project['category'] = $catMap[$cid];
            }
        }
        unset($project);

        usort($projects, static fn(array $a, array $b): int => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
        return $projects;
    }

    public function getProjectById(int $id): ?array
    {
        foreach ($this->getProjects(false) as $project) {
            if ((int)$project['id'] === $id) {
                return $project;
            }
        }
        return null;
    }

    public function getProjectBySlug(string $slug): ?array
    {
        foreach ($this->getProjects(true) as $project) {
            if ($project['slug'] === $slug) {
                return $project;
            }
        }
        return null;
    }

    public function saveProject(?int $id, array $fields): int
    {
        $data = $this->getAllData();
        if ($id === null) {
            $maxId = 0;
            foreach ($data['projects'] as $p) {
                $maxId = max($maxId, (int)$p['id']);
            }
            $newId = $maxId + 1;
            $fields['id'] = $newId;
            $fields['images'] = $fields['images'] ?? [];
            $data['projects'][] = $fields;
            $this->saveAllData($data);
            return $newId;
        }

        foreach ($data['projects'] as &$p) {
            if ((int)$p['id'] === $id) {
                $p = array_merge($p, $fields);
                $this->saveAllData($data);
                return $id;
            }
        }
        return $id;
    }

    public function deleteProject(int $id): bool
    {
        $data = $this->getAllData();
        $data['projects'] = array_values(array_filter(
            $data['projects'],
            static fn(array $p): bool => (int)$p['id'] !== $id
        ));
        return $this->saveAllData($data);
    }

    public function getInquiries(): array
    {
        if (!is_file($this->inquiriesFile)) {
            return [];
        }
        $list = json_decode((string)file_get_contents($this->inquiriesFile), true);
        if (!is_array($list)) {
            return [];
        }
        usort($list, static fn(array $a, array $b): int => ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0));
        return $list;
    }

    public function saveContactSubmission(array $postData, ?string $ipAddress): array
    {
        $model = new ContactSubmission();
        $model->name = (string)($postData['name'] ?? '');
        $model->email = (string)($postData['email'] ?? '');
        $model->phone = (string)($postData['phone'] ?? '');
        $model->project_type = (string)($postData['project_type'] ?? '');
        $model->message = (string)($postData['message'] ?? '');

        if (!$model->validate()) {
            return [
                'success' => false,
                'errors' => $model->getErrors(),
                'message' => 'Please check the highlighted fields and try again.',
            ];
        }

        $inquiries = $this->getInquiries();
        $maxId = 0;
        foreach ($inquiries as $item) {
            $maxId = max($maxId, (int)($item['id'] ?? 0));
        }

        $inquiries[] = [
            'id' => $maxId + 1,
            'name' => $model->name,
            'email' => $model->email,
            'phone' => $model->phone,
            'project_type' => $model->project_type,
            'message' => $model->message,
            'ip_address' => $ipAddress,
            'is_read' => 0,
            'created_at' => time(),
        ];

        file_put_contents(
            $this->inquiriesFile,
            json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );

        return [
            'success' => true,
            'message' => 'Thank you. Your inquiry has been received by NJ Interiors. We will respond shortly.',
        ];
    }

    public function markInquiryRead(int $id): bool
    {
        $inquiries = $this->getInquiries();
        foreach ($inquiries as &$inq) {
            if ((int)($inq['id'] ?? 0) === $id) {
                $inq['is_read'] = 1;
            }
        }
        return file_put_contents(
            $this->inquiriesFile,
            json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        ) !== false;
    }

    public function deleteInquiry(int $id): bool
    {
        $inquiries = array_values(array_filter(
            $this->getInquiries(),
            static fn(array $inq): bool => (int)($inq['id'] ?? 0) !== $id
        ));
        return file_put_contents(
            $this->inquiriesFile,
            json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        ) !== false;
    }

    private function defaultSettings(): array
    {
        return [
            'seo_title' => 'Nitesh Jangda — Creative Director | NJ Interiors',
            'seo_description' => 'Nitesh Jangda, Creative Director of NJ Interiors. Four generations of craftsmanship, engineering precision, and contemporary luxury interior design.',
            'seo_keywords' => 'Nitesh Jangda, NJ Interiors, Luxury Interior Design, Creative Director, Residential Interiors, Commercial Interiors, Bespoke Furniture',
            'about_heading' => 'FROM ENGINEERING TO DESIGN.',
            'about_body' => 'An Electronics Engineering graduate, Nitesh initially began his professional journey in technical support. However, his passion for creativity, architecture and interior spaces inspired him to pursue a different path. In 2016, he founded NJ Interiors, bringing together his technical knowledge, creative vision and family\'s rich heritage of craftsmanship.',
            'vision_quote' => 'To create spaces that go beyond beauty — spaces that tell stories, inspire living and stand the test of time.',
            'contact_email' => 'studio@njinteriors.in',
            'contact_phone' => '+91 98000 00000',
            'contact_address' => 'Bhubaneswar & Mumbai, India',
            'social_instagram' => 'https://instagram.com/njinteriors',
            'social_linkedin' => 'https://linkedin.com/in/niteshjangda',
            'social_behance' => 'https://behance.net/njinteriors',
            'social_youtube' => 'https://youtube.com/@njinteriors',
        ];
    }

    private function defaultTimeline(): array
    {
        return [
            [
                'id' => 1,
                'era_label' => 'GEN 01 // FOUNDATION',
                'title' => 'Great-Grandfather',
                'subtitle' => 'Civil Contracting & Structural Heritage',
                'description' => 'The family\'s journey began with Nitesh\'s great-grandfather, a respected civil contractor whose work laid the architectural foundation of trust, structural integrity, and enduring built environments.',
                'image_path' => '/media/legacy/civil-foundation.webp',
                'sort_order' => 1,
                'is_active' => 1,
            ],
            [
                'id' => 2,
                'era_label' => 'GEN 02 // CRAFT',
                'title' => 'Grandfather',
                'subtitle' => 'Master Carpentry Workshop',
                'description' => 'Followed by his grandfather\'s carpentry workshop, where raw timber was shaped by hand into bespoke joinery, architectural woodwork, and heirloom furniture.',
                'image_path' => '/media/legacy/heritage-workshop.webp',
                'sort_order' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 3,
                'era_label' => 'GEN 03 // PRECISION',
                'title' => 'Father — Navadurga Engineering Works',
                'subtitle' => 'Engineering Equipment, Steel & Architectural Metalwork',
                'description' => 'His father\'s entrepreneurial venture, Navadurga Engineering Works, specialised in engineering equipment, machinery, steel furniture, and architectural metalwork — instilling precision and industrial discipline.',
                'image_path' => '/media/legacy/metalwork-precision.webp',
                'sort_order' => 3,
                'is_active' => 1,
            ],
            [
                'id' => 4,
                'era_label' => 'GEN 04 // VISION (2016)',
                'title' => 'Nitesh Jangda — NJ Interiors',
                'subtitle' => 'Creative Direction & Contemporary Living',
                'description' => 'Growing up surrounded by workshops, materials and skilled craftsmanship, Nitesh founded NJ Interiors in 2016 — uniting four generations of making with contemporary interior architecture.',
                'image_path' => '/media/legacy/nj-studio-2016.webp',
                'sort_order' => 4,
                'is_active' => 1,
            ],
        ];
    }

    private function defaultCategories(): array
    {
        return [
            ['id' => 1, 'name' => 'Residential Interiors', 'slug' => 'residential-interiors', 'sort_order' => 1],
            ['id' => 2, 'name' => 'Luxury Living Spaces', 'slug' => 'luxury-living-spaces', 'sort_order' => 2],
            ['id' => 3, 'name' => 'Bedrooms', 'slug' => 'bedrooms', 'sort_order' => 3],
            ['id' => 4, 'name' => 'Kitchens', 'slug' => 'kitchens', 'sort_order' => 4],
            ['id' => 5, 'name' => 'Commercial Interiors', 'slug' => 'commercial-interiors', 'sort_order' => 5],
            ['id' => 6, 'name' => 'Bespoke Furniture', 'slug' => 'bespoke-furniture', 'sort_order' => 6],
            ['id' => 7, 'name' => 'Architectural Details', 'slug' => 'architectural-details', 'sort_order' => 7],
        ];
    }

    private function defaultProjects(): array
    {
        return [
            [
                'id' => 1,
                'category_id' => 2,
                'title' => 'The Travertine Pavilion (Sample)',
                'slug' => 'travertine-pavilion-sample',
                'location' => 'Sample Residence — Mumbai',
                'year' => '2025',
                'summary' => 'A sunlit contemporary living space anchored by monolithic travertine, walnut millwork, and warm bronze accents.',
                'description' => 'Sample project demonstrating NJ Interiors\' spatial planning approach. Designed around natural daylight, tactile stone surfaces, and calm architectural proportions.',
                'design_concept' => 'Quiet luxury rooted in raw natural stone, recessed linear lighting, and bespoke low-slung seating.',
                'materials_finishes' => 'Honed Italian Travertine, Smoked American Walnut, Brushed Bronze, Bouclé Linen',
                'cover_image' => '/media/projects/hero-living.webp',
                'aspect_ratio' => 'landscape',
                'is_featured' => 1,
                'is_sample' => 1,
                'sort_order' => 1,
                'status' => 1,
                'images' => [
                    ['image_path' => '/media/projects/hero-living.webp', 'caption' => 'Primary Living Pavilion'],
                    ['image_path' => '/media/legacy/engineering-sketch.webp', 'caption' => 'Material & Elevation Study'],
                ],
            ],
            [
                'id' => 2,
                'category_id' => 4,
                'title' => 'Monolith Culinary Atelier (Sample)',
                'slug' => 'monolith-culinary-atelier-sample',
                'location' => 'Sample Residence — Bhubaneswar',
                'year' => '2025',
                'summary' => 'Architectural kitchen featuring a sculptural Calacatta marble island and fluted dark oak cabinetry.',
                'description' => 'Sample culinary space balancing precision engineering with warm residential hospitality. Concealed appliances and integrated bronze task lighting keep sightlines uninterrupted.',
                'design_concept' => 'Precision geometry softened by natural stone veining and warm timber grain.',
                'materials_finishes' => 'Calacatta Viola Marble, Fluted Dark Oak, PVD Brushed Bronze, Matte Lacquer',
                'cover_image' => '/media/projects/project-kitchen.webp',
                'aspect_ratio' => 'landscape',
                'is_featured' => 1,
                'is_sample' => 1,
                'sort_order' => 2,
                'status' => 1,
                'images' => [
                    ['image_path' => '/media/projects/project-kitchen.webp', 'caption' => 'Sculptural Stone Island'],
                ],
            ],
            [
                'id' => 3,
                'category_id' => 3,
                'title' => 'Serene Timber Suite (Sample)',
                'slug' => 'serene-timber-suite-sample',
                'location' => 'Sample Residence — Pune',
                'year' => '2024',
                'summary' => 'A calm master bedroom sanctuary layered with slatted oak paneling, indirect cove light, and organic linen.',
                'description' => 'Sample private suite engineered for acoustic stillness and tactile warmth, pairing custom upholstered joinery with monolithic travertine side tables.',
                'design_concept' => 'Restorative minimalism using warm indirect illumination and natural textile layering.',
                'materials_finishes' => 'Quarter-Sawn White Oak, Belgian Linen, Honed Travertine, Warm Bronze Hardware',
                'cover_image' => '/media/projects/project-bedroom.webp',
                'aspect_ratio' => 'portrait',
                'is_featured' => 1,
                'is_sample' => 1,
                'sort_order' => 3,
                'status' => 1,
                'images' => [
                    ['image_path' => '/media/projects/project-bedroom.webp', 'caption' => 'Master Suite Sanctuary'],
                ],
            ],
            [
                'id' => 4,
                'category_id' => 5,
                'title' => 'Atelier Executive Lounge (Sample)',
                'slug' => 'atelier-executive-lounge-sample',
                'location' => 'Sample Commercial — New Delhi',
                'year' => '2024',
                'summary' => 'Boutique commercial lounge with fluted bronze glass partitions, stone reception, and bespoke walnut seating.',
                'description' => 'Sample commercial interior crafted to project architectural authority and understated hospitality from the first threshold.',
                'design_concept' => 'Architectural rhythm through fluted glass, monolithic stone, and gallery-grade lighting.',
                'materials_finishes' => 'Fluted Bronze Glass, Basaltina Stone, Saddle Leather, Walnut Veneer',
                'cover_image' => '/media/projects/project-commercial.webp',
                'aspect_ratio' => 'landscape',
                'is_featured' => 1,
                'is_sample' => 1,
                'sort_order' => 4,
                'status' => 1,
                'images' => [
                    ['image_path' => '/media/projects/project-commercial.webp', 'caption' => 'Reception & Lounge'],
                ],
            ],
            [
                'id' => 5,
                'category_id' => 6,
                'title' => 'Navadurga Brass & Walnut Credenza (Sample)',
                'slug' => 'navadurga-credenza-sample',
                'location' => 'Sample Bespoke Piece — Studio Edition',
                'year' => '2024',
                'summary' => 'Custom-engineered furniture piece uniting handcrafted timber joinery with precision-machined steel and brass.',
                'description' => 'Sample bespoke furniture study celebrating NJ Interiors\' four-generation heritage in carpentry and architectural metalwork.',
                'design_concept' => 'Structural honesty where exposed brass joinery becomes the primary ornament.',
                'materials_finishes' => 'Solid Burma Teak, Machined Brass, Powder-Coated Architectural Steel',
                'cover_image' => '/media/projects/project-credenza.webp',
                'aspect_ratio' => 'landscape',
                'is_featured' => 0,
                'is_sample' => 1,
                'sort_order' => 5,
                'status' => 1,
                'images' => [
                    ['image_path' => '/media/projects/project-credenza.webp', 'caption' => 'Bookmatched Walnut & Brass Credenza'],
                    ['image_path' => '/media/legacy/heritage-workshop.webp', 'caption' => 'Workshop Joinery Craft'],
                ],
            ],
            [
                'id' => 6,
                'category_id' => 7,
                'title' => 'Threshold & Light Study (Sample)',
                'slug' => 'threshold-light-study-sample',
                'location' => 'Sample Architectural Detail — Studio',
                'year' => '2025',
                'summary' => 'Detailed elevation study of recessed cove lighting, shadow gaps, and bespoke brass hardware.',
                'description' => 'Sample architectural detailing study demonstrating how millimeter-level tolerances elevate everyday spatial transitions.',
                'design_concept' => 'Precision engineering translated into tactile, light-sculpted interior surfaces.',
                'materials_finishes' => 'Anodized Bronze Profiles, Limewash Plaster, Cast Brass Hardware',
                'cover_image' => '/media/projects/project-detail.webp',
                'aspect_ratio' => 'portrait',
                'is_featured' => 0,
                'is_sample' => 1,
                'sort_order' => 6,
                'status' => 1,
                'images' => [
                    ['image_path' => '/media/projects/project-detail.webp', 'caption' => 'Fluted Walnut & Cove Light Detail'],
                    ['image_path' => '/media/legacy/engineering-sketch.webp', 'caption' => 'Elevation & Material Swatches'],
                ],
            ],
        ];
    }
}
