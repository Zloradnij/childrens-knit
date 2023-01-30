<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css",
        "/template/lib/animate/animate.min.css",
        "/template/lib/owlcarousel/assets/owl.carousel.min.css",
        "/template/css/style.css",
        'css/site.css',
        'https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css',
    ];
    public $js = [
//        "https://code.jquery.com/jquery-3.6.0.min.js",
        'https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js',
        "https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js",
        "/template/lib/easing/easing.min.js",
        "/template/lib/owlcarousel/owl.carousel.min.js",
        "/template/mail/jqBootstrapValidation.min.js",
        "/template/mail/contact.js",
        "/template/js/main.js",
//        "/template/js/basket.js",
//        "/template/js/catalog.js",
        "/template/js/product.js",
        'https://api-maps.yandex.ru/2.1/?apikey=6633a082-ecd5-4c62-a277-99c6dde468de&lang=ru_RU',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
