<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class ShopController extends Controller
{
    /**
     * Метод устанавливает мета-теги для страницы сайта
     * @param string $title
     * @param string $keywords
     * @param string $description
     */
    protected function setMetaTags($title = '', $keywords = '', $description = '')
    {
        $this->view->title = $title ?: Yii::$app->params['defaultTitle'];
        $this->view->registerMetaTag([
            'name'    => 'keywords',
            'content' => $keywords ?: Yii::$app->params['defaultKeywords'],
        ]);
        $this->view->registerMetaTag([
            'name'    => 'description',
            'content' => $description ?: Yii::$app->params['defaultDescription'],
        ]);
    }
}
