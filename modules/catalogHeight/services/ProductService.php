<?php

namespace app\modules\catalogHeight\services;

use app\modules\catalogHeight\models\Offer;
use app\modules\catalogHeight\models\Product;
use app\modules\catalogHeight\models\Property;
use app\modules\catalogHeight\models\PropertyValue;
use yii\base\Model;
use yii\helpers\Inflector;

class ProductService
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

    /**
     * SaveProduct constructor.
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function build(Product $product)
    {
        $this->product = $product;
        $this->offers = $product->getOffers()->all();
        $this->propertyValues = $product->getOffers()->all();
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
        return $this->property->find()->active()->all();
    }

    /**
     * @return PropertyValue
     */
    public function getPropertyValue(): PropertyValue
    {
        return $this->propertyValues ? $this->propertyValues[0] : $this->propertyValue;
    }

    /**
     * @return PropertyValue[]
     */
    public function getPropertyValues(): array
    {
        return $this->propertyValues;
    }

    public function load($data)
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
        foreach ($data[$this->offer->formName()] as $offerData) {
            $this->offers[] = new Offer($offerData);
        }

        $loadResult = Model::loadMultiple($this->offers, $data);

        if (!$loadResult) {
            return false;
        }

        /** load property values */
        $propertyValueDates = [];

        foreach ($data[$this->propertyValue->formName()] as $propertyValueData) {
            foreach ($propertyValueData as $propertyValueDatum) {
                $propertyValueDates[] = $propertyValueDatum;
                $this->propertyValues[] = new PropertyValue($propertyValueDatum);
            }
        }

        $loadResult = Model::loadMultiple(
            $this->propertyValues,
            [$this->propertyValue->formName() => $propertyValueDates]
        );

        if (!$loadResult) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        try {
            $saveResult = $this->product->save();

            if (!$saveResult) {
                throw new \Exception('product save error');
            }

            foreach ($this->offers as $offer) {
                $offer->product_id = $this->product->id;

                $saveResult = $offer->save();

                if (!$saveResult) {
                    throw new \Exception('offer save error');
                }
            }

            foreach ($this->propertyValues as $i => $propertyValue) {
                $propertyValue->offer_id = $this->offers[$i]->id;

                $saveResult = $propertyValue->save();

                if (!$saveResult) {
                    throw new \Exception('propertyValue save error');
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
