<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\control\models\LogsVisit */

$this->title = Yii::t('app', 'Create Logs Visit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs Visits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-visit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
