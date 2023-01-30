<?php

namespace app\modules\basket\models\handler;

use app\modules\basket\models\Order;
use app\modules\basket\models\OrderItem;

class OrderMaker
{
    private const DEFAULT_BUYER_ID = 2;

    public const SUCCESS_PARAMS = [
        'delivery_address',
        'delivery_id',
        'delivery_price',
    ];

    /** @var Order */
    private $order;

    /** @var OrderItem */
    private $orderItem;

    /** @var OrderItem[] */
    private $orderItems;

    /**
     * @param OrderItem $orderItem
     */
    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;

        $this->order = \Yii::$app->basket->getOrder();
    }

    public function setOrderItem()
    {
        $this->orderItems[] = $this->orderItem;
    }

    /**
     * @return OrderItem
     */
    public function getOrderItem(): OrderItem
    {
        return $this->orderItems ? $this->orderItems[0] : $this->orderItem;
    }

    /**
     * @return OrderItem[]
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function load(array $data)
    {
        $this->loadOrder($data);

        /** load OrderItem */
        $formName = $this->orderItem->formName();

        $this->orderItem = OrderItem::findOne([
            'offer_id' => $data[$formName]['offer_id'],
            'order_id' => $this->order->id,
        ]);

        if (empty($data[$formName]['created_user'])) {
            $data[$formName]['created_user'] = \Yii::$app->user->isGuest
                ? static::DEFAULT_BUYER_ID
                : \Yii::$app->user->id;

            $data[$formName]['updated_user'] = $data[$formName]['created_user'];
        }

        if (empty($this->orderItem)) {
            $data[$formName]['order_id'] = $this->order->id;
            $this->orderItem = new OrderItem();
        }

        $loadResult = $this->orderItem->load($data);

        if (!$loadResult) {
            return false;
        }

        return true;
    }

    private function loadOrder(array $data)
    {
        if (empty($data[$this->order->formName()])) {
            return;
        }

        if (!empty($data[$this->order->formName()]['delivery_date'])) {
            $deliveryDate = (new \DateTime(strtotime($data[$this->order->formName()]['delivery_date'])));
            $data[$this->order->formName()]['delivery_date'] = $deliveryDate->getTimestamp();
        }

        $loadResult = $this->order->load($data);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function save(): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $saveResult = $this->orderItem->save();

            if (!$saveResult) {
                throw new \Exception('OrderItem save error');
            }

            $saveResult = $this->order->save();

            if (!$saveResult) {
                throw new \Exception('Order save error');
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        }

        return true;
    }
}
