<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FavDish;

/**
* FavDishSearch represents the model behind the search form about `common\models\FavDish`.
*/
class FavDishSearch extends FavDish
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'dish_id', 'user_id'], 'integer'],
            [['created_on'], 'safe'],
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
$query = FavDish::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'dish_id' => $this->dish_id,
            'user_id' => $this->user_id,
            'created_on' => $this->created_on,
        ]);

return $dataProvider;
}
}