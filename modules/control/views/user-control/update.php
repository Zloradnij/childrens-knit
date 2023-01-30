<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserShort */

$this->title = Yii::t('user-short', 'Update {modelClass}: ', [
    'modelClass' => 'User Short',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user-short', 'User Shorts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user-short', 'Update');
?>
<div class="user-short-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'profile' => $profile,
        'dataProviderOlympiad' => $dataProviderOlympiad,
        'searchModelOlympiad' => $searchModelOlympiad,
        'teacherClasses' => $teacherClasses,
    ]) ?>

</div>
