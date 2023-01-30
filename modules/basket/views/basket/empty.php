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
        Вы ещё ничего не выбрали
    </div>
    <div class="loader-container"><img src="/images/loadingIcon.gif" /></div>
</div>
<!-- Cart End -->
