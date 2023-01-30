<?php

namespace app\modules\catalogHeight\models;

/**
 * This is the model class for table "property_value".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $offer_id
 * @property integer $property_id
 * @property string $value
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class PropertyValue extends ActiveQueryCatalogAbstract
{
    public const SIZE = [
        18,
        20,
        22,
        24,
        26,
        28,
        '28-30',
        30,
        '30-32',
        34,
    ];

    public const MATERIAL = [
        'Кулирная гладь / Хлопок 100%',
    ];

    public const VALUE_LIST = [
        'size'     => PropertyValue::SIZE,
        'material' => PropertyValue::MATERIAL,
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'offer_id', 'property_id'], 'required', 'message' => 'Это обязательное поле'],
            [
                ['id', 'status', 'created_at', 'updated_at', 'created_user', 'updated_user', 'offer_id', 'property_id'],
                'integer',
            ],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'offer_id'     => 'Торговое предложение',
            'property_id'  => 'Свойство',
            'status'       => 'Статус',
            'value'        => 'Значение',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }
}
