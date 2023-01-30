<?php

namespace app\modules\basket\widgets;

use yii\helpers\Html;

class AddButtonWidget extends \yii\base\Widget
{
    public $offerId;
    public $count;
    public $wholesaleCount;
    public $retailPrice;
    public $wholesalePrice;

    public function run()
    {
        if (!$this->retailPrice) {
            $this->retailPrice = ceil($this->wholesalePrice * \Yii::$app->basket->getRetailPercent());
        }

        if (!$this->wholesaleCount) {
            $this->wholesaleCount = \Yii::$app->basket->getWholesaleCount();
        }

        $price = $this->count >= $this->wholesaleCount ? $this->wholesalePrice : $this->retailPrice;
        ?>

        <div
            class="add-product-block"
            data-offer-id="<?= $this->offerId?>"
            data-retail-price="<?= $this->retailPrice?>"
            data-wholesale-price="<?= $this->wholesalePrice?>"
            data-wholesale-count="<?= $this->wholesaleCount?>"
        >

            <span class="product-price"><span class="show-price"><?= $price?></span> / шт</span>
            <span class="hidden product-full-price"><?= $price * $this->count?></span>
            <div class="form-group field-orderitem-count">
                <span class="set-product-count product-count-minus">-</span>
                <input
                    type="text"
                    class="orderitem-count"
                    name="OrderItem[count]"
                    value="<?= $this->count ?: 1 ?>"
                />
                <span class="set-product-count product-count-plus">+</span>
            </div>

            <div class="form-group">
                <span class="btn btn-primary addToBasket"><?= $this->count ? 'В корзине' : 'Купить'?></span>
            </div>

        </div>
        <?php
    }
}
