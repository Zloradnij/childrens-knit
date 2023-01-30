<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Studio 90-is">

    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <?php $this->head() ?>

</head>

<body>
<!-- Topbar Start -->
<div class="container-fluid">
    <div class="row bg-secondary py-1 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center h-100">
                <a class="text-body mr-3" href="/contacts">Контакты</a>
<!--                <a class="text-body mr-3" href="/help">Помощь</a>-->
            </div>
        </div>
        <div class="col-lg-6 text-center text-lg-right">
            <div class="d-inline-flex align-items-center">
                <div class="btn-group">
                    <?= Yii::$app->user->isGuest ? (
                        Nav::widget([
                            'options' => ['class' => 'text-body navbar-nav'],
                            'items' => [
                                ['label' => 'Вход', 'url' => ['/site/login']]
                            ]])
                    ) : (
                         Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                        . Html::submitButton(
                            'Выход (' . (Yii::$app->user->identity->username ?: Yii::$app->user->identity->phone) . ')',
                            ['class' => 'text-body btn btn-link logout']
                        )
                        . Html::endForm()
                    ); ?>
                </div>
            </div>
            <div class="d-inline-flex align-items-center d-block d-lg-none">
                <a href="" class="btn px-0 ml-2">
                    <i class="fas fa-heart text-dark"></i>
                    <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                </a>
                <a href="" class="btn px-0 ml-2">
                    <i class="fas fa-shopping-cart text-dark"></i>
                    <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                </a>
            </div>
        </div>
    </div>
<!--    <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">-->
<!--        <div class="col-lg-4">-->
<!--        </div>-->
<!--        <div class="col-lg-4 col-6 text-left">-->
<!--            <form action="">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" class="form-control" placeholder="Поиск">-->
<!--                    <div class="input-group-append">-->
<!--                        <span class="input-group-text bg-transparent text-primary">-->
<!--                            <i class="fa fa-search"></i>-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid bg-dark mb-30">
    <div class="row px-xl-5">
        <div class="col-lg-6 d-none d-lg-block padding-top-9">
            <a href="/" class="text-decoration-none">
                <span class="h1 text-uppercase text-primary bg-dark px-2">Детский</span>
                <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Трикотаж</span>
            </a>
        </div>
        <div class="col-lg-6">
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                <a href="" class="text-decoration-none d-block d-lg-none">
                    <span class="h1 text-uppercase text-dark bg-light px-2">Детский</span>
                    <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Трикотаж</span>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="/" class="nav-item nav-link active">Главная</a>
                        <a href="/contacts" class="nav-item nav-link">Контакты</a>
                        <?= Yii::$app->user->isGuest ? "" : '<a href="/my-orders" class="nav-item nav-link">Мои заказы</a>'?>
                        <a href="/basket" class="nav-item nav-link">Корзина</a>
                    </div>
                    <?= \app\modules\basket\widgets\MinimalSmallBasketWidget::widget(); ?>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- Navbar End -->

<!-- Breadcrumb Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/'],
                'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'tag'      => 'nav', // container tag
                'options'  => ['class' => 'breadcrumb bg-light'], // attributes on container
            ]) ?>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<div class="container-fluid pt-5">
    <?= Alert::widget() ?>
</div>

<?= $content ?>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-secondary mt-5 pt-5">
    <div class="row px-xl-5 pt-5">
        <div class="col-lg-6 col-md-6 mb-5 pr-3 pr-xl-5">
            <h5 class="text-secondary text-uppercase mb-4">Свяжитесь с нами</h5>
            <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>childrens-knit@yandex.ru</p>
            <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
        </div>
        <div class="col-lg-4 col-md-5 float-right">
<!--            <div class="row">-->
<!--                <div class="col-md-4 mb-5 float-right">-->
                    <h5 class="text-secondary text-uppercase mb-4">Подписаться на рассылку</h5>
                    <p>пока не стоит, потому что новостей нет</p>
                    <form action="">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Ваш Email" disabled \>
                            <div class="input-group-append">
                                <button class="btn btn-primary disabled">Подписаться</button>
                            </div>
                        </div>
                    </form>
<!--                    <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>-->
<!--                    <div class="d-flex">-->
<!--                        <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>-->
<!--                        <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>-->
<!--                        <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>-->
<!--                        <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
    <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
        <div class="col-md-6 px-xl-0">
            <p class="mb-md-0 text-center text-md-left text-secondary">
                &copy; <a class="text-primary" href="https://childrens-knit.ru/">https://childrens-knit.ru/</a>
                2015 - <?= date('Y')?>.<br />
                Все права защищены.
            </p>
        </div>
        <div class="col-md-6 px-xl-0 text-center text-md-right">
            Сделано <a class="text-primary" href="https://zloradnij.ru">Studio 90-is</a>
        </div>
    </div>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
