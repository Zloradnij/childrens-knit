<?php

/* @var $this yii\web\View */
/* @var $model app\modules\catalogLow\models\handler\ProductMaker */
/* @var $index int */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-sm-6 mb-3 mb-md-0">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Вариант #<?= $index ?></h5>

            <?= $form
                ->field($model->getOffer(), "[{$index}]product_id")
                ->hiddenInput(['value' => $model->getProduct()->id ?? 0])
                ->label(false)
            ?>

            <?= $form->field($model->getOffer(), "[{$index}]price")->textInput(['maxlength' => true]) ?>

            <?= $form->field($model->getOffer(), "[{$index}]status")->dropDownList([
                \Yii::$app->params['statusDisabled'] => 'Заблокирован',
                \Yii::$app->params['statusActive'] => 'Активный'
            ]) ?>

            <?php
            foreach ($model->getActiveProperties() as $key => $activeProperty) {

                $propertyPath = "[offer_{$index}][property_{$activeProperty->id}]";
                $propertyKey = "{$index}" . "{$key}";

                print $form
                    ->field($model->getPropertyValue(), "[{$propertyKey}]property_id")
                    ->hiddenInput(['value' => $activeProperty->id])
                    ->label(false);

                print $form
                    ->field($model->getPropertyValue(), "[{$propertyKey}]offer_id")
                    ->hiddenInput(['value' => $model->getOffer()->id ?? $index])
                    ->label(false);

                switch ($activeProperty->property_type_id) {
                    case 1:
                    case 2: print $form
                        ->field($model->getPropertyValue(), "[{$propertyKey}]value")
                        ->textInput(['maxlength' => true])
                        ->label($activeProperty->title);
                        break;
                    default: 'def (';
                }

            }?>

        </div>
    </div>
</div>
