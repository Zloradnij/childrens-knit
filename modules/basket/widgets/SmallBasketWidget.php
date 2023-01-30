<?php

namespace app\modules\basket\widgets;

use app\modules\basket\models\OrderItem;

class SmallBasketWidget extends \yii\base\Widget
{
    public $orderItems = [];

    public function run()
    {
        $this->orderItems = \Yii::$app->basket->getOrder()->getItems()->all();
        $display = count($this->orderItems) ? '' : 'hidden';
        ?>

        <div class="small-basket-block <?= $display ?>">

            <span class="small-basket-title"><a href="/basket/">Корзина</a></span>

            <div class="small-basket-block-items">
                <?php
                /** @var OrderItem $orderItem */
                foreach ($this->orderItems as $orderItem) {

                    $alias = $orderItem->getOffer()->one()->getProduct()->one()->alias;
                    ?>
                    <div class="small-basket-item">
                        <span class="small-basket-item-image">
                            <a href="https://childrens-knit.ru/catalog/product/view/<?= $alias ?>">
                                <img
                                    src="<?= $orderItem->getOffer()->one()->getPictures()->one()->path ?>"
                                    class=""
                                    style="width: 100px;"
                                />
                            </a>
                        </span>
                        <span class="small-basket-item-title">
                            <a href="https://childrens-knit.ru/catalog/product/view/<?= $alias ?>">
                                <?= $orderItem->getOffer()->one()->getProduct()->one()->title ?>
                            </a>
                        </span>
                        <span class="small-basket-item-price">
                            <?= $orderItem->getRealPrice() ?>
                        </span>
                        <span class="small-basket-item-count">
                            <?= $orderItem->count ?>
                        </span>
                        <span class="small-basket-item-full-price">
                            <?= $orderItem->getResultPrice() ?>
                        </span>
                    </div>
                    <?php
                } ?>
            </div>
            <div class="small-basket-sum">
                <span class="small-basket-sum-count">
                    Всего товаров: <?= count($this->orderItems) ?>
                </span>
                <span class="small-basket-sum-price">
                    Сумма: <?= \Yii::$app->basket->getOrder()->price ?> руб.
                </span>
            </div>
            <span class="small-basket-open">111</span>

        </div>
        <?php
    }
}
