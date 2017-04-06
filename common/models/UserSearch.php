<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\base\User`.
 */
class UserSearch extends User {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'first_name', 'last_name', 'mobile_no', 'fb_id', 'fb_profile', 'mobile_v_code', 'is_mobile_verified', 'is_email_verified', 'type', 'auth_key', 'password_hash', 'password_reset_token', 'last_login_ip'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'first_name', $this->first_name])
                ->andFilterWhere(['like', 'last_name', $this->last_name])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'fb_id', $this->fb_id])
                ->andFilterWhere(['like', 'fb_profile', $this->fb_profile])
                ->andFilterWhere(['like', 'mobile_v_code', $this->mobile_v_code])
                ->andFilterWhere(['like', 'is_mobile_verified', $this->is_mobile_verified])
                ->andFilterWhere(['like', 'is_email_verified', $this->is_email_verified]);
        if ((\yii::$app->user->can('viewUserList')) AND ( !\yii::$app->user->can('manageUsers'))) { // TELECALLER ACCESS
            $query->andFilterWhere(['like', 'type', 'customer']);
        }
        $query->andFilterWhere(['like', 'type', $this->type]);
        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip]);

        return $dataProvider;
    }

    public function exportCustomer() {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query->andWhere(['type' => 'customer']);
        return $dataProvider;
    }

}
