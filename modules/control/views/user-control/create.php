<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserShort */

$this->title = Yii::t('user-short', 'Create User Short');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user-short', 'User Shorts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-short-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <h1>AHTUNG !!!</h1>

    <?php
    /*= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ])*/
     ?>

</div>
