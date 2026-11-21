<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\services\PortfolioDataService;
use yii\base\Security;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly Security $security,
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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'submit-contact' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Single-page luxury editorial portfolio for Nitesh Jangda — NJ Interiors.
     */
    public function actionIndex(): string
    {
        $this->layout = 'portfolio';

        return $this->render('index', [
            'settings' => $this->portfolioService->getSettings(),
            'timeline' => $this->portfolioService->getTimelineEntries(),
            'categories' => $this->portfolioService->getCategories(),
            'projects' => $this->portfolioService->getProjects(),
        ]);
    }

    /**
     * Returns project detail JSON for AJAX modal drawer or renders standalone page.
     */
    public function actionProject(string $slug): Response|string
    {
        $project = $this->portfolioService->getProjectBySlug($slug);
        if ($project === null) {
            throw new NotFoundHttpException('The requested project could not be found.');
        }

        if (Yii::$app->request->isAjax || Yii::$app->request->get('format') === 'json') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->asJson([
                'success' => true,
                'project' => $project,
            ]);
        }

        $this->layout = 'portfolio';
        return $this->render('project', [
            'project' => $project,
            'settings' => $this->portfolioService->getSettings(),
        ]);
    }

    /**
     * AJAX contact inquiry submission with CSRF & server-side validation.
     */
    public function actionSubmitContact(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = $this->portfolioService->saveContactSubmission(
            $post,
            Yii::$app->request->userIP
        );

        return $this->asJson($result);
    }

    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/index']);
        }

        $model = new LoginForm($this->security);
        if ($model->load($this->request->post()) && $model->login()) {
            return $this->redirect(['/admin/index']);
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
