<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\basket\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\basket\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'user_id',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        $user = User::findOne($data->user_id);
                        $userData = !empty($user->phone)
                            ? ($user->username ?? '') . " (" . ($user->phone ?? '') . ")"
                            : 'Аноним';

                        return $userData;
                    },
                ],
                [
                    'attribute' => 'pay_type_id',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        return $data->price . " " . Order::PAY_TYPES[$data->pay_type_id];
                    },
                ],
                [
                    'attribute' => 'delivery_id',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        return Order::DELIVERY_TYPES[$data->delivery_id] . "<br />$data->delivery_address";
                    },
                ],
                [
                    'attribute' => 'delivery_date',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        return date('Y-m-d', $data->delivery_date);
                    },
                ],
//                'sale_type_id',
                [
                    'attribute' => 'status',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        return Order::ORDER_STATUSES[$data->status];
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'label'     => 'Создан / Обновлён',
                    'value'     => function ($data) {
                        return date('Y-m-d', $data->created_at) . " / " . date('Y-m-d', $data->updated_at);
                    },
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?></div>
