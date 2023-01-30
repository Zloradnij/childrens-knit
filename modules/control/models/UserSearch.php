<?php

namespace app\modules\control\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserShortSearch represents the model behind the search form about `app\models\UserShort`.
 */
class UserSearch extends User
{
    public $fullName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email','fullName'], 'safe'],
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
        $query = $this->setQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'fullName' => [
                    'asc' => ['profile.second_name' => SORT_ASC, 'profile.name' => SORT_ASC],
                    'desc' => ['profile.second_name' => SORT_DESC, 'profile.name' => SORT_DESC],
                    'label' => 'Full Name',
                    'default' => SORT_ASC
                ],
                'country_id'
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            /**
             * The following line will allow eager loading with country data
             * to enable sorting by country on initial loading of the grid.
             */
            $query->joinWith([$this->profileRelations]);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);
        $query->joinWith([$this->profileRelations => function ($q) {
            $q->where('profile.second_name LIKE "%' . $this->fullName . '%"' .
                'OR profile.name LIKE "%' . $this->fullName . '%"');
        }]);
        return $dataProvider;
    }

    protected function setQuery(){
        return User::find();
    }
}

