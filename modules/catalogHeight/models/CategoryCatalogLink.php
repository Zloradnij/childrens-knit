<?php

namespace app\modules\catalogHeight\models;

/**
 * This is the model class for table "category2catalog".
 *
 * @property integer $id
 * @property integer $catalog_id
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class CategoryCatalogLink extends ActiveQueryCatalogAbstract
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category2catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalog_id', 'category_id'], 'required'],
            [
                [
                    'catalog_id',
                    'category_id',
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
            'catalog_id'   => 'catalog_id',
            'category_id'  => 'category_id',
            'created_at'   => 'created_at',
            'updated_at'   => 'updated_at',
            'created_user' => 'created_user',
            'updated_user' => 'updated_user',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['catalog_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['category_id' => 'id']);
    }
}
