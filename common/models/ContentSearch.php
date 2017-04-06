<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Content;
use yii\web\ForbiddenHttpException;

/**
 * ContentSearch represents the model behind the search form about `common\models\Content`.
 */
class ContentSearch extends Content {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['page_key', 'Title', 'content', 'meta_title', 'meta_keywords', 'meta_desctiption', 'status'], 'safe'],
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
        $query = Content::find();

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
        ]);

        $query->andFilterWhere(['like', 'page_key', $this->page_key])
                ->andFilterWhere(['like', 'Title', $this->Title])
                ->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'meta_title', $this->meta_title])
                ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
                ->andFilterWhere(['like', 'meta_desctiption', $this->meta_desctiption])
                ->andFilterWhere(['like', 'status', $this->status]);

        if (!\Yii::$app->user->can('manageSiteContent')) {
            throw new ForbiddenHttpException("You are not allow to use this page");
        }
        return $dataProvider;
    }

}
