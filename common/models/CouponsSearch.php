<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Coupons;

/**
 * CouponsSearch represents the model behind the search form about `common\models\Coupons`.
 */
class CouponsSearch extends Coupons {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['code', 'coupon_key', 'title', 'description', 'type', 'coupon_perameter', 'notify', 'status', 'created_on'], 'safe'],
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

        $query = Coupons::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_on' => SORT_DESC,
                ]
            ],
        ]);


        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
//            'created_on' => $this->created_on,
        ]);
        $query->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'coupon_key', $this->coupon_key])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'type', $this->type])
//                ->andFilterWhere(['like', 'coupon_perameter', $this->coupon_perameter])
                ->andFilterWhere(['like', 'notify', $this->notify])
                ->andFilterWhere(['like', 'status', $this->status]);
//        
        if (\Yii::$app->user->can('manageRestaurantCoupons')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->innerJoin('tbl_restaurant_coupons', 'tbl_coupons.id = tbl_restaurant_coupons.coupon_id');
            $query->andFilterWhere(['IN', 'tbl_restaurant_coupons.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }
        return $dataProvider;
    }

}
