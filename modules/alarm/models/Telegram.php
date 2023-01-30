<?php

namespace app\modules\alarm\models;

use app\models\User;
use app\modules\basket\models\Order;
use app\modules\catalogHeight\models\PropertyValue;
use yii\httpclient\Client;

/**
 * @property integer $id
 * @property integer $user_id
 * @property float $price
 * @property integer $pay_type_id
 * @property integer $delivery_id
 * @property string $delivery_address
 * @property integer $delivery_date
 * @property float $delivery_price
 * @property string $promo
 * @property float $sale
 * @property integer $sale_type_id
 * @property string $comment
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 * @property string $session_id
 */
class Telegram
{
    private array $replaceFileds = [
        '#ORDER_NUMBER#',
        '#CLIENT_NUMBER#',
        '#ORDER_PRICE#',
        '#PRODUCT_PRICE#',
        '#DELIVERY_PRICE#',
        '#DELIVERY_ADDRESS#',
        '#DELIVERY_TYPE#',
        '#ORDER_DATE#',
        '#ORDER_COMMENT#',
        '#ORDER_ITEMS#',
    ];

    private array $replaceData = [];

    public function __construct(Order $order)
    {
        $propertyValues = [
            'size'     => PropertyValue::SIZE,
            'material' => PropertyValue::MATERIAL,
        ];

        $orderItems = '';

        foreach ($order->getItems()->all() as $item) {
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

            $orderItems .= "{$product->title} ({$propertyVisible}) Количество - {$item->count} шт. \n";
        }

        $user = User::findOne($order->user_id);

        $this->replaceData = [
            "<a href='https://childrens-knit.ru/control/order/view?id={$order->id}'>{$order->id}</a>",
            "{$user->phone}" . (empty($user->username) ? '' : " (Клиент просит обращаться к нему -{$user->username})"),
            $order->price,
            $order->price - $order->delivery_price,
            $order->delivery_price ?? 0,
            $order->delivery_address ?? '',
            Order::DELIVERY_TYPES[$order->delivery_id],
            date('d-m-Y H:i', $order->updated_at),
            $order->comment,
            $orderItems,
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function send()
    {
        $telegramParams = \Yii::$app->params['alarmMethods']['telegram'];

        foreach ($telegramParams['params']['chat_id'] as $chatId) {
            $requestParams = $telegramParams['params'];
            $requestParams['chat_id'] = $chatId;

            $requestParams['text'] = str_replace(
                $this->replaceFileds,
                $this->replaceData,
                $requestParams['text']
            );

            $client = new Client();
            $client->createRequest()
                ->setMethod('POST')
                ->setUrl($telegramParams['tgBotUrl'])
                ->setData($requestParams)
                ->send();
        }
    }
}
