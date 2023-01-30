<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\catalogHeight\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList([
        \Yii::$app->params['statusDisabled'] => 'Заблокирован',
        \Yii::$app->params['statusActive'] => 'Активный'
    ]) ?>

    <div class="row offer-group">
        <div class="col-sm-12">
            <h5>Варианты товара</h5>
        </div>

        <?php
        foreach ($model->getOffers() as $offer) {
            print $this->render('_form-offer', [
                'model' => $model,
                'index' => $model->id,
                'form'  => $form,
            ]);
        }
        ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Добавить' : 'Изменить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
