<?php

namespace app\modules\catalogHeight\models;

use app\modules\catalogHeight\interfaces\FileModelInterface;
use app\modules\catalogHeight\models\query\OfferQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "offer".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $product_id
 * @property float $price
 * @property float $wholesale_price
 * @property float $retail_price
 * @property integer $wholesale_count
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_user
 * @property integer $updated_user
 */
class Offer extends ActiveQueryCatalogAbstract implements FileModelInterface
{
    public $images;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['id', 'sort', 'product_id', 'status', 'created_at', 'updated_at', 'created_user', 'updated_user', 'wholesale_count', 'sort'], 'integer'],
            [['price', 'wholesale_price', 'retail_price',], 'number'],
            ['images', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'status'       => 'Статус',
            'product_id'   => 'Товар',
            'price'        => 'Цена',
            'sort'         => 'Sort',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
            'created_user' => 'Created User',
            'updated_user' => 'Updated User',

            'images'       => 'Картинки',
        ];
    }

    /**
     * @inheritdoc
     * @return OfferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OfferQuery(get_called_class());
    }

    /**
     * @return int
     */
    public static function getObjectType()
    {
        return Image::OBJECT_TYPE_OFFER;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiblings()
    {
        return $this->hasMany(Offer::class, ['product_id' => 'product_id'])->where(['<>', 'id', $this->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(PropertyValue::class, ['offer_id' => 'id']);
    }

    /**
     * @param int $propertyId
     * @return \yii\db\ActiveQuery
     */
    public function getProperty(int $propertyId)
    {
        return $this->hasOne(PropertyValue::class, ['offer_id' => 'id'])->where(['property_id' => $propertyId]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPictures()
    {
        return $this
            ->hasMany(Image::class, ['object_id' => 'id'])
            ->where(['object_type' => static::getObjectType()]);
    }

    /**
     * @return array
     */
    public function getPicturesData()
    {
        $pictureData = [];

        foreach ($this->getPictures()->all() as $image) {
            $pictureData[] = [
                'caption'=> $image->title,
                'width'  => '120px',
                'url'    => Url::to('/catalog/image/delete'),
                'key'    => $image->id
            ];
        }

        return $pictureData;
    }

    public function getWholesalePrice()
    {
        return $this->wholesale_price;
    }

    public function getRetailPrice()
    {
        return $this->retail_price;
    }
}
