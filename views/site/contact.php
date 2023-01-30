<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

$this->title = 'Связаться с нами';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid pb-3">
    <div class="">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

            <div class="alert alert-success">
                Спасибо, наши сотрудники рассмотрят Ваше обращение в ближайшее время
            </div>

        <?php else: ?>

            <p>
                Если у вас есть деловые запросы или возникли вопросы по работе сайта,
                пожалуйста, заполните следующую форму, чтобы связаться с нами.
                Спасибо.
            </p>

            <div class="row px-xl-5">
                <div class="col-lg-5">

                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'subject') ?>

                        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                        ]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        <?php endif; ?>
    </div>

    <div class="organization" itemscope itemtype="http://schema.org/Organization">
        <span class="block" itemprop="name">Детский трикотаж</span>
        Контакты:
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            Адрес:
            <span class="block" itemprop="streetAddress">Центральная, 88/1</span>
            <span class="block" itemprop="postalCode">633430</span>
            <span itemprop="addressLocality">Новосибирская обл, Тогучинский р-н, село Карпысак</span>,
        </div>
        Телефон:<span itemprop="telephone">+7 960 790–63–53</span>,
        Электронная почта: <span itemprop="email">childrens-knit@yandex.ru</span>
    </div>

</div>
