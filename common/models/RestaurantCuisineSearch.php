<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantCuisine;

/**
 * RestaurantCuisineSearch represents the model behind the search form about `common\models\RestaurantCuisine`.
 */
class RestaurantCuisineSearch extends RestaurantCuisine {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'restaurant_id', 'cuisine_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = RestaurantCuisine::find();

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
            'restaurant_id' => $this->restaurant_id,
            'cuisine_id' => $this->cuisine_id,
        ]);

        if (\Yii::$app->user->can('manageRestaurantCuisine')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_restaurant_cuisine.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }

        return $dataProvider;
    }

}
