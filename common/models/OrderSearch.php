<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_id', 'restaurant_id', 'order_items', 'order_ip'], 'integer'],
            [['order_unique_id', 'affiliate_order_id', 'user_full_name', 'mobile', 'email', 'address_line_1', 'address_line_2', 'area', 'city', 'pincode', 'delivery_time', 'coupon_code', 'discount_text', 'tax_text', 'vat_text', 'service_charge_text', 'comment', 'booking_time', 'order_status_change_datetime', 'order_status_change_reason', 'status'], 'safe'],
            [['discount_amount', 'tax', 'vat', 'service_charge'], 'number'],
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
        $query = Order::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['delivery_time' => SORT_DESC]]
        ]);

        $this->load($params);
        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }
        if (\Yii::$app->user->can('editRestaurant')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_order.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }
        if ($this->booking_time) {
            $month = date('m', strtotime($this->booking_time));
            $year = date('Y', strtotime($this->booking_time));
        } else {
            $month = "";
            $year = "";
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'restaurant_id' => $this->restaurant_id,
            'delivery_time' => $this->delivery_time,
            'discount_amount' => $this->discount_amount,
            'order_items' => $this->order_items,
            'tax' => $this->tax,
            'vat' => $this->vat,
            'service_charge' => $this->service_charge,
            //'booking_time' => $this->booking_time,
            'MONTH(booking_time)' => $month,
            'YEAR(booking_time)' => $year,
            'order_status_change_datetime' => $this->order_status_change_datetime,
            'order_ip' => $this->order_ip,
        ]);

        $query->andFilterWhere(['like', 'order_unique_id', $this->order_unique_id])
                ->andFilterWhere(['like', 'affiliate_order_id', $this->affiliate_order_id])
                ->andFilterWhere(['like', 'user_full_name', $this->user_full_name])
                ->andFilterWhere(['like', 'mobile', $this->mobile])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'address_line_1', $this->address_line_1])
                ->andFilterWhere(['like', 'address_line_2', $this->address_line_2])
                ->andFilterWhere(['like', 'area', $this->area])
                ->andFilterWhere(['like', 'city', $this->city])
                ->andFilterWhere(['like', 'pincode', $this->pincode])
                ->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
                ->andFilterWhere(['like', 'discount_text', $this->discount_text])
                ->andFilterWhere(['like', 'tax_text', $this->tax_text])
                ->andFilterWhere(['like', 'vat_text', $this->vat_text])
                ->andFilterWhere(['like', 'service_charge_text', $this->service_charge_text])
                ->andFilterWhere(['like', 'comment', $this->comment])
                ->andFilterWhere(['like', 'order_status_change_reason', $this->order_status_change_reason])
                ->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }

}
