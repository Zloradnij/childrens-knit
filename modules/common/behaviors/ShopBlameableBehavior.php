<?php

namespace app\modules\common\behaviors;

class ShopBlameableBehavior extends \yii\behaviors\BlameableBehavior
{
    private const DEFAULT_BUYER_ID = 2;

    protected function getValue($event)
    {
        if ($this->value === null && \Yii::$app->has('user')) {
            $userId = \Yii::$app->get('user')->id ?? static::DEFAULT_BUYER_ID;
            if ($userId === null) {
                return $this->getDefaultValue($event);
            }

            return $userId;
        } elseif ($this->value === null) {
            return $this->getDefaultValue($event);
        }

        return parent::getValue($event);
    }
}