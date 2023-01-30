<?php

namespace app\modules\basket\widgets;

use app\modules\catalogHeight\models\Offer;

class ProductWidget extends \yii\base\Widget
{
    public $product;
    public $count;
    public $wholesaleCount;
    public $retailPrice;
    public $wholesalePrice;

    public function run()
    {
        $orderItems = \Yii::$app->basket->getOrder()->getItems()->indexBy('offer_id')->all();

        /** @var Offer $offer */
        $offer = $this->product->getOffers()->one();
        $this->wholesalePrice = $offer->wholesale_price;

        if (!$this->retailPrice) {
            $this->retailPrice = $offer->retail_price;
        }

        if (!$this->wholesaleCount) {
            $this->wholesaleCount = \Yii::$app->basket->getWholesaleCount();
        } ?>

        <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
            <div class="product-item bg-light mb-4" id="product-item-<?= $this->product->id?>">
                <div
                    class="product-img position-relative overflow-hidden"
                >
                    <img
                        class="img-fluid w-100"
                        src="<?= $this->product->getImages()[0] ?>"
                        alt=""
                    />
                    <div
                        class="product-action"
                        data-offer-id="<?= $offer->id?>"
                        data-retail-price="<?= $offer->retail_price ?>"
                        data-wholesale-price="<?= $offer->wholesale_price ?>"
                        data-wholesale-count="<?= $offer->wholesale_count ?>"
                    >
                        <a
                                class="btn btn-outline-dark btn-square"
                                href="/catalog/<?= $this->product->alias ?>"
                        >
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                    </div>
                </div>
                <div class="text-center py-4">
                    <a
                        class="h6 text-decoration-none text-truncate"
                        href="/catalog/<?= $this->product->alias ?>"
                    >
                        <?= $this->product->title ?>
                    </a>
                    <div class="d-flex align-items-center justify-content-center mt-2">
                        <h5>
                            <?= $this->count >= $this->wholesaleCount ? $this->wholesalePrice : $this->retailPrice ?> &#8381;
                        </h5>
                        <h6 class="text-muted ml-2">
                            <del>
                                <?= $this->count < $this->wholesaleCount ? $this->wholesalePrice : $this->retailPrice ?> &#8381;
                            </del>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
