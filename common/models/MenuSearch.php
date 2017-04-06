<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Menu; 

/**
 * MenuSearch represents the model behind the search form about `common\models\Menu`.
 */
class MenuSearch extends Menu {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'parent_id', 'restaurant_id'], 'integer'],
            [['title', 'excerpt', 'image'], 'safe'],
			[['status'], 'string'],
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
        $query = Menu::find();

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
            'parent_id' => $this->parent_id,
            'restaurant_id' => $this->restaurant_id,
            'discount' => $this->discount,
			'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'excerpt', $this->excerpt])
                ->andFilterWhere(['like', 'image', $this->image])
				 ->andFilterWhere(['=', 'status', $this->status]);

        if (\Yii::$app->user->can('manageRestaurantMenu')) {
            $user_model = base\User::findOne(['id' => \yii::$app->user->identity->id]);
            $query->andFilterWhere(['IN', 'tbl_menu.restaurant_id', \yii\helpers\ArrayHelper::getColumn($user_model->restaurants, 'id')]);
        }
        return $dataProvider;
    }
    
    public function searchMenu($params) { 
		 $query = Menu::find();
		 $title =$params['title']; 
		 $title =strtr($title, array('%'=>'\%', '_'=>'\_'));
		 $restaurant_id= $params['restaurant_id']; 
		 $query->join ( 'inner join', 'tbl_dish as tdish', $on = 'tbl_menu.id = tdish.menu_id', $params = [] )
			->Where(['like', 'tbl_menu.title',$title]) 
			->orWhere(['like', 'tdish.title',$title])  
			->andFilterWhere(['=', 'tbl_menu.restaurant_id', $restaurant_id])
			->andFilterWhere(['=', 'tbl_menu.status', 'Active'])
			->andFilterWhere(['=', 'tdish.status', 'Active']);  
			
			$query->orderBy(['order' => SORT_ASC,]); 
                $result = $query->all();   
                return $result;
    }

}
