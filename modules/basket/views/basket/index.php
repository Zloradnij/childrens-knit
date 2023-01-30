<?php

/* @var $this yii\web\View */
/* @var $basket Order */
/* @var $propertyValues [] */


$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

use app\modules\basket\models\Order; ?>

<!-- Cart Start -->
<div class="container-fluid basket-page">
    <div class="row px-xl-5">
        <div class="col-lg-12 table-responsive mb-12">
            <label class="">
                Как к Вам обращаться
            </label>
            <input
                    id="username"
                    name="username"
                    type="text"
                    value="<?= Yii::$app->user->identity->username ?>"
                    placeholder="например - Семён Семёныч"
                    class="form-control"
            />
            <p></p>
        </div>
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0">
                <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th>Товар (Размер / Материал)</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Всего</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody class="align-middle">

                <?php
                /** @var \app\modules\basket\models\OrderItem $item */
                foreach ($basket->getItems()->all() as $item) {
                    /** @var \app\modules\catalogHeight\models\Offer $offer */
                    $offer = $item->getOffer()->one();
                    /** @var \app\modules\catalogHeight\models\Product $product */
                    $product = $offer->getProduct()->one();
                    $propertyVisible = [];

                    foreach ($offer->getProperties()->all() as $property) {
                        $alias = $property->getProperty()->one()->alias;

                        $propertyVisible[] = $propertyValues[$alias][$property->value];
                    }

                    $propertyVisible = implode(' / ', $propertyVisible);
                    ?>
                    <tr
                        class="product-item"
                        id="product-item-<?= $product->id ?>"
                        data-product-id="<?= $product->id ?>"
                        data-offer-id="<?= $offer->id ?>"
                        data-retail-price="<?= $offer->retail_price ?>"
                        data-wholesale-price="<?= $offer->wholesale_price ?>"
                        data-wholesale-count="<?= $offer->wholesale_count ?>"
                        data-offer-price="<?= $item->getResultPrice() ?>"
                        data-offer-count="<?= $item->count ?>"
                        data-offer-active="<?= $offer->id ?>"
                        data-offer-in-basket="1"
                    >
                        <td class="align-middle">
                            <img src="<?= $product->getImages()[0]?>" alt="" style="width: 50px;"/>
                        </td>
                        <td class="align-middle">
                            <?= $product->title ?> (<?= $propertyVisible ?>)
                        </td>
                        <td class="align-middle">
                            <span class="product-price"><?= $item->getRealPrice() ?></span> &#8381;
                        </td>
                        <td class="align-middle">
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    class="form-control form-control-sm bg-secondary border-0 text-center offer-count"
                                    value="<?= $item->count ?>"
                                />
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-plus">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="product-result-price"><?= $item->getResultPrice() ?></span> &#8381;
                        </td>
                        <td class="align-middle">
                            <button class="btn btn-sm btn-danger delete-from-basket"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>

            <p></p>

            <div class="payment-select">
                <h5 class="section-title position-relative text-uppercase">
                    <span class="bg-secondary pr-3">Способ оплаты</span>
                </h5>

                <?php
                foreach (Order::PAY_TYPES as $paymentTypeId => $paymentType) { ?>
                    <div>
                    <input
                            type="radio"
                            class="payment-control-input"
                            id="payment-<?= $paymentTypeId ?>"
                            name="payment"
                            value="<?= $paymentTypeId?>"
                        <?= $basket->pay_type_id === $paymentTypeId ? 'checked="checked"' : '' ?>
                    />
                    <label class="" for="delivery-<?= $paymentTypeId ?>">
                        <?= $paymentType ?>
                    </label>
                    </div><?php
                } ?>
            </div>

            <p></p>

            <div class="delivery-select">
                <h5 class="section-title position-relative text-uppercase">
                    <span class="bg-secondary pr-3">Выберите доставку</span>
                </h5>

                <?php
                foreach (Order::DELIVERY_TYPES as $deliveryTypeId => $deliveryType) { ?>
                    <div>
                        <input
                            type="radio"
                            class="delivery-control-input"
                            id="delivery-<?= $deliveryTypeId ?>"
                            data-address="<?= in_array($deliveryTypeId, Order::DELIVERY_WITH_ADDRESS) ? 1 : 0 ?>"
                            data-calculate="<?= in_array($deliveryTypeId, [Order::DELIVERY_TYPE_SIMPLE]) ? 1 : 0 ?>"
                            name="delivery"
                            value="<?= $deliveryTypeId?>"
                            <?= $basket->delivery_id === $deliveryTypeId ? 'checked="checked"' : '' ?>
                        />
                        <label class="" for="delivery-<?= $deliveryTypeId ?>">
                            <?= $deliveryType ?>
                        </label>
                        <div class="delivery-info <?= $basket->delivery_id === $deliveryTypeId ? '' : 'hidden' ?>">
                            <?= Order::DELIVERY_INFO[$deliveryTypeId] ?>
                        </div>
                    </div><?php
                } ?>
                <div id="address-container" class="col-lg-8">
                    <hr />
                    <label class="">
                        Введите адрес
                    </label>
                    <input
                        id="delivery-address"
                        name="address"
                        type="text"
                        value="<?= $basket->delivery_address ?? '' ?>"
                    />
                    <div id="map"></div>
                    <input type="hidden" id="delivery-address-hidden" />
                    <input type="hidden" id="delivery-price" />
                </div>

            </div>

            <p></p>

            <div class="comment-block">
                <h5 class="section-title position-relative text-uppercase">
                    <span class="bg-secondary pr-3">Комментарий</span>
                </h5>
                <textarea id="comment-filed" name="comment" rows="4" cols=""><?= $basket->comment ?? '' ?></textarea>
            </div>

            <p></p>

            <div class="order-block">
                <button id="create-order-bottom" class="btn btn-block btn-primary font-weight-bold my-3 py-3">
                    Оформить заказ
                </button>
            </div>

        </div>

        <div class="col-lg-4">
<!--            <form class="mb-30" action="">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" class="form-control border-0 p-4" placeholder="Coupon Code">-->
<!--                    <div class="input-group-append">-->
<!--                        <button class="btn btn-primary">Apply Coupon</button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </form>-->
            <div class="">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Заказ</span>
                </h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Сумма товаров</h6>
                            <h6>
                                <span class="basket-products-price">
                                    <?= $basket->price - $basket->delivery_price ?>
                                </span> &#8381;
                            </h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Доставка</h6>
                            <h6 class="font-weight-medium"><span class="basket-delivery-price">0</span> &#8381;</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Всего</h5>
                            <h5><span class="basket-all-price"><?= $basket->price ?></span> &#8381;</h5>
                        </div>
                        <button id="create-order" class="btn btn-block btn-primary font-weight-bold my-3 py-3">
                            Оформить заказ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader-container"><img src="/images/loadingIcon.gif" /></div>
</div>
<!-- Cart End -->
