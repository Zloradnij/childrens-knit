<?php

use app\modules\catalogHeight\models\form\OfferTemplate;
use app\modules\basket\models\Order;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\basket\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form
        ->field($model, 'user_id', ['template' => OfferTemplate::getInlineTemplate()])
        ->textInput(['maxlength' => true]) ?>

    <?= $form->field(
        $model,
        "pay_type_id",
        ['template' => OfferTemplate::getInlineTemplate()]
    )
        ->dropDownList(Order::PAY_TYPES, ['multiple' => false]) ?>

    <?= $form->field(
        $model,
        "delivery_id",
        ['template' => OfferTemplate::getInlineTemplate()]
    )
        ->dropDownList(Order::DELIVERY_TYPES, ['multiple' => false]) ?>

    <?= $form->field(
        $model,
        "delivery_date",
        ['template' => OfferTemplate::getInlineTemplate()]
    ) ?>

    <?= $form->field(
        $model,
        "sale_type_id",
        ['template' => OfferTemplate::getInlineTemplate()]
    )
        ->dropDownList(Order::SALE_TYPES, ['multiple' => false]) ?>

    <?= $form->field(
        $model,
        "sale",
        ['template' => OfferTemplate::getInlineTemplate()]
    ) ?>

    <?= $form
        ->field($model, 'status', ['template' => OfferTemplate::getInlineTemplate()])
        ->dropDownList([
            \Yii::$app->params['statusActive']   => 'Новый',
        ]) ?>

    <?= $form
        ->field($model, 'comment', ['template' => OfferTemplate::getInlineTemplate()])
        ->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton(
                $model->isNewRecord ? 'Добавить' : 'Изменить',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
