<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Dish;

/**
 * DishesSearch represents the model behind the search form about `common\models\Dish`.
 */
class DishesSearch extends Dish {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'menu_id', 'restaurant_id', 'price'], 'integer'],
            [['title', 'description', 'ingredients', 'is_deleted', 'status'], 'safe'],
            [['discount'], 'number'],
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
        $query = Dish::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere([
            'is_deleted' => 'No', //AS WE WANT TO SHOW NON-DELETED RECORDS ONLY!
        ]);
        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'restaurant_id' => $this->restaurant_id,
            'price' => $this->price,
            'discount' => $this->discount,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'ingredients', $this->ingredients])
//                ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]) //AS WE WANT TO SHOW NON-DELETED RECORDS ONLY!
                ->andFilterWhere(['like', 'status', $this->status]);

        if (\Yii::$app->user->can('manageRestaurantDishes')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_dish.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }
        return $dataProvider;
    }

}
