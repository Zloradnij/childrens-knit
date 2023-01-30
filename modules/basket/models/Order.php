<?php

namespace app\modules\basket\models;

use app\models\User;
use app\modules\alarm\models\Telegram;
use app\modules\basket\models\query\OrderQuery;
use app\modules\common\behaviors\ShopBlameableBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
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
class Order extends \yii\db\ActiveRecord
{
    public const STATUS_NEW = 10;
    public const STATUS_PAYED = 20;
    public const STATUS_JOB = 25;
    public const STATUS_DELIVERY = 30;
    public const STATUS_FINISH = 40;

    public const STATUS_CLOSED = 99;
    public const STATUS_DELETED = 0;

    public const ORDER_STATUSES = [
        self::STATUS_NEW      => 'Новый',
        self::STATUS_PAYED    => 'Оплачен',
        self::STATUS_JOB      => 'В работе',
        self::STATUS_DELIVERY => 'Доставка',
        self::STATUS_FINISH   => 'Завершён',
        self::STATUS_CLOSED   => 'Отменён',
        self::STATUS_DELETED  => 'Удалён',
    ];

    public const ORDER_STATUSES_FROM_BASKET = [
        self::STATUS_PAYED    => 'Оплачен',
        self::STATUS_JOB      => 'В работе',
        self::STATUS_DELIVERY => 'Доставка',
        self::STATUS_FINISH   => 'Завершён',
        self::STATUS_CLOSED   => 'Отменён',
    ];

    public const PAY_TYPE_CASH = 10;

    public const PAY_TYPES = [
        self::PAY_TYPE_CASH => 'Наличными при получении товара',
    ];

    public const DELIVERY_TYPE_SIMPLE = 10;
    public const DELIVERY_TYPE_SELF = 20;
    public const DELIVERY_TYPE_COMPANY = 30;

    public const DELIVERY_TYPES = [
        self::DELIVERY_TYPE_SIMPLE  => 'Доставка продавцом (только Новосибирск и окресности)',
        self::DELIVERY_TYPE_SELF    => 'Самовывоз',
        self::DELIVERY_TYPE_COMPANY => 'Доставка транспортной компанией',
    ];

    public const DELIVERY_INFO = [
        self::DELIVERY_TYPE_SIMPLE  => '20 рублей / километр',
        self::DELIVERY_TYPE_SELF    => '',
        self::DELIVERY_TYPE_COMPANY => 'Стоимость уточняйте у мнеджера',
    ];

    public const DELIVERY_WITH_ADDRESS = [
        self::DELIVERY_TYPE_SIMPLE,
        self::DELIVERY_TYPE_COMPANY,
    ];

    public const SALE_TYPE_PERCENT = 10;
    public const SALE_TYPE_FIX = 20;

    public const SALE_TYPES = [
        self::SALE_TYPE_PERCENT => 'В процентах',
        self::SALE_TYPE_FIX     => 'В рублях',
    ];

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
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'delivery_date'], 'required'],
            [
                [
                    'id',
                    'user_id',
                    'pay_type_id',
                    'delivery_id',
                    'delivery_date',
                    'sale_type_id',
                    'status',
                    'created_at',
                    'updated_at',
                    'created_user',
                    'updated_user',
                ],
                'integer',
            ],
            [['promo', 'delivery_address'], 'string', 'max' => 255],
            [['session_id'], 'string', 'max' => 60],
            [['comment'], 'string'],
            [['price', 'sale', 'delivery_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'user_id'          => 'Покупатель',
            'price'            => 'Общая цена',
            'pay_type_id'      => 'Тип оплаты',
            'delivery_id'      => 'Тип доставки',
            'delivery_address' => 'Адрес доставки',
            'delivery_date'    => 'Дата доставки',
            'delivery_price'  => 'Стоимость доставки',
            'promo'            => 'Промокод',
            'sale'             => 'Скидка',
            'sale_type_id'     => 'Тип скидки',
            'comment'          => 'Комментарий к заказу',
            'status'           => 'Статус',
            'created_at'       => 'Created At',
            'updated_at'       => 'Updated At',
            'created_user'     => 'Created User',
            'updated_user'     => 'Updated User',
        ];
    }

    /**
     * @inheritdoc
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        $this->setPrice();

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    protected function setPrice()
    {
        $price = $this->delivery_price;

        if (empty($this->getItems()->all())) {
            return;
        }

        /** @var OrderItem $item */
        foreach ($this->getItems()->all() as $item) {

            if (empty($item->getOffer()->one())) {
                $item->delete();

                continue;
            }

            $priceItem = $item->getResultPrice();
            $price += $priceItem;
        }

        if (empty($this->sale)) {
            $this->price = $price;

            return;
        }

        if ($this->sale_type_id == static::SALE_TYPE_PERCENT) {
            $this->price = $price * (1 - $this->sale / 100);

            return;
        }

        $this->price = $price - $this->sale;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub

        if ($this->status !== self::STATUS_JOB) {
            return;
        }

        (new Telegram($this))->send();

        \Yii::$app->mailer->compose()
            ->setTo(\Yii::$app->params['senderEmail'])
            ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
            ->setReplyTo([\Yii::$app->params['adminEmail'] => \Yii::$app->params['senderName']])
            ->setSubject('Оформлен заказ')
            ->setTextBody("Заказ № {$this->id}")
            ->send();
    }
}
