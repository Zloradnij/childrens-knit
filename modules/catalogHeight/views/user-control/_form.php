<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\UserShort */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-short-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->widget(\kartik\select2\Select2::classname(), [
        'data' => ArrayHelper::map($roles,'name','description'),
        'options' => [
            'multiple' => true,
            'placeholder' => 'Выберите роли',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($profile, 'name')->textInput() ?>
    <?= $form->field($profile, 'second_name')->textInput() ?>
    <?= $form->field($profile, 'patronymic')->textInput() ?>
    <?= $form->field($profile, 'myclass')->textInput() ?>

    <?= $form->field($profile, 'school_id')->dropDownList(
        ArrayHelper::map(\app\models\School::find()->all(),'id','title')
    )?>
    <?= $form->field($profile, 'class_id')->dropDownList(
        ArrayHelper::map(\app\models\SchoolClass::find()->where(['active' => 10])->all(),'id','class')
    )?>

    <?php
    if(Yii::$app->user->can('teacher')){
        $schoolClassList = \app\models\SchoolClass::find()->where(['school_id' => $profile->school_id])->all();
        foreach($teacherClasses as $i => $class){
            if(!isset($class->id)){
                print '
                <hr />
                <label>Добавить</label>';
                $schoolClassList = array_merge(['id' => false,'class' => false],$schoolClassList);
            }
            ?><?= $form->field($class, "[$i]class_id")->dropDownList(
                ArrayHelper::map($schoolClassList,'id','class')
            )->label('Классы учителя')?><?php
        }
    }
    ?>

    <?php
    /*
    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
    */
    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('user-short', 'Create') : Yii::t('user-short', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
