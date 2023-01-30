<?php

namespace app\modules\catalogHeight\models\handler;

use app\modules\catalogHeight\models\Offer;
use app\modules\catalogHeight\models\Product;
use app\modules\catalogHeight\models\Property;
use app\modules\catalogHeight\models\PropertyValue;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class ProductMaker
{
    /** @var Product */
    private $product;

    /** @var Offer */
    private $offer;

    /** @var Offer[] */
    private $offers;

    /** @var Property */
    private $property;

    /** @var PropertyValue */
    private $propertyValue;

    /** @var PropertyValue[] */
    private $propertyValues;

    /** @var array */
    private $propertyValueDates;

    /**
     * SaveProduct constructor.
     * @param Product $product
     * @param Offer $offer
     * @param Property $property
     * @param PropertyValue $propertyValue
     */
    public function __construct(Product $product, Offer $offer, Property $property, PropertyValue $propertyValue)
    {
        $this->product = $product;
        $this->offer = $offer;
        $this->property = $property;
        $this->propertyValue = $propertyValue;
    }

    public function build(Product $product)
    {
        $this->product = $product;
        $this->offers = $product->getOffers()->all();
    }

    public function findByProductId(int $productId)
    {
        $this->product = Product::findOne($productId);
        $this->offers = $this->product->getOffers()->all();
    }

    public function setOffer()
    {
        $this->offers[] = $this->offer;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return Offer
     */
    public function getOffer(): Offer
    {
        return $this->offers ? $this->offers[0] : $this->offer;
    }

    /**
     * @return Offer[]
     */
    public function getOffers(): array
    {
        return $this->offers;
    }

    /**
     * @return Property[]
     */
    public function getActiveProperties(): array
    {
        return $this->property->find()->active()->orderBy('sort')->all();
    }

    /**
     * @return PropertyValue
     */
    public function getPropertyValue(): PropertyValue
    {
        return $this->propertyValues ? $this->propertyValues[0] : $this->propertyValue;
    }

    public function load(array $data)
    {
        if (empty($data[$this->product->formName()])) {
            return false;
        }

        $alias = Inflector::slug($data[$this->product->formName()]['title']);


        $data[$this->product->formName()]['alias'] = $alias;
        $loadResult = $this->product->load($data);

        if (!$loadResult) {
            return false;
        }

        /** load offers */
        foreach ($data[$this->offer->formName()] as $offerId => $offerData) {
            if (empty($offerData['id'])) {
                $this->offers[$offerId] = new Offer($offerData);
            } else {
                $this->offers[$offerId] = Offer::findOne($offerId);
            }
        }

        $loadResult = Model::loadMultiple($this->offers, $data);

        if (!$loadResult) {
            return false;
        }

        /** load property values */
        $this->setPropertyValues($data);

        $loadResult = Model::loadMultiple(
            $this->propertyValues,
            [$this->propertyValue->formName() => $this->propertyValueDates]
        );

        if (!$loadResult) {
            return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    protected function setPropertyValues(array $data)
    {
        $this->propertyValueDates = [];

        foreach ($data[$this->propertyValue->formName()] as $offerKey => $propertyValueData) {
            foreach ($propertyValueData as $propertyValueDatum) {
                if (empty($propertyValueDatum['offer_id'])) {
                    $propertyValueDatum['offer_id'] = $offerKey;
                }

                $this->propertyValueDates[] = $propertyValueDatum;

                $this->propertyValues[] = empty($propertyValueDatum['id'])
                    ? new PropertyValue($propertyValueDatum)
                    : PropertyValue::findOne($propertyValueDatum['id']);
            }
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $saveResult = $this->product->save();

            if (!$saveResult) {
                throw new \Exception('product save error');
            }

            $this->product->images = UploadedFile::getInstances($this->product, 'images');
            $this->product->upload();

            foreach ($this->offers as $i => $offer) {
                if (empty($offer->product_id)) {
                    $offer->product_id = $this->product->id;
                }

                $saveResult = $offer->save();

                if (!$saveResult) {
                    throw new \Exception('offer save error');
                }

                $index = $offer->id ?? $i;
                $offer->images = UploadedFile::getInstances($offer, "[{$index}]images");
                $offer->upload();
            }

            foreach ($this->propertyValues as $i => $propertyValue) {
                if (empty($propertyValue->offer_id)) {
                    $propertyValue->offer_id = $offer->id ?? $this->offers[$i]->id;
                }

                $saveResult = $propertyValue->save();

                if (!$saveResult) {
                    throw new \Exception('propertyValue save error');
                }
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw $e;
        }

        return true;
    }
}
