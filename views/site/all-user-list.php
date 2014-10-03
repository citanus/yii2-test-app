<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProfileForm */

$this->title = 'All Users List';
$userDataProvider = new ActiveDataProvider([
	'query' => User::find()->where(sprintf('id != %d', Yii::$app->user->identity->getId())),
	'pagination' => [
		'pageSize' => 20,
	],
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-all-users-list">
	<h1><?= Html::encode($this->title) ?></h1>

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
</div>
