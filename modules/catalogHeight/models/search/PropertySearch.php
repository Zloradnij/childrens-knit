<?php

namespace app\modules\catalogHeight\models\search;

use app\modules\catalogHeight\models\Property;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PropertySearch represents the model behind the search form about `app\modules\catalogHeight\models\Property`.
 */
class PropertySearch extends Property
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'property_type_id',
                    'status',
                    'created_at',
                    'updated_at',
                    'created_user',
                    'updated_user',
                ],
                'integer',
            ],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Property::find();

        // add conditions that should always apply here

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
            'id'               => $this->id,
            'status'           => $this->status,
            'property_type_id' => $this->property_type_id,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'created_user'     => $this->created_user,
            'updated_user'     => $this->updated_user,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['title', 'alias', $this->alias]);

        return $dataProvider;
    }
}
