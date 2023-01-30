<?php

namespace app\modules\basket\models\search;

use app\modules\basket\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `app\modules\basket\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_user', 'updated_user', 'user_id', 'pay_type_id', 'delivery_id'], 'integer'],
            [['title', 'alias'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function searchForBayer($params)
    {
        $query = Order::find();

        if (empty($params['OrderSearch']['status'])) {
            $query->andFilterWhere(['IN', 'status', array_keys(Order::ORDER_STATUSES_FROM_BASKET)]);
        }

        return $this->searchByQuery($query, $params);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        // add conditions that should always apply here

        return $this->searchByQuery($query, $params);
    }

    public function searchByQuery($query, $params)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'pay_type_id'   => $this->pay_type_id,
            'delivery_id'   => $this->delivery_id,
            'delivery_date' => $this->delivery_date,
            'sale_type_id'  => $this->sale_type_id,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'created_user'  => $this->created_user,
            'updated_user'  => $this->updated_user,
        ]);

        $query->andFilterWhere(['like', 'promo', $this->promo]);

        return $dataProvider;

    }
}
