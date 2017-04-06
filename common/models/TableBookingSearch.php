<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TableBooking;
use yii\helpers\ArrayHelper;
/**
 * TableBookingSearch represents the model behind the search form about `common\models\TableBooking`.
 */
class TableBookingSearch extends TableBooking {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_id'], 'integer'],
            [['checkin_datetime', 'booking_date', 'comment'], 'safe'],
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
        $query = TableBooking::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

        if (\Yii::$app->user->can('editRestaurant')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $table_arr = ArrayHelper::getColumn( base\Table::find()->where(['IN', 'restaurant_id',ArrayHelper::getColumn($user_model->restaurants, 'id')])->all(), 'id');
            $query->joinWith('tableBookingTables');
            $query->where(['IN', 'tbl_table_booking_tables.table_id', $table_arr]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'checkin_datetime' => $this->checkin_datetime,
            'booking_date' => $this->booking_date,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }

}
