<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProfileForm */

$this->title = 'All Users List';
$usersDataProvider = new ActiveDataProvider([
	'query' => User::find()->where('id != :currentUser', ['currentUser' => Yii::$app->user->identity->getId()]),
	'pagination' => [
		'pageSize' => 20,
	],
]);

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-all-users-list">
	<h1><?= Html::encode($this->title) ?></h1>


	<?php if (Yii::$app->session->hasFlash('contactListUpdateSuccessfull')): ?>

		<div class="alert alert-success">
			Contact list was updated
		</div>

	<?php elseif (Yii::$app->session->hasFlash('contactListUpdateFailure')): ?>

		<div class="alert alert-warning">
			error occured. is user already in contact list?
		</div>

	<?php endif; ?>


	<?= GridView::widget([
		'dataProvider' => $usersDataProvider,
		'id' => 'usersList',
		'columns' => [
			'name',
			'status',
			[
				'class' => 'yii\grid\DataColumn',
				'value' => function ($data) {
						return $data->isOnline()?'online':'offline';
					},
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{add-to-contact-list}',
				'buttons' => [
					'add-to-contact-list' => function ($url, $model) {
							return Html::a('Add to contact list',$url);
						},
					],
					'urlCreator' => function ($action, $model, $key, $index) {
						return Url::to([$action, 'id' => $model->id]);
					}
			]
		],
	]);?>
</div>
