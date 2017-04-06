<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantPhone;

/**
 * RestaurantPhoneSearch represents the model behind the search form about `common\models\RestaurantPhone`.
 */
class RestaurantPhoneSearch extends RestaurantPhone {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'restaurant_id'], 'integer'],
            [['label', 'phone_no'], 'safe'],
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
        $query = RestaurantPhone::find();

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
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
                ->andFilterWhere(['like', 'phone_no', $this->phone_no]);

        if (\Yii::$app->user->can('manageRestaurantPhone')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_restaurant_phone.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }
        return $dataProvider;
    }

}
