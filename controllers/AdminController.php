<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\services\MediaUploadService;
use app\services\PortfolioDataService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Database-free Admin CMS controller backed by data/portfolio.json and data/contact_submissions.json.
 */
class AdminController extends Controller
{
    public $layout = 'admin';

    public function __construct(
        $id,
        $module,
        private readonly MediaUploadService $mediaUpload,
        private readonly PortfolioDataService $portfolioService,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'project-delete' => ['post'],
                    'category-delete' => ['post'],
                    'inquiry-delete' => ['post'],
                    'inquiry-mark-read' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $projects = $this->portfolioService->getProjects(false);
        $categories = $this->portfolioService->getCategories();
        $timeline = $this->portfolioService->getAllTimelineEntries();
        $inquiries = $this->portfolioService->getInquiries();
        $unread = count(array_filter($inquiries, static fn(array $i): bool => empty($i['is_read'])));

        return $this->render('index', [
            'stats' => [
                'projects' => count($projects),
                'categories' => count($categories),
                'timeline' => count($timeline),
                'inquiries' => count($inquiries),
                'unread' => $unread,
            ],
            'recentInquiries' => array_slice($inquiries, 0, 5),
        ]);
    }

    public function actionProjects(): string
    {
        return $this->render('projects', [
            'projects' => $this->portfolioService->getProjects(false),
        ]);
    }

    public function actionProjectCreate(): Response|string
    {
        $categories = $this->portfolioService->getCategories();
        $project = [
            'id' => null,
            'category_id' => $categories[0]['id'] ?? 1,
            'title' => '',
            'slug' => '',
            'location' => '',
            'year' => date('Y'),
            'summary' => '',
            'description' => '',
            'design_concept' => '',
            'materials_finishes' => '',
            'cover_image' => '',
            'aspect_ratio' => 'landscape',
            'is_featured' => 0,
            'is_sample' => 0,
            'sort_order' => 10,
            'status' => 1,
            'images' => [],
        ];

        if (Yii::$app->request->isPost) {
            return $this->handleProjectSave(null, $project);
        }

        return $this->render('project-form', [
            'project' => $project,
            'categories' => $categories,
        ]);
    }

    public function actionProjectUpdate(int $id): Response|string
    {
        $project = $this->portfolioService->getProjectById($id);
        if ($project === null) {
            throw new NotFoundHttpException('Project not found.');
        }

        if (Yii::$app->request->isPost) {
            return $this->handleProjectSave($id, $project);
        }

        return $this->render('project-form', [
            'project' => $project,
            'categories' => $this->portfolioService->getCategories(),
        ]);
    }

    private function handleProjectSave(?int $id, array $existing): Response
    {
        $post = Yii::$app->request->post('Project', []);
        $title = trim((string)($post['title'] ?? 'Untitled Project'));
        $slug = trim((string)($post['slug'] ?? ''));
        if ($slug === '') {
            $slug = Inflector::slug($title) . '-' . substr((string)time(), -4);
        }

        $coverImage = $existing['cover_image'] ?? '';
        $coverFile = UploadedFile::getInstanceByName('cover_file');
        if ($coverFile !== null) {
            $webpPath = $this->mediaUpload->uploadAsWebp($coverFile, 'uploads/projects');
            if ($webpPath !== null) {
                $coverImage = $webpPath;
            }
        }

        $images = $existing['images'] ?? [];
        foreach (UploadedFile::getInstancesByName('gallery_files') as $file) {
            $webpPath = $this->mediaUpload->uploadAsWebp($file, 'uploads/projects');
            if ($webpPath !== null) {
                $images[] = ['image_path' => $webpPath, 'caption' => $title];
            }
        }

        $this->portfolioService->saveProject($id, [
            'category_id' => (int)($post['category_id'] ?? 1),
            'title' => $title,
            'slug' => $slug,
            'location' => (string)($post['location'] ?? ''),
            'year' => (string)($post['year'] ?? date('Y')),
            'summary' => (string)($post['summary'] ?? ''),
            'description' => (string)($post['description'] ?? ''),
            'design_concept' => (string)($post['design_concept'] ?? ''),
            'materials_finishes' => (string)($post['materials_finishes'] ?? ''),
            'cover_image' => $coverImage,
            'aspect_ratio' => (string)($post['aspect_ratio'] ?? 'landscape'),
            'sort_order' => (int)($post['sort_order'] ?? 10),
            'is_featured' => isset($post['is_featured']) ? 1 : 0,
            'is_sample' => isset($post['is_sample']) ? 1 : 0,
            'status' => isset($post['status']) ? 1 : 0,
            'images' => $images,
        ]);

        Yii::$app->session->setFlash('success', 'Project saved to portfolio.');
        return $this->redirect(['projects']);
    }

    public function actionProjectDelete(int $id): Response
    {
        $this->portfolioService->deleteProject($id);
        Yii::$app->session->setFlash('success', 'Project deleted.');
        return $this->redirect(['projects']);
    }

    public function actionCategories(): Response|string
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Category', []);
            $name = trim((string)($post['name'] ?? ''));
            if ($name !== '') {
                $slug = trim((string)($post['slug'] ?? '')) ?: Inflector::slug($name);
                $this->portfolioService->addCategory($name, $slug, (int)($post['sort_order'] ?? 10));
                Yii::$app->session->setFlash('success', 'Category added.');
                return $this->refresh();
            }
        }

        return $this->render('categories', [
            'categories' => $this->portfolioService->getCategories(),
        ]);
    }

    public function actionCategoryDelete(int $id): Response
    {
        if ($this->portfolioService->deleteCategory($id)) {
            Yii::$app->session->setFlash('success', 'Category deleted.');
        } else {
            Yii::$app->session->setFlash('error', 'Cannot delete a category that still has projects.');
        }
        return $this->redirect(['categories']);
    }

    public function actionTimeline(): string
    {
        return $this->render('timeline', [
            'entries' => $this->portfolioService->getAllTimelineEntries(),
        ]);
    }

    public function actionTimelineUpdate(int $id): Response|string
    {
        $entry = $this->portfolioService->getTimelineEntryById($id);
        if ($entry === null) {
            throw new NotFoundHttpException('Timeline entry not found.');
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Timeline', []);
            $imagePath = $entry['image_path'] ?? '';
            $imageFile = UploadedFile::getInstanceByName('timeline_image');
            if ($imageFile !== null) {
                $webpPath = $this->mediaUpload->uploadAsWebp($imageFile, 'uploads/timeline');
                if ($webpPath !== null) {
                    $imagePath = $webpPath;
                }
            }

            $this->portfolioService->saveTimelineEntry($id, [
                'era_label' => (string)($post['era_label'] ?? $entry['era_label']),
                'title' => (string)($post['title'] ?? $entry['title']),
                'subtitle' => (string)($post['subtitle'] ?? ''),
                'description' => (string)($post['description'] ?? ''),
                'image_path' => $imagePath,
                'sort_order' => (int)($post['sort_order'] ?? 1),
                'is_active' => isset($post['is_active']) ? 1 : 0,
            ]);

            Yii::$app->session->setFlash('success', 'Timeline entry updated.');
            return $this->redirect(['timeline']);
        }

        return $this->render('timeline-form', ['entry' => $entry]);
    }

    public function actionSettings(): Response|string
    {
        if (Yii::$app->request->isPost) {
            $this->portfolioService->saveSettings(Yii::$app->request->post('Settings', []));
            Yii::$app->session->setFlash('success', 'Settings, contact details, and SEO metadata saved.');
            return $this->refresh();
        }

        return $this->render('settings', [
            'settings' => $this->portfolioService->getSettings(),
        ]);
    }

    public function actionInquiries(): string
    {
        return $this->render('inquiries', [
            'inquiries' => $this->portfolioService->getInquiries(),
        ]);
    }

    public function actionInquiryMarkRead(int $id): Response
    {
        $this->portfolioService->markInquiryRead($id);
        return $this->redirect(['inquiries']);
    }

    public function actionInquiryDelete(int $id): Response
    {
        $this->portfolioService->deleteInquiry($id);
        return $this->redirect(['inquiries']);
    }
}
