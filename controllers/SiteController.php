<?php

namespace app\controllers;

use app\models\OnlineStatus;
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
                'only' => ['registration', 'login', 'logout', 'profile', 'messages', 'overview', 'user-list', 'update-online-status'],
                'rules' => [
	                [
		                'actions' => ['registration', 'login'],
		                'allow' => true,
		                'roles' => ['?'],
	                ],
                    [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
	                [
		                'actions' => ['messages', 'overview', 'user-list', 'update-online-status'],
		                'allow' => true,
		                'roles' => ['@'],
	                ]
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
        ];
    }

    public function actionIndex()
    {
	    if (Yii::$app->user->isGuest) {
	        return $this->render('index');
	    } else {
		    return $this->redirect(['site/messenger']);
	    }
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

	/**
	 * render messenger layout with all ajax actions
	 *
	 * @return string
	 */
	public function actionMessenger() {
		OnlineStatus::updateStatus(Yii::$app->user->identity);
		return $this->render('messenger');
	}

	public function actionGetUserList() {

	}

	public function actionUpdateOnlineStatus() {
		return OnlineStatus::updateStatus(Yii::$app->user->identity);
	}

	public function actionGetMessages() {

	}
}
