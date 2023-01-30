<?php

namespace app\modules\catalogHeight\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\catalogHeight\models\Product]].
 *
 * @see \app\modules\catalogHeight\models\Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    /**
     * @return ProductQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => \Yii::$app->params['statusActive']]);
    }

    /**
     * @param $alias
     * @return ProductQuery
     */
    public function findByAlias($alias)
    {
        return $this->andWhere(['alias' => $alias]);
    }
}
