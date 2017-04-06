<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DishTopping;

/**
* DishToppingSearch represents the model behind the search form about `common\models\DishTopping`.
*/
class DishToppingSearch extends DishTopping
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'topping_group_id', 'topping_id'], 'integer'],
            [['price'], 'number'],
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
$query = DishTopping::find();

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
            'topping_group_id' => $this->topping_group_id,
            'topping_id' => $this->topping_id,
            'price' => $this->price,
        ]);

return $dataProvider;
}
}