<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HomeSlider;
use yii\web\ForbiddenHttpException;

/**
 * TrendingSearch represents the model behind the search form about `common\models\HomeSlider`.
 */
class HomeSliderSearch extends HomeSlider {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['title', 'description', 'image', 'status','link'], 'safe'],
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
        $query = HomeSlider::find(); 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['is_type' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'status', $this->status])
				->andFilterWhere(['like', 'link', $this->link]);

        if (!\Yii::$app->user->can('homeSlider')) {
            throw new ForbiddenHttpException("You are not allow to use this page");
        }
        return $dataProvider;
    }

}
