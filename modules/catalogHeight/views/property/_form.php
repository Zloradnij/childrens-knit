<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\catalogHeight\models\Property */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-short-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList([
            \Yii::$app->params['statusDisabled'] => 'Заблокирован',
            \Yii::$app->params['statusActive'] => 'Активный'
    ]) ?>

    <?= $form->field($model, 'property_type_id')->dropDownList(
        $model::PROPERTY_TYPE_IDS
    )?>

    <div class="form-group">
        <?= Html::submitButton(
                $model->isNewRecord ? 'Добавить' : 'Изменить',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
