<?php /** @noinspection ALL */

namespace app\modules\catalogHeight\models;

use app\modules\catalogHeight\interfaces\FileModelInterface;
use app\modules\catalogHeight\models\query\ProductQuery;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

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
class Product extends ActiveQueryCatalogAbstract implements FileModelInterface
{
    public $images;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors()
    {
        parent::behaviors();
        return [
            TimestampBehavior::class,
            [
                'class'              => BlameableBehavior::class,
                'createdByAttribute' => 'created_user',
                'updatedByAttribute' => 'updated_user',
            ],
            [
                'class'         => SluggableBehavior::class,
                'attribute'     => 'title',
                'slugAttribute' => 'alias',
                'ensureUnique'  => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required', 'message' => '{attribute} - обязательное поле'],
            [['id', 'sort', 'status', 'created_at', 'updated_at', 'created_user', 'updated_user'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description', 'description_short'], 'string'],
            ['images', 'safe'],
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
            'sort'              => 'Sort',
            'description'       => 'Описание',
            'description_short' => 'Короткое описание',
            'created_at'        => 'Created At',
            'updated_at'        => 'Updated At',
            'created_user'      => 'Created User',
            'updated_user'      => 'Updated User',

            'images'            => 'Картинки',
        ];
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * @return int
     */
    public static function getObjectType()
    {
        return Image::OBJECT_TYPE_PRODUCT;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasMany(Offer::class, ['product_id' => 'id'])->active()->one();
    }

    public function getImages()
    {
        $pathDir = \Yii::getAlias('@webroot') . "/images/products/$this->alias/";

        $images = [];

        foreach (scandir($pathDir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            $images[] = "/images/products/{$this->alias}/" . basename($item);
        }

        return $images;
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
}
