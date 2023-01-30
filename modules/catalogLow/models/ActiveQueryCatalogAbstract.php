<?php

namespace app\modules\catalogLow\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

abstract class ActiveQueryCatalogAbstract extends \yii\db\ActiveRecord
{
    public const STATUS_ACTIVE = 10;

    public const STATUS_DELETED = 0;

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_user',
                'updatedByAttribute' => 'updated_user',
            ],
        ];
    }

    public function deactivate()
    {
        $this->status = static::STATUS_DELETED;

        return $this->save();
    }
}
