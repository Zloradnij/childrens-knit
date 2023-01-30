<?php

use app\modules\catalogHeight\models\PropertyValue;

/* @var $this yii\web\View */
/* @var $product app\modules\catalogHeight\models\Product */
/* @var $offers app\modules\catalogHeight\models\Offer[] */
/** @var $sizes int[] */
/* @var $orderItems \app\modules\basket\models\OrderItem[] */
/** @var $sizePropertyId int */

$this->params['breadcrumbs'][] = $product->title;

$activeOffer = $offers[0];
$count = $orderItems[$activeOffer->id]->count ?? 1;

$price = $count >= $activeOffer->wholesale_count ? $activeOffer->wholesale_price : $activeOffer->retail_price;
?>

<!-- Shop Detail Start -->
<div class="container-fluid pb-5 product-page" id="product-item-<?= $product->id?>">
    <div class="offers-list">
        <?php
        foreach ($offers as $offer) {
            $countOffer = $orderItems[$offer->id]->count ?? 0;
            $priceOffer = $countOffer >= $offer->wholesale_count ? $offer->wholesale_price : $offer->retail_price;
            ?>
            <div
                data-product-id="<?= $product->id ?>"
                data-offer-id="<?= $offer->id ?>"
                data-retail-price="<?= $offer->retail_price ?>"
                data-wholesale-price="<?= $offer->wholesale_price ?>"
                data-wholesale-count="<?= $offer->wholesale_count ?>"
                data-offer-price="<?= $priceOffer ?>"
                data-offer-count="<?= $countOffer ?>"
                data-offer-active="<?= $activeOffer->id == $offer->id ? 1 : 0 ?>"
                data-offer-in-basket="<?= empty($orderItems[$offer->id]) ? 0 : 1 ?>"
                <?php
                foreach ($offer->getProperties()->all() as $propertyValue) {
                    $alias = $propertyValue->getProperty()->one()->alias; ?>
                    data-offer-<?= $alias ?>="<?= $propertyValue->value ?>"<?php
                } ?>
            ></div><?php
        } ?>
    </div>
    <div class="row px-xl-5">
        <div class="col-lg-5 mb-30">
            <div id="product-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner bg-light">
                    <?php
                    foreach ($product->getImages() as $i => $picture) { ?>
                        <div
                            class="carousel-block carousel-item-hidden hidden"
                            data-offer-id="product"
                        >
                            <img
                                class="w-100 h-100"
                                src="<?= $picture?>"
                                alt="<?= $product->title?>"
                            />
                        </div><?php
                    } ?>
                </div>
                <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                    <i class="fa fa-2x fa-angle-left text-dark"></i>
                </a>
                <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                    <i class="fa fa-2x fa-angle-right text-dark"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-7 h-auto mb-30">
            <div class="h-100 bg-light p-30 offer-properties">
                <h3><?= $product->title ?></h3>
                <div class="d-flex mb-3"></div>
                <h3 class="font-weight-semi-bold mb-4"><span class="product-price"><?= $price?></span> Р</h3>
                <p class="mb-4"><?= $product->description_short ?></p>
                <div class="d-flex mb-3">
                    <strong class="text-dark mr-3">Размеры:</strong>
                    <form>
                        <?php
                        foreach ($sizes as $size) {
                            $isActive = $activeOffer->getProperty($sizePropertyId)->one()->value == $size;
                            ?>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input
                                    type="radio"
                                    class="custom-control-input"
                                    id="size-<?= $size?>"
                                    name="size"
                                    value="<?= $size?>"
                                    <?= $isActive ? 'checked="checked"' : '' ?>
                                />
                                <label class="custom-control-label" for="size-<?= $size?>">
                                    <?= PropertyValue::SIZE[$size]?>
                                </label>
                            </div>
                            <?php
                        } ?>
                    </form>
                </div>
                <div class="d-flex mb-4">
                    <strong class="text-dark mr-3">Материал:</strong>
                    <form>
                        <?php
                        foreach (PropertyValue::MATERIAL as $i => $material) { ?>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input
                                    type="radio"
                                    checked="checked"
                                    class="custom-control-input"
                                    id="color-<?= $i?>"
                                    name="material"
                                    value="<?= $i?>"
                                />
                                <label class="custom-control-label" for="color-<?= $i?>"><?= $material?></label>
                            </div>
                            <?php
                        } ?>
                    </form>
                </div>
                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input
                            type="text"
                            class="form-control bg-secondary border-0 text-center offer-count"
                            value="<?= $count ?>"
                        />
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary px-3">
                        <i class="fa fa-shopping-cart mr-1"></i>
                        <span class="addToBasket" data-active="<?= !empty($orderItems[$activeOffer->id]) ? 0 : 1 ?>">
                            <?= !empty($orderItems[$activeOffer->id]) ? 'В корзине' : 'В корзину' ?>
                        </span>
                    </button>
                </div>
<!--                <div class="d-flex pt-2">-->
<!--                    <strong class="text-dark mr-2">Share on:</strong>-->
<!--                    <div class="d-inline-flex">-->
<!--                        <a class="text-dark px-2" href="">-->
<!--                            <i class="fab fa-facebook-f"></i>-->
<!--                        </a>-->
<!--                        <a class="text-dark px-2" href="">-->
<!--                            <i class="fab fa-twitter"></i>-->
<!--                        </a>-->
<!--                        <a class="text-dark px-2" href="">-->
<!--                            <i class="fab fa-linkedin-in"></i>-->
<!--                        </a>-->
<!--                        <a class="text-dark px-2" href="">-->
<!--                            <i class="fab fa-pinterest"></i>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="bg-light p-30">
                <div class="nav nav-tabs mb-4">
                    <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Описание</a>
<!--                    <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Информация</a>-->
<!--                    <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-3">Reviews (0)</a>-->
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Описание продукта</h4>
                        <p><?= $product->description ?></p>
                    </div>
<!--                    <div class="tab-pane fade" id="tab-pane-2">-->
<!--                        <h4 class="mb-3">Дополнительная информация</h4>-->
<!--                        <p>Eos no lorem eirmod diam diam, eos elitr et gubergren diam sea. Consetetur vero aliquyam invidunt duo dolores et duo sit. Vero diam ea vero et dolore rebum, dolor rebum eirmod consetetur invidunt sed sed et, lorem duo et eos elitr, sadipscing kasd ipsum rebum diam. Dolore diam stet rebum sed tempor kasd eirmod. Takimata kasd ipsum accusam sadipscing, eos dolores sit no ut diam consetetur duo justo est, sit sanctus diam tempor aliquyam eirmod nonumy rebum dolor accusam, ipsum kasd eos consetetur at sit rebum, diam kasd invidunt tempor lorem, ipsum lorem elitr sanctus eirmod takimata dolor ea invidunt.</p>-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <ul class="list-group list-group-flush">-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Sit erat duo lorem duo ea consetetur, et eirmod takimata.-->
<!--                                    </li>-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Amet kasd gubergren sit sanctus et lorem eos sadipscing at.-->
<!--                                    </li>-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Duo amet accusam eirmod nonumy stet et et stet eirmod.-->
<!--                                    </li>-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Takimata ea clita labore amet ipsum erat justo voluptua. Nonumy.-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <ul class="list-group list-group-flush">-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Sit erat duo lorem duo ea consetetur, et eirmod takimata.-->
<!--                                    </li>-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Amet kasd gubergren sit sanctus et lorem eos sadipscing at.-->
<!--                                    </li>-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Duo amet accusam eirmod nonumy stet et et stet eirmod.-->
<!--                                    </li>-->
<!--                                    <li class="list-group-item px-0">-->
<!--                                        Takimata ea clita labore amet ipsum erat justo voluptua. Nonumy.-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="tab-pane fade" id="tab-pane-3">-->
<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <h4 class="mb-4">1 review for "Product Name"</h4>-->
<!--                                <div class="media mb-4">-->
<!--                                    <img src="img/user.jpg" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">-->
<!--                                    <div class="media-body">-->
<!--                                        <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>-->
<!--                                        <div class="text-primary mb-2">-->
<!--                                            <i class="fas fa-star"></i>-->
<!--                                            <i class="fas fa-star"></i>-->
<!--                                            <i class="fas fa-star"></i>-->
<!--                                            <i class="fas fa-star-half-alt"></i>-->
<!--                                            <i class="far fa-star"></i>-->
<!--                                        </div>-->
<!--                                        <p>Diam amet duo labore stet elitr ea clita ipsum, tempor labore accusam ipsum et no at. Kasd diam tempor rebum magna dolores sed sed eirmod ipsum.</p>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!--                                <h4 class="mb-4">Leave a review</h4>-->
<!--                                <small>Your email address will not be published. Required fields are marked *</small>-->
<!--                                <div class="d-flex my-3">-->
<!--                                    <p class="mb-0 mr-2">Your Rating * :</p>-->
<!--                                    <div class="text-primary">-->
<!--                                        <i class="far fa-star"></i>-->
<!--                                        <i class="far fa-star"></i>-->
<!--                                        <i class="far fa-star"></i>-->
<!--                                        <i class="far fa-star"></i>-->
<!--                                        <i class="far fa-star"></i>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <form>-->
<!--                                    <div class="form-group">-->
<!--                                        <label for="message">Your Review *</label>-->
<!--                                        <textarea id="message" cols="30" rows="5" class="form-control"></textarea>-->
<!--                                    </div>-->
<!--                                    <div class="form-group">-->
<!--                                        <label for="name">Your Name *</label>-->
<!--                                        <input type="text" class="form-control" id="name">-->
<!--                                    </div>-->
<!--                                    <div class="form-group">-->
<!--                                        <label for="email">Your Email *</label>-->
<!--                                        <input type="email" class="form-control" id="email">-->
<!--                                    </div>-->
<!--                                    <div class="form-group mb-0">-->
<!--                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">-->
<!--                                    </div>-->
<!--                                </form>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Shop Detail End -->
