<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\catalogLow\models\handler\ProductMaker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model->getProduct(), 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model->getProduct(), 'alias')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model->getProduct(), 'status')->dropDownList([
            \Yii::$app->params['statusDisabled'] => 'Заблокирован',
            \Yii::$app->params['statusActive'] => 'Активный'
    ]) ?>

    <div class="row offer-group">
        <div class="col-sm-12">
            <h5>Варианты товара</h5>
        </div>

        <?= $this->render('_form-offer', [
            'model' => $model,
            'index' => 1,
            'form'  => $form,
        ])
        ?>

        <?= $this->render('_form-offer', [
            'model' => $model,
            'index' => 2,
            'form'  => $form,
        ])
        ?>

        <?= $this->render('_form-offer', [
            'model' => $model,
            'index' => 3,
            'form'  => $form,
        ])
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
