<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Restaurant;

/**
 * RestaurantSearch represents the model behind the search form about `common\models\Restaurant`.
 */
class RestaurantSearch extends Restaurant {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_id', 'service_charge', 'table_slot_time', 'is_featured'], 'integer'],
            [['title', 'slogan', 'address', 'area', 'city', 'latitude', 'longitude', 'logo', 'open_datetime_1', 'close_datetime_1', 'open_datetime_2', 'close_datetime_2', 'scharge_type', 'who_delivers', 'meta_keywords', 'meta_description', 'coupon_text', 'featured_image', 'status', 'created_date'], 'safe'],
            [['min_amount', 'delivery_network', 'tax', 'vat', 'kj_share', 'prior_table_booking_time', 'avg_rating'], 'number'],
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

        $query = Restaurant::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $this->load($params);
        if (\Yii::$app->user->can('editRestaurant')) {
            $user_filter = \yii::$app->user->identity->id;
        } else {
            $user_filter = $this->user_id;
        }



        if ($this->created_date) {
            $month = date('m', strtotime($this->created_date));
            $year = date('Y', strtotime($this->created_date));
            $query->andFilterWhere([
                'MONTH(created_date)' => $month,
                'YEAR(created_date)' => $year,
            ]);
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $user_filter,
            'min_amount' => $this->min_amount,
            'delivery_network' => $this->delivery_network,
            'tax' => $this->tax,
            'vat' => $this->vat,
            'service_charge' => $this->service_charge,
            'kj_share' => $this->kj_share,
            'prior_table_booking_time' => $this->prior_table_booking_time,
            'table_slot_time' => $this->table_slot_time,
            'avg_rating' => $this->avg_rating,
            'is_featured' => $this->is_featured,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'slogan', $this->slogan])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'area', $this->area])
                ->andFilterWhere(['like', 'city', $this->city])
                ->andFilterWhere(['like', 'latitude', $this->latitude])
                ->andFilterWhere(['like', 'longitude', $this->longitude])
                ->andFilterWhere(['like', 'logo', $this->logo])
                ->andFilterWhere(['like', 'open_datetime_1', $this->open_datetime_1])
                ->andFilterWhere(['like', 'close_datetime_1', $this->close_datetime_1])
                ->andFilterWhere(['like', 'open_datetime_2', $this->open_datetime_2])
                ->andFilterWhere(['like', 'close_datetime_2', $this->close_datetime_2])
                ->andFilterWhere(['like', 'scharge_type', $this->scharge_type])
                ->andFilterWhere(['like', 'who_delivers', $this->who_delivers])
                ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
                ->andFilterWhere(['like', 'meta_description', $this->meta_description])
                ->andFilterWhere(['like', 'coupon_text', $this->coupon_text])
                ->andFilterWhere(['like', 'featured_image', $this->featured_image])
                ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }

}
