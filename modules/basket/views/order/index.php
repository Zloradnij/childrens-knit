<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\basket\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'pay_type_id',
            'delivery_id',
            'delivery_date',
            'sale_type_id',
            [
                'attribute' => 'status',
                'format'    => 'raw',
                'value'     => function ($data) {
                    return $data->status === \Yii::$app->params['statusActive']
                        ? '<i class="btn btn-success fa fa-flag-o fa-lg"></i>'
                        : '<i class="btn btn-danger fa fa-trash-o fa-lg"></i>';
                },
            ],
            [
                'attribute' => 'created_at',
                'value'     => function ($data) {
                    return date('Y-m-d', $data->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value'     => function ($data) {
                    return date('Y-m-d', $data->updated_at);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]
    ); ?>
    <?php Pjax::end(); ?></div>
