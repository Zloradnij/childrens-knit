<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\catalogLow\models\Property */

$this->title = "# {$model->id} {$model->title}";
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Уверены, что хотите удалить товар?',
                'method'  => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'alias',
            [
                'attribute' => 'status',
                'format'    => 'raw',
                'value' => $model->status === \Yii::$app->params['statusActive']
                    ? '<i title="Активный" class="btn btn-success fa fa-flag-o fa-lg"></i>'
                    : '<i title="Отключен" class="btn btn-danger fa fa-trash-o fa-lg"></i>',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d',$model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d',$model->updated_at),
            ],
            [
                'attribute' => 'created_user',
                'value' => function($model){
                    return User::find()->where(['id' => $model->created_user])->select('username')->scalar();
                }
            ],
            [
                'attribute' => 'updated_user',
                'value' => function($model){
                    return User::find()->where(['id' => $model->created_user])->select('username')->scalar();
                }
            ],
        ],
    ]) ?>
</div>
