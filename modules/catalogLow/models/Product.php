<?php

namespace app\modules\catalogLow\models;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $status
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $description_short
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class Product extends ActiveQueryCatalogAbstract
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'low_product';
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
            [['description', 'description_short'], 'string'],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'status'            => 'Статус',
            'title'             => 'Название',
            'alias'             => 'Alias',
            'description'       => 'Описание',
            'description_short' => 'Короткое описание',
            'created_at'        => 'Created At',
            'updated_at'        => 'Updated At',
            'created_user'      => 'Created User',
            'updated_user'      => 'Updated User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id'])
            ->viaTable(ProductCategoryLink::tableName(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::class, ['product_id' => 'id']);
    }
}
