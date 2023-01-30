<?php

namespace app\modules\catalogHeight\models;

/**
 * This is the model class for table "product2category".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class ProductCategoryLink extends ActiveQueryCatalogAbstract
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product2category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id'], 'required'],
            [
                [
                    'category_id',
                    'product_id',
                    'created_at',
                    'updated_at',
                    'created_user',
                    'updated_user',
                ],
                'integer',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'category_id'  => 'category_id',
            'product_id'   => 'product_id',
            'created_at'   => 'created_at',
            'updated_at'   => 'updated_at',
            'created_user' => 'created_user',
            'updated_user' => 'updated_user',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'id']);
    }
}
