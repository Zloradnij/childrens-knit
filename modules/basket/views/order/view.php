<?php

use app\modules\catalogHeight\models\PropertyValue;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\basket\models\Order;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model \app\modules\basket\models\Order */

$this->title = "Заказ # {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['/my-orders']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-lg-12 table-responsive mb-12">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?php Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php Html::a(
                    'Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data'  => [
                        'confirm' => 'Уверены, что хотите удалить заказ?',
                        'method'  => 'post',
                    ],
                ]
                ) ?>
            </p>

            <?= DetailView::widget(
                [
                    'model'      => $model,
                    'attributes' => [
                        [
                            'attribute' => 'pay_type_id',
                            'format'    => 'raw',
                            'label'     => 'Оплата',
                            'value'     => function ($data) {
                                return "<b style='color: green'>{$data->price} &#8381; </b>" . Order::PAY_TYPES[$data->pay_type_id];
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
                        [
                            'attribute' => 'delivery_price',
                            'format'    => 'raw',
                            'value'     => function ($data) {
                                return "<b style='color: green'>{$data->delivery_price} &#8381; </b>";
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
                    ],
                ]
            );

            $propertyValues = PropertyValue::VALUE_LIST;

            $list = "
            <table class=\"table table-light table-borderless table-hover text-center mb-0\">
                <thead class=\"thead-dark\">
                <tr>
                    <th></th>
                    <th>Товар (Размер / Материал)</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Всего</th>
                </tr>
                </thead>
                <tbody class=\"align-middle\">
            ";

            /** @var \app\modules\basket\models\OrderItem $item */
            foreach ($model->getItems()->all() as $item) {
                /** @var \app\modules\catalogHeight\models\Offer $offer */
                $offer = $item->getOffer()->one();
                /** @var \app\modules\catalogHeight\models\Product $product */
                $product = $offer->getProduct()->one();
                $propertyVisible = [];

                foreach ($offer->getProperties()->all() as $property) {
                    $alias = $property->getProperty()->one()->alias;

                    $propertyVisible[] = $propertyValues[$alias][$property->value];
                }

                $propertyVisible = implode(' / ', $propertyVisible);

                $list .= "
                    
                    <tr
                        class=\"product-item\"
                        id=\"product-item-{$product->id}\"
                        data-product-id=\"{$product->id}\"
                        data-offer-id=\"{$offer->id}\"
                        data-retail-price=\"{$offer->retail_price}\"
                        data-wholesale-price=\"{$offer->wholesale_price}\"
                        data-wholesale-count=\"{$offer->wholesale_count}\"
                        data-offer-price=\"{$item->getResultPrice()}\"
                        data-offer-count=\"{$item->count}\"
                        data-offer-active=\"{$offer->id}\"
                        data-offer-in-basket=\"1\"
                    >
                        <td class=\"align-middle\">
                            <img src=\"{$product->getImages()[0]}\" alt=\"\" style=\"width: 50px;\"/>
                        </td>
                        <td class=\"align-middle\">
                            {$product->title} ({$propertyVisible})
                        </td>
                        <td class=\"align-middle\">
                            <span class=\"product-price\">{$item->getRealPrice()}</span> &#8381;
                        </td>
                        <td class=\"align-middle\">
                            {$item->count}
                        </td>
                        <td class=\"align-middle\">
                            <span class=\"product-result-price\">{$item->getResultPrice()}</span> &#8381;
                        </td>
                    </tr>
                    ";
            }

            $list .= "
                </tbody>
            </table>
            ";

            print $list;
            ?>

        </div>
    </div>
</div>
