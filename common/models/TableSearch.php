<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Table;

/**
 * TableSearch represents the model behind the search form about `common\models\Table`.
 */
class TableSearch extends Table {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'restaurant_id', 'no_of_seats', 'table_id'], 'integer'],
            [['price'], 'number'],
            [['status'], 'safe'],
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
        $query = Table::find();

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
            'no_of_seats' => $this->no_of_seats,
            'price' => $this->price,
            'table_id' => $this->table_id,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);
        if (\Yii::$app->user->can('manageRestaurantTables')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_table.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }
        return $dataProvider;
    }

}
