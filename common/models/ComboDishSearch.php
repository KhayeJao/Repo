<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ComboDish;

/**
 * ComboDishSearch represents the model behind the search form about `common\models\ComboDish`.
 */
class ComboDishSearch extends ComboDish {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'combo_id', 'dish_id', 'dish_qty'], 'integer'],
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
        $query = ComboDish::find();

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
            'combo_id' => $this->combo_id,
            'dish_id' => $this->dish_id,
            'dish_qty' => $this->dish_qty,
        ]);

//        if (\Yii::$app->user->can('manageRestaurantDishes')) {
//            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
//            $query->andFilterWhere(['IN', 'tbl_combo.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
//        }
        return $dataProvider;
    }

}
