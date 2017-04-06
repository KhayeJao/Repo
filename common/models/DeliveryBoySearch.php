<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\base\DeliveryBoy;
use yii\web\ForbiddenHttpException;

/**
 * CuisineSearch represents the model behind the search form about `common\models\Cuisine`.
 */
class DeliveryBoySearch extends DeliveryBoy {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['first_name', 'username','mobile_no','last_name', 'email', 'status'], 'safe'],
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
        $query = DeliveryBoy::find();
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
 
        $this->load($params);
		$user_id = yii::$app->user->identity->id;
		$restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $user_id]);
		
		 if (yii::$app->user->identity->type=='admin') { 
			
			
			 if($_GET['restaurant_id']){
				  $deliveryBoy_filter =$_GET['restaurant_id']; 
			 }else{
				 $deliveryBoy_filter = '';
			 }
			
        } else {
			
             $deliveryBoy_filter = $restaurant_model->id;
        }
      

        if (!$this->validate()) {
        // uncomment the following line if you do not want to any records when validation fails
       //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'restaurant_id' => $deliveryBoy_filter,
        ]);
     
        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'first_name', $this->first_name])
                ->andFilterWhere(['like', 'last_name', $this->last_name]) 
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no]) 
                ->andFilterWhere(['like', 'status', $this->status]);

        if (!\Yii::$app->user->can('DeliveryBoy')) {
            throw new ForbiddenHttpException("You are not allow to use this page");
        }
        return $dataProvider;
    }

}
