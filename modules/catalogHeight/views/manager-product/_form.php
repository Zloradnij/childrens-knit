<?php

use app\modules\catalogHeight\models\form\OfferTemplate;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\catalogHeight\models\handler\ProductMaker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3 col-sm-4 mb-3 mb-md-0">

            <?= $form
                ->field($model->getProduct(), 'title', ['template' => OfferTemplate::getInlineTemplate()])
                ->textInput(['maxlength' => true]) ?>

            <?= $form
                ->field($model->getProduct(), 'status', ['template' => OfferTemplate::getInlineTemplate()])
                ->dropDownList([
                    \Yii::$app->params['statusDisabled'] => 'Заблокирован',
                    \Yii::$app->params['statusActive']   => 'Активный',
                ]) ?>
        </div>

        <div class="col-md-9 col-sm-8 mb-3 mb-md-0">
            <?= $form->field($model->getProduct(), 'images')->widget(
                \kartik\file\FileInput::class,
                [
                    'name'          => 'product_photos[]',
                    'options'       => ['multiple' => true],
                    'pluginOptions' => [
                        'initialPreviewConfig'=> $model->getProduct()->getPicturesData(),
                        'initialPreview'       => \yii\helpers\ArrayHelper::getColumn(
                            $model->getProduct()->getPictures()->all(),
                            'path'
                        ),
                        'initialPreviewAsData' => true,
                        'initialCaption'       => "Фото товара",
                        'overwriteInitial'     => false,
                        'maxFileSize'          => 2800,
                        'showRemove'           => false,
                        'showUpload'           => false,
                    ],
                ]
            ); ?>
        </div>
    </div>

    <div class="row offer-group">
        <div class="col-sm-12">
            <h5>Варианты товара</h5>
        </div>

        <?php
        foreach ($model->getOffers() as $index => $offer) {
            print $this->render('_form-offer', [
                'model' => $model,
                'offer' => $offer,
                'index' => $index + 1,
                'form'  => $form,
            ]);
        }
        ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton(
                $model->getProduct()->isNewRecord ? 'Добавить' : 'Изменить',
                ['class' => $model->getProduct()->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
