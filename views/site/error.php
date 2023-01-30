<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error container-fluid">
    <div class="row px-xl-5">
        <div class="col-lg-12 table-responsive mb-12">
            <h1><?= Html::encode($this->title) ?></h1>
            <p></p>

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                Возможно, Вы ошиблись при вводе адреса.
            </p>

            <p>
                Пожалуйста, перейдите на главную страницу или воспользуйтесь меню сайта
            </p>

            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </div>
    </div>
</div>
