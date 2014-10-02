<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrationForm */

$this->title = 'Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-registration">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('registrationFormSubmitted')): ?>

    <div class="alert alert-success">
        Thank you for registration..
    </div>

    <?php else: ?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'registration-form']); ?>
                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password') ?>
	            <?= $form->field($model, 'passwordVerify') ?>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'registration-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php endif; ?>
</div>
