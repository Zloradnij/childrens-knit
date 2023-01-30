<?php

namespace app\modules\basket\components;

use app\modules\basket\models\Order;
use yii\base\Component;

class BasketComponent extends Component
{
    private const DEFAULT_BUYER_ID = 2;
    private const DEFAULT_DELIVERY_DELTA = '+2 day';

    protected const RETAIL_PERCENT = 1.25;
    protected const WHOLESALE_COUNT = 100;

    private Order $order;

    public function init()
    {
        parent::init();
    }

    private function initOrder()
    {
        $order = $this->findOrder();

        if (empty($order)) {
            $this->createOrder();
        } else {
            $this->order = $order;
        }

        if ($this->order->delivery_date <= time()) {
            $this->order->delivery_date = (new \DateTime())->modify('+1 day')->getTimestamp();
            $this->order->save();
        }
    }

    private function findOrder()
    {
        $orderByUserId = Order::find()->findByUserId()->active()->one();

        if ($orderByUserId) {
            return $orderByUserId;
        }

        return Order::find()->findBySession()->active()->one();
    }

    public function selectOrder()
    {
        /** Если покупатель уже накидал товаров в неавторизованную корзину, то и и нахрен старую */
        if ($this->order->getItems()->count()) {
            $this->order->user_id = $this->order->created_user = $this->order->updated_user = \Yii::$app->user->id;
            $this->order->save();

            return;
        }

        $orderByUserId = Order::find()->findByUserId()->active()->one();

        /** Если не нашли авторизованную корзину, то обновляем текущую */
        if (!$orderByUserId) {
            $this->order->session_id = \Yii::$app->session->getId();
            $this->order->user_id = $this->order->created_user = $this->order->updated_user = \Yii::$app->user->id;
            $this->order->save();

            return;
        }

        /** Если нашли авторизованную корзину и текущая пуста, то текущую убъём нафиг */
        $this->order->delete();
        $this->order = $orderByUserId;
    }

    private function createOrder()
    {
        $this->order = new Order();
        $this->order->session_id = \Yii::$app->session->getId();
        $this->order->user_id = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
        $this->order->created_user = \Yii::$app->user->isGuest ? static::DEFAULT_BUYER_ID : \Yii::$app->user->id;
        $this->order->updated_user = \Yii::$app->user->isGuest ? static::DEFAULT_BUYER_ID : \Yii::$app->user->id;
        $this->order->delivery_date = (new \DateTime())->modify(static::DEFAULT_DELIVERY_DELTA)->getTimestamp();
        $this->order->status = Order::STATUS_NEW;

        $this->order->save();
    }

    public function getDefaultBuyerId()
    {
        return static::DEFAULT_BUYER_ID;
    }

    public function getOrder(): Order
    {
        if (!empty($this->order)) {
            return $this->order;
        }

        $this->initOrder();

        return $this->order;
    }

    public function getRetailPercent()
    {
        return static::RETAIL_PERCENT;
    }

    public function getWholesaleCount()
    {
        return static::WHOLESALE_COUNT;
    }
}
