<?php
namespace app\modules\catalogHeight;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        \Yii::setAlias('@imgPath', '@web/images/');

        // ...  other initialization code ...
    }
}