<?php

/* @var $this yii\web\View */
/* @var $model app\modules\catalogHeight\models\handler\ProductMaker */
/* @var $offer app\modules\catalogHeight\models\Offer */
/* @var $index int */
/* @var $form yii\widgets\ActiveForm */

use app\modules\catalogHeight\models\form\OfferTemplate;
?>

<div class="col-sm-4 mb-3 mb-md-0">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Вариант #<?= $index ?></h5>

            <?= $form
                ->field($offer, "[{$index}]id")
                ->hiddenInput(['value' => $offer->id ?? 0])
                ->label(false)
            ?>

            <?= $form
                ->field($offer, "[{$index}]product_id")
                ->hiddenInput(['value' => $model->getProduct()->id ?? 0])
                ->label(false)
            ?>

            <?= $form->field(
                    $offer,
                    "[{$index}]price",
                    ['template' => OfferTemplate::getInlineTemplate()]
                )->textInput(['maxlength' => true])
            ?>

            <?= $form->field($offer, "[{$index}]status", ['template' => OfferTemplate::getInlineTemplate()])
                ->dropDownList([
                \Yii::$app->params['statusDisabled'] => 'Заблокирован',
                \Yii::$app->params['statusActive'] => 'Активный'
            ]) ?>

            <?php
            $propertyValues = $offer->getProperties()->all();

            foreach ($model->getActiveProperties() as $key => $activeProperty) {
                $propertyValue = array_filter($propertyValues, function ($value) use ($offer, $activeProperty) {
                    return $value->offer_id == $offer->id && $value->property_id == $activeProperty->id;
                });

                if (empty($propertyValue)) {
                    $propertyValue = $model->getPropertyValue();
                    $propertyValue->property_id = $activeProperty->id;
                } else {
                    $propertyValue = reset($propertyValue);
                }

                print $form
                    ->field($propertyValue, "[{$index}][{$key}]id")
                    ->hiddenInput(['value' => $propertyValue->id ?? 0])
                    ->label(FALSE);

                print $form
                    ->field($propertyValue, "[{$index}][{$key}]property_id")
                    ->hiddenInput(['value' => $activeProperty->id])
                    ->label(false);

                print $form
                    ->field($propertyValue, "[{$index}][{$key}]offer_id")
                    ->hiddenInput(['value' => $offer->id ?? $index])
                    ->label(false);

                switch ($activeProperty->property_type_id) {
                    case 1:
                    case 2:
                    case 3: print $form
                        ->field(
                            $propertyValue,
                            "[{$index}][{$key}]value",
                            ['template' => OfferTemplate::getInlineTemplate()]
                        )
                        ->textInput(['maxlength' => true])
                        ->label($activeProperty->title);
                        break;
                    case 4: print $form
                        ->field(
                            $propertyValue,
                            "[{$index}][{$key}]value",
                            ['template' => OfferTemplate::getInlineTemplate()]
                        )
                        ->fileInput()
                        ->label($activeProperty->title);
                        break;
                    case 5:
                        $dataList = strtoupper($propertyValue->getProperty()->one()->alias);

                        print $form
                        ->field(
                            $propertyValue,
                            "[{$index}][{$key}]value",
                            ['template' => OfferTemplate::getInlineTemplate()]
                        )
                        ->dropDownList(constant("\app\modules\catalogHeight\models\PropertyValue::$dataList"))
                        ->label($activeProperty->title);
                        break;
                    case 6:
                        $dataList = strtoupper($propertyValue->getProperty()->one()->alias);

                        print $form
                        ->field(
                            $propertyValue,
                            "[{$index}][{$key}]value",
                            ['template' => OfferTemplate::getInlineTemplate()]
                        )
                        ->dropDownList(
                            constant("\app\modules\catalogHeight\models\PropertyValue::$dataList"),
                            ['multiple' => true]
                        )
                        ->label($activeProperty->title);
                        break;
                    default: 'def (';
                }
            }?>

            <div class="col-md-12 col-sm-8 mb-3 mb-md-0">
                <?= $form->field($offer, "[{$index}]images")->widget(
                    \kartik\file\FileInput::class,
                    [
                        'name'          => 'offer_photos[]',
                        'options'       => ['multiple' => true],
                        'pluginOptions' => [
                            'initialPreviewConfig' => $offer->getPicturesData(),
                            'initialPreview'       => \yii\helpers\ArrayHelper::getColumn(
                                $offer->getPictures()->all(),
                                'path'
                            ),
                            'initialPreviewAsData' => true,
                            'initialCaption'       => "Фото предложения",
                            'overwriteInitial'     => false,
                            'maxFileSize'          => 2800,
                            'showRemove'           => false,
                            'showUpload'           => false,
                        ],
                    ]
                ); ?>
            </div>

        </div>
    </div>
</div>
