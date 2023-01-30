<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\control\models\LogsVisit */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Logs Visit',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs Visits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="logs-visit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
