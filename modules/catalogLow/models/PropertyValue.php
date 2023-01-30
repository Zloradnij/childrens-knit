<?php

namespace app\modules\catalogLow\models;

/**
 * This is the model class for table "property_value".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $product_id
 * @property integer $property_id
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class PropertyValue extends ActiveQueryCatalogAbstract
{
    public const PROPERTY_IDS = [
        1 => 'Материал',
        2 => 'Цвет',
        3 => 'Размер',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'low_property_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'product_id', 'property_id'], 'required'],
            [
                [
                    'id',
                    'product_id',
                    'status',
                    'created_at',
                    'updated_at',
                    'created_user',
                    'updated_user',
                    'property_id',
                ],
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
            'product_id'   => 'Товар',
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
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'id']);
    }
}
