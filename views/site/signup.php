<?php
/* @var $this yii\web\View */
/* @var $form \yii\bootstrap4\ActiveForm */

/* @var $model \app\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('app', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid site-signup">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Заполните данные для регистрации:</p>

            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'username') ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="row">
                <hr/>
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 align-center">
                    <?= Html::a(Yii::t('app', 'Login'), ['/login'], ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 align-center">
                    <?= Html::a(Yii::t('app', 'Request Password'), ['/request-password-reset'], ['class' => 'btn btn-primary']) ?>
                </div>

            </div>
        </div>
    </div>
</div>
