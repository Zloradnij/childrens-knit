<?php

namespace app\modules\catalogLow\models\handler;

use app\modules\catalogLow\models\Offer;
use app\modules\catcatalogLowalog\models\Product;
use app\modules\catalogLow\models\Property;
use app\modules\catalogLow\models\PropertyValue;

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

    private function build()
    {
        if ($this->product->isNewRecord) {
            return;
        }

        $this->offers = $this->product->getOffers();
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
        $loadResult = $this->product->load($data);

        if (!$loadResult) {
            return false;
        }

        foreach ($data[$this->offer->formName()] as $offerData) {
            $cloneOffer = new Offer();

            $loadResult = $cloneOffer->load(['Offer' => $offerData]);

            if (!$loadResult) {
                return false;
            }

            $this->offers[] = $cloneOffer;
        }

        foreach ($data[$this->propertyValue->formName()] as $propertyValueData) {
            $clonePropertyValue = new PropertyValue();

            $loadResult = $clonePropertyValue->load(['PropertyValue' => $propertyValueData]);

            if (!$loadResult) {
                return false;
            }

            $this->propertyValues[] = $clonePropertyValue;
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
                if ($this->product->isNewRecord) {
                    $offer->product_id = $this->product->id;
                }

                $saveResult = $offer->save();

                if (!$saveResult) {
                    throw new \Exception('offer save error');
                }
            }

            foreach ($this->propertyValues as $i => $propertyValue) {
                if ($this->product->isNewRecord) {
                    $propertyValue->offer_id = $this->offers[$i]->id;
                }

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
