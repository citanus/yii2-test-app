<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ProfileForm;
use app\models\RegistrationForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
	                [
		                'actions' => ['profile'],
		                'allow' => true,
		                'roles' => ['@'],
	                ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	public function actionRegistration()
	{
		$model = new RegistrationForm();

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);

		} elseif ($model->load(Yii::$app->request->post()) && $model->register()) {
			Yii::$app->session->setFlash('registrationFormSubmitted');
			return $this->refresh();

		} else {
			return $this->render('registration', [
				'model' => $model,
			]);
		}
	}

	public function actionProfile()
	{

		$model = new ProfileForm();

		if ($model->load(Yii::$app->request->post()) && $model->update()) {
			Yii::$app->session->setFlash('profileFormSubmitted');
			return $this->refresh();

		} else {
			$model->status = Yii::$app->user->identity->status;
			return $this->render('profile', [
				'model' => $model,
			]);
		}
	}
}
