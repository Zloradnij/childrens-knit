<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Генератор csv-файла для загрузки учеников в Moodle';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Fantastic Pages'),'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="load-more-users-page">
    <p>
        Пример:<br /><br />
        Школа 1; school1@mail.ru<br />
        Школа 2; school1@mail.ru<br />
        Школа 3; school1@mail.ru<br />
        Школа 4; school1@mail.ru<br />
    </p>
    <hr />
    <br />

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if(!empty($errorString)){
        ?><div class="error">Ошибки в строках - <?=implode(',',$errorString)?></div><?php
    }

    if(!$link){

    }else{
        print '
        <a href="' . $link . '">Скачать csv файл</a>';
    }
    ?>

    <textarea name="users-list" style="width:100%;height:500px;"><?= $list?></textarea>

    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Generate Start')) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
