<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Ordertablestatus;

/**
 * UserSearch represents the model behind the search form about `common\models\base\User`.
 */
class OrdertablestatusSearch extends Ordertablestatus {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id','restaurant_id','no_of_seats','table_no'], 'integer'],
            [['table_no','date','status'], 'safe']
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
		$date =date('Y-m-d');
        $query = Ordertablestatus::find();  
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]]
        ]);
        $query->andFilterWhere([ 
            'DATE(date)' =>$date,
            'status'=>'Pending', 
        ]);

        $this->load($params); 
        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
           /// $query->where('0=1');
          
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'date' => $this->date,
             
        ]);

        $query->andFilterWhere(['like', 'table_no', $this->table_no]);
         

        return $dataProvider;
    }

    

}
