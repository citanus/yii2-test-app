<?php

namespace app\controllers;

use app\models\Message;
use app\models\OnlineStatus;
use app\models\User;
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
                'only' => ['registration', 'login', 'logout', 'profile', 'messages', 'send-message', 'overview', 'all-users-list', 'add-to-contact-list', 'update-online-status'],
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
		                'actions' => ['messages', 'overview', 'all-users-list', 'send-message', 'add-to-contact-list', 'update-online-status'],
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
	 * - display messenger layout with all ajax actions
	 * - display messages and new message form for current recipient
	 * - refresh online status if is javascript is not working
	 *
	 * @params $id null|int recipent id
	 * @return string
	 */
	public function actionMessenger($recipient = null) {
		OnlineStatus::updateStatus(Yii::$app->user->identity);
		$recipient = \app\models\User::findOne($recipient);

		if($recipient !== null) {
			$messageModel = new \app\models\NewMessageForm();
			$messageModel->recipient = $recipient->getId();
			return $this->render('messenger', ['displayChat'=> true,'recipient' => $recipient, 'messageModel'=> $messageModel]);
		}

		return $this->render('messenger', ['displayChat'=> false, 'recipient' => null]);
	}

	/**
	 * update online status for logged user. Periodicaly called via ajax
	 *
	 * @return void
	 */
	public function actionUpdateOnlineStatus() {
		OnlineStatus::updateStatus(Yii::$app->user->identity);
	}

	/**
	 * display all registered users with action buttons(profile, add to contact list)
	 *
	 * @return string
	 */
	public function actionAllUsersList() {
		return $this->render('all-user-list');
	}

	/**
	 * add requested contact to contact list and redirect back to actionAllUsersList()

	 * @todo better error handle
	 * @param $id new contact id
	 */
	public function actionAddToContactList($id) {
		$newContact = \app\models\User::findOne($id);
		if($newContact !== null && Yii::$app->user->identity->addUserToContactList($newContact)) {
			Yii::$app->session->setFlash('contactListUpdateSuccessfull');
		} else {
			Yii::$app->session->setFlash('contactListUpdateFailure');
		}
		$this->redirect(['site/all-users-list']);
	}

	/**
	 * check if recipient is in contact list and queue message for him
	 *
	 * @todo better error handle
	 */
	public function actionSendMessage() {
		$messageModel = new \app\models\NewMessageForm();
		if ($messageModel->load(Yii::$app->request->post())) {
			$recipient = User::findOne($messageModel->recipient);
			var_dump($messageModel->message);
			Message::sendMessage(Yii::$app->user->identity, $recipient, $messageModel->message);
			Yii::$app->session->setFlash('messageSendSuccesfull');
			$this->redirect(['site/messenger', 'recipient' => $messageModel->recipient]);
		} else {
			Yii::$app->session->setFlash('messageSendFailure');
			$this->redirect(['site/messenger', 'recipient' => $messageModel->recipient]);
		}
	}
}
