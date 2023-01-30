<?php

namespace app\modules\basket\models;

use app\modules\catalogHeight\models\Offer;
use app\modules\common\behaviors\ShopBlameableBehavior;
use yii\behaviors\TimestampBehavior;

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
class OrderItem extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class'              => ShopBlameableBehavior::class,
                'createdByAttribute' => 'created_user',
                'updatedByAttribute' => 'updated_user',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'offer_id', 'count'], 'required'],
            [
                [
                    'id',
                    'order_id',
                    'offer_id',
                    'count',
                    'sale_type_id',
                    'created_at',
                    'updated_at',
                    'created_user',
                    'updated_user',
                ],
                'integer',
            ],
            [['promo',], 'string', 'max' => 255],
            [['sale'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'order_id'     => 'Заказ',
            'offer_id'     => 'Товар',
            'count'        => 'Количество',
            'promo'        => 'Промокод',
            'sale'         => 'Скидка',
            'sale_type_id' => 'Тип скидки',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(Offer::class, ['id' => 'offer_id']);
    }

    public function getRealPrice()
    {
        /** @var Offer $offer */
        $offer = $this->getOffer()->one();

        if ($this->count < $offer->wholesale_count) {
            return $offer->retail_price;
        }

        return $offer->wholesale_price;
    }

    public function getResultPrice()
    {
        $priceItem = $this->getRealPrice();

        if (empty($this->sale)) {
            return $priceItem * $this->count;
        }

        if ($this->sale_type_id == Order::SALE_TYPE_PERCENT) {
            $priceItem *= (1 - $this->sale / 100);

            return $priceItem * $this->count;
        }

        $priceItem -= $this->sale;

        if ($priceItem < 0) {
            $priceItem = 0;
        }

        return $priceItem * $this->count;

    }
}
