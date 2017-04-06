<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantReview;

/**
 * RestaurantReviewSearch represents the model behind the search form about `common\models\RestaurantReview`.
 */
class RestaurantReviewSearch extends RestaurantReview {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_id', 'restaurant_id', 'rate'], 'integer'],
            [['title', 'comment', 'created_on', 'status'], 'safe'],
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
        $query = RestaurantReview::find();

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
            'restaurant_id' => $this->restaurant_id,
            'rate' => $this->rate,
            'created_on' => $this->created_on,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'comment', $this->comment])
                ->andFilterWhere(['like', 'status', $this->status]);

        if (\Yii::$app->user->can('manageRestaurantCuisine')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_restaurant_review.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }

        return $dataProvider;
    }

}
