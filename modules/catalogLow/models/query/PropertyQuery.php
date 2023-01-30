<?php

namespace app\modules\catalogLow\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\catalogLow\models\Property]].
 *
 * @see \app\modules\catalogLow\models\Property
 */
class PropertyQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => \Yii::$app->params['statusActive']]);
    }
}
