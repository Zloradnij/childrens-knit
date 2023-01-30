<?php

namespace app\modules\catalogHeight\models;

use app\modules\catalogHeight\models\query\PropertyQuery;

/**
 * This is the model class for table "property".
 *
 * @property integer $id
 * @property integer $property_type_id
 * @property integer $status
 * @property string $title
 * @property string $alias
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class Property extends ActiveQueryCatalogAbstract
{
    public const PROPERTY_TYPE_IDS = [
        1 => 'Целое число',
        2 => 'Строка',
        3 => 'Дробное число',
        4 => 'Файл',
        5 => 'Список',
        6 => 'Множественный список',
    ];

    public const PROPERTY_TYPE_VALUES = [
        'Целое число'          => 1,
        'Строка'               => 2,
        'Дробное число'        => 3,
        'Файл'                 => 4,
        'Список'               => 5,
        'Множественный список' => 6,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias', 'property_type_id'], 'required', 'message' => '{attribute} - обязательное поле'],
            [['id', 'property_type_id', 'status', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'integer'],
            [['title', 'alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'property_type_id' => 'Тип свойства',
            'status'           => 'Статус',
            'title'            => 'Название',
            'alias'            => 'Alias',
            'created_at'       => 'Created At',
            'updated_at'       => 'Updated At',
            'created_user'     => 'Created User',
            'updated_user'     => 'Updated User',
        ];
    }

    /**
     * @inheritdoc
     * @return PropertyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PropertyQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyType()
    {
        return $this->hasOne(PropertyType::class, ['property_type_id' => 'id']);
    }
}
