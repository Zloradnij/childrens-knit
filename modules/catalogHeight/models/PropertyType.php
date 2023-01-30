<?php

namespace app\modules\catalogHeight\models;

/**
 * This is the model class for table "property_type".
 *
 * @property integer $id
 * @property integer $status
 * @property string $title
 * @property string $alias
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class PropertyType extends ActiveQueryCatalogAbstract
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['id', 'status', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'integer'],
            [['title', 'alias'], 'string', 'max' => 255],
            [['title', 'alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'status'           => 'Статус',
            'title'            => 'Название',
            'alias'            => 'Alias',
            'created_at'       => 'Created At',
            'updated_at'       => 'Updated At',
            'created_user'     => 'Created User',
            'updated_user'     => 'Updated User',
        ];
    }
}
