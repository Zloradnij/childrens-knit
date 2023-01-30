<?php

namespace app\modules\catalogHeight\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\catalogHeight\models\Offer]].
 *
 * @see \app\modules\catalogHeight\models\Offer
 */
class OfferQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => \Yii::$app->params['statusActive']]);
    }
}
