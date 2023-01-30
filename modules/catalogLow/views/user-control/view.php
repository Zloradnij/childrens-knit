<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\UserShort */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user-short', 'User Shorts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-short-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('user-short', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user-short', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('user-short', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'role',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            'status',
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d',$model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d',$model->updated_at),
            ],
        ],
    ]) ?>
</div>
