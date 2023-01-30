<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\basket\models\Order;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\basket\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-my-order container-fluid">
    <div class="row px-xl-5">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?php Pjax::begin(); ?>    <?= GridView::widget([
                'layout'=> "{summary}\n{items}\n{pager}",
                'summary' => "Показано {begin} - {end} из {totalCount} заказов",
                'emptyText' => 'Заказов не найдено. Измените параметры фильтра',
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns'      => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'pay_type_id',
                        'filter'    => Order::PAY_TYPES,
                        'format'    => 'raw',
                        'value'     => function ($data) {
                            return Order::PAY_TYPES[$data->pay_type_id];
                        },
                    ],
                    [
                        'attribute' => 'delivery_id',
                        'filter'    => Order::DELIVERY_TYPES,
                        'format'    => 'raw',
                        'value'     => function ($data) {
                            return Order::DELIVERY_TYPES[$data->delivery_id];
                        },
                    ],
                    [
                        'attribute' => 'delivery_date',
                        'value'     => function ($data) {
                            return date('Y-m-d', $data->created_at);
                        },
                    ],
//                    'sale_type_id',
                    [
                        'attribute' => 'status',
                        'filter'    => Order::ORDER_STATUSES_FROM_BASKET,
                        'format'    => 'raw',
                        'value'     => function ($data) {
                            return Order::ORDER_STATUSES_FROM_BASKET[$data->status];
                        },
                    ],
    //                [
    //                    'attribute' => 'created_at',
    //                    'value'     => function ($data) {
    //                        return date('Y-m-d', $data->created_at);
    //                    },
    //                ],
                    [
                        'attribute' => 'updated_at',
                        'label'     => 'Последнее изменение',
                        'value'     => function ($data) {
                            return date('Y-m-d', $data->updated_at);
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons'=>[
                            'view'=>function ($url, $model) {
                                return Html::a(
                                    'Подробнее',
                                    $url,
                                    ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']
                                );
                            }
                        ],
                    ],
                ],
            ]
        ); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
