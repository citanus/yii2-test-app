<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\User;

/* @var $this yii\web\View */
$this->title = 'My Yii Application';

$userDataProvider = new ActiveDataProvider([
	'query' => User::find()->where(sprintf('id != %d', Yii::$app->user->identity->getId())),
	'pagination' => [
		'pageSize' => 20,
	],
]);


/** register simple javascript which will periodicaly:
 *  - update online status for current user
 *  - update user list
 *  - update user informatins
 **/
$this->registerJs('function updateMessenger() {
	$.pjax.reload({container:"#usersList"});
	$.get("'.Url::to(['update-online-status']).'");
}
setInterval(updateMessenger,5000);
', View::POS_READY);

?>
<div class="site-index">

    <div class="body-content">
        <div class="row">
            <div class="col-lg-2">
                <h2>User list</h2>

	            <?php \yii\widgets\Pjax::begin(); ?>
	            <?= GridView::widget([
		            'dataProvider' => $userDataProvider,
		            'id' => 'usersList',
		            'columns' => [
			            'name',
			            'status',
			            [
				            'class' => 'yii\grid\DataColumn', // can be omitted, default
			                'value' => function ($data) {
					            return $data->isOnline()?'online':'offline';
				            },
				        ],
		            ],
	            ]);?>
	            <?php \yii\widgets\Pjax::end(); ?>


            </div>
            <div class="col-lg-4">
                <h2>Messages</h2>




            </div>
        </div-->

    </div>
</div>
