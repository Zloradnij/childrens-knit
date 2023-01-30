<?php

namespace app\modules\basket\widgets;


class MinimalSmallBasketWidget extends \yii\base\Widget
{
    public $orderItems = [];

    public function run()
    {

        $this->orderItems = \Yii::$app->basket->getOrder()->getItems()->all();
        ?>
        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
            <a href="/basket" class="btn px-0 ml-3">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span
                    class="badge text-secondary border border-secondary rounded-circle small-basket-count"
                    style="padding-bottom: 2px;"
                ><?= \Yii::$app->basket->getOrder()->getItems()->count() ?></span>
            </a>
        </div>
        <?php
    }
}
