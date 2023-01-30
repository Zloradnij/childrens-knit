<?php

namespace app\modules\basket\models\query;

use app\modules\basket\models\Order;

/**
 * This is the ActiveQuery class for [[\app\modules\basket\models\Order]].
 *
 * @see \app\modules\basket\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    public function findBySession()
    {
        return $this->andWhere(['session_id' => \Yii::$app->session->getId()]);
    }

    public function findByUserId()
    {
        return $this->andWhere(['not', ['user_id' => null]])->andWhere(['user_id' => \Yii::$app->user->id]);
    }

    public function active()
    {
        return $this->andWhere(['status' => Order::STATUS_NEW]);
    }
}
