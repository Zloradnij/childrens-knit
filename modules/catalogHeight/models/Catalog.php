<?php

namespace app\modules\catalogHeight\models;

/**
 * This is the model class for table "catalog".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $status
 * @property string $title
 * @property string $alias
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class Catalog extends ActiveQueryCatalogAbstract
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['id', 'parent_id', 'status', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'integer'],
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
            'id'         => 'ID',
            'parent_id'  => 'Parent',
            'status'     => 'Статус',
            'title'      => 'Название',
            'alias'      => 'Alias',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Catalog::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogs()
    {
        return $this->hasMany(Catalog::class, ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable(CategoryCatalogLink::tableName(), ['catalog_id' => 'id']);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     * @throws \yii\base\InvalidConfigException
//     */
//    public function getProducts()
//    {
//        return $this->hasMany(Product::class, ['id' => 'product_id'])
//            ->viaTable(CategoryCatalogLink::tableName(), ['category_id' => 'id']);
//    }
}
