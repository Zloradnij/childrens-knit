<?php

namespace app\modules\basket\models;

use app\modules\catalogHeight\models\PropertyValue;

/**
 * This is the model class for table "order_item".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $offer_id
 * @property integer $count
 * @property integer $sale_type_id
 * @property string $promo
 * @property float $sale
 *
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class OrderItemList
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function showOrderItems()
    {
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
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody class=\"align-middle\">
        ";

                /** @var \app\modules\basket\models\OrderItem $item */
                foreach ($this->order->getItems()->all() as $item) {
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

            $list .="
                </tbody>
            </table>
            ";
    }
}
