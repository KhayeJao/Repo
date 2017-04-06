<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MenuMaster; 

/**
 * MenuSearch represents the model behind the search form about `common\models\MenuMaster`.
 */
class MenuMasterSearch extends MenuMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'parent_id'], 'integer'],
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
        $query = MenuMaster::find();

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
            'discount' => $this->discount,
			'status' => $this->status,
			
        ]); 
        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'excerpt', $this->excerpt])
                ->andFilterWhere(['like', 'image', $this->image])
				 ->andFilterWhere(['=', 'status', $this->status]);
		

         
        return $dataProvider;
    }
    
    public function searchMenu($params) { 
		 $query = MenuMaster::find();
		 $title = $params['title']; 
		 $title =strtr($title, array('%'=>'\%', '_'=>'\_')); 
		 
		  $query->join ( 'inner join', 'tbl_master_dish as tdish', $on = 'tbl_master_menu.id = tdish.menu_id', $params = [] )
				->Where(['like', 'tbl_master_menu.title',$title]) 
				->orWhere(['like', 'tdish.title',$title])  
				//->andFilterWhere(['=', 'tbl_menu.restaurant_id', $restaurant_id])
				->andFilterWhere(['=', 'tbl_master_menu.status', 'Active'])
				->andFilterWhere(['=', 'tdish.status', 'Active']);  
				$query->orderBy(['order' => SORT_ASC,]); 
         $result = $query->all(); 
         return $result;
    }
    
     
    

}
