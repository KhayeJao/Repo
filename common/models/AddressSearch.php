<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Address;

/**
* AddressSearch represents the model behind the search form about `common\models\Address`.
*/
class AddressSearch extends Address
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'user_id', 'area', 'city', 'pincode', 'country'], 'integer'],
            [['address_line_1', 'address_line_2', 'created_on'], 'safe'],
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
$query = Address::find();

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
            'user_id' => $this->user_id,
            'area' => $this->area,
            'city' => $this->city,
            'pincode' => $this->pincode,
            'country' => $this->country,
            'created_on' => $this->created_on,
        ]);

        $query->andFilterWhere(['like', 'address_line_1', $this->address_line_1])
            ->andFilterWhere(['like', 'address_line_2', $this->address_line_2]);

return $dataProvider;
}
}