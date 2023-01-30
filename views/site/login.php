<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\PhoneForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid site-login">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <h1><?= Html::encode($this->title) ?></h1>

            <p></p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-2 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '+7 999-999-99-99',
            ]) ?>

            <?= $model->withCode ? $form->field($model, 'verificationCode') : '' ?>

            <?= $model->withCode
                ? $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"offset-lg-2 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                ])
                : '' ?>

            <div class="form-group">
                <div class="offset-lg-2 col-lg-10">
                    <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
