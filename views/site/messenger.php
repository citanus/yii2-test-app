<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'Messenger';


$userDataProvider = new ActiveDataProvider([
	'query' => Yii::$app->user->identity->getContacts()
]);




/** register simple javascript which will periodicaly:
 *  - update online status for current user
 *  - update user list
 *  - update user informatins
 * @todo integrate with pjax?
 **/
$this->registerJs('function updateMessenger() {
	$.get("'.Url::to(['update-online-status']).'");
}
setInterval(function(){$.pjax.reload({container:"#contactList"});}, 30000);
setInterval(updateMessenger,5000);
', View::POS_READY);


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="body-content">
	    <?php if (Yii::$app->session->hasFlash('messageSendSuccesfull')): ?>

		    <div class="alert alert-success">
			    Message has been sent
		    </div>

	    <?php elseif (Yii::$app->session->hasFlash('messageSendFailure')): ?>

		    <div class="alert alert-warning">
			    Some error occured!!
		    </div>

	    <?php endif; ?>

        <div class="row">
	        <div class="col-lg-2">
		        <ul>
		            <li><?= Html::a('All users', ['all-users-list'])?></li>
			    </ul>
		    </div>
	        <div class="col-lg-2">
		        <h3>Contact list</h3>
		        <?php //\yii\widgets\Pjax::begin(['id' => 'contactList']); ?>
		        <?= GridView::widget([
			        'dataProvider' => $userDataProvider,
			        'columns' => [
				        'name',
				        //'status',
				        [
					        'class' => 'yii\grid\DataColumn', // can be omitted, default
					        'value' => function ($data) {
							        return $data->isOnline()?'online':'offline';
						        },
				        ],

				        [
					        'class' => 'yii\grid\ActionColumn',
					        'template' => '{send-message}',
					        'buttons' => [
						        'send-message' => function ($url, $model) {
								        return Html::a('View/Send Message',$url);
							        },
					        ],
					        'urlCreator' => function ($action, $model, $key, $index) {
							        return Url::to(['', 'recipient' => $model->id]);
						        }
				        ]
			        ],
		        ]);?>
		        <?php //\yii\widgets\Pjax::end(); ?>
	        </div>
	        <?php if ($displayChat): ?>
            <div class="col-lg-2">
                <h2>Messages</h2>
	            <?php
	            $messagesDataProvider = new ActiveDataProvider([
		            'query' => Yii::$app->user->identity->getChatWithContact($recipient),
		            'sort' => [
			            'defaultOrder' => [
		                    'date_posted' => SORT_DESC,
	                    ],
			        ],
	            ]);
	            ?>
	            <?=GridView::widget([
		            'dataProvider' => $messagesDataProvider,
		            'columns' => [
			            'date_posted',
			            [
				            'class' => 'yii\grid\DataColumn',
				            'value' => function ($data) {
						            return $data->getAuthor()->one()->name .'......'.$data->getRecipient()->one()->name;

					            },
			            ],
			            'message'

		            ],
	            ]);?>


            </div>

            <div class="col-lg-4">
                <h2>New Message to: <?=$recipient->name;?></h2>
	            <?php

	            $form = ActiveForm::begin(['id' => 'message-form', 'action' => Url::to(['send-message'])]); ?>
	            <?= $form->field($messageModel, 'message') ?>
	            <?= $form->field($messageModel, 'recipient')->input('hidden') ?>
	            <div class="form-group">
		            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'profile-button']) ?>
	            </div>
	            <?php ActiveForm::end(); ?>

            </div>
			<?php endif ?>
        </div-->

    </div>
</div>
