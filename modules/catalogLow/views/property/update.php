<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\catalogLow\models\Property */

$this->title = 'Изменить свойство';
$this->params['breadcrumbs'][] = ['label' => 'Свойства товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-short-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
