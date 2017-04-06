<?php

namespace api\modules\v1\controllers;

use yii\helpers\Json;
use api\modules\v1\models\Restaurant;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Cuisine;
use api\modules\v1\models\Dish;
use api\modules\v1\models\RestaurantReview;
use Yii;
use yii\web\Response;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller {

    public $modelClass = 'api\modules\v1\models\Restaurant';
    private $response = array(
        'status' => 0,
        'message' => 0,
    );

    public function init() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
        header("Access-Control-Allow-Headers: x-requested-with");
        parent::init();
        Yii::$app->user->enableSession = FALSE;
//        if (Yii::$app->request->post('app_token') != Yii::$app->params['application_token']) {
//            $this->response['status'] = 0;
//            $this->response['message'] = 'Invalid token';
//            echo Json::encode($this->response);
//            exit;
//        }
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
//        $behaviors['access'] = array(
//            'class' => AccessControl::className(),
//            'rules' => [
//                [
//                    'allow' => true,
//                    'actions' => ['autocomplete', 'view', 'search'],
////                        'roles' => ['@']
//                ]
//            ]
//        );


        return $behaviors;
    }

    public function actionAutocomplete($q) {
//        $q = Yii::$app->request->post('q');
        $query = Restaurant::find();
//        $query->orFilterWhere(['like', 'title', $q])
//                ->orFilterWhere(['like', 'slogan', $q])
//                ->orFilterWhere(['like', 'area', $q])
//                ->orFilterWhere(['like', 'food_type', $q])
//                ->orFilterWhere(['like', 'address', $q])
//                ->andFilterWhere(['=', 'status', 'Active']);
        $query->orFilterWhere(['like', 'title', $q])
                ->andFilterWhere(['=', 'status', 'Active']);
        $query->distinct(TRUE);
        $result = $query->all();
        $restaurant_arr = array();
        foreach ($result as $key => $value) {
            array_push($restaurant_arr, array(
                'id' => $value->title,
                'name' => $value->title,
                'data_id' => $value->id,
                'type' => 'restaurant',
                'url' => $value->restaurantUrl,
            ));
        }
        return $restaurant_arr;
    }
    
    public function actionAutocompletetablebooking($q) {
//        $q = Yii::$app->request->post('q');
        $query = Restaurant::find();
//        $query->orFilterWhere(['like', 'title', $q])
//                ->orFilterWhere(['like', 'slogan', $q])
//                ->orFilterWhere(['like', 'area', $q])
//                ->orFilterWhere(['like', 'food_type', $q])
//                ->orFilterWhere(['like', 'address', $q])
//                ->andFilterWhere(['=', 'status', 'Active']);
        
        $query->orFilterWhere(['like', 'title', $q])
                ->andFilterWhere(['=', 'status', 'Active'])
                ->andFilterWhere(['=', 'does_tablebooking', 'Yes']);
        $query->distinct(TRUE);
        $result = $query->all();
        $restaurant_arr = array();
        foreach ($result as $key => $value) {
            array_push($restaurant_arr, array(
                'id' => $value->title,
                'name' => $value->title,
                'data_id' => $value->id,
                'type' => 'restaurant',
                'url' => $value->restaurantUrl,
            ));
        }
        return $restaurant_arr;
    }

    public function actionView() {
        $id = Yii::$app->request->post('id');
        $user_id = Yii::$app->request->post('user_id');
        $data = array();

        $review_model = new RestaurantReview;
        $restaurant = $this->findModel($id);
        $data['restaurant'] = array(
            'id' => $restaurant->id,
            'title' => $restaurant->title,
            'slogan' => $restaurant->slogan,
            'address' => $restaurant->address,
            'area' => $restaurant->area,
            'city' => $restaurant->city,
            'latitude' => $restaurant->latitude,
            'longitude' => $restaurant->longitude,
            'min_amount' => $restaurant->min_amount,
            'logo' => $restaurant->logo,
            'delivery_network' => $restaurant->delivery_network,
            'food_type' => $restaurant->food_type,
            'open_datetime_1' => $restaurant->open_datetime_1,
            'close_datetime_1' => $restaurant->close_datetime_1,
            'open_datetime_2' => $restaurant->open_datetime_2,
            'close_datetime_2' => $restaurant->close_datetime_2,
            'tax' => $restaurant->tax,
            'vat' => $restaurant->vat,
            'service_charge' => $restaurant->service_charge,
            'scharge_type' => $restaurant->scharge_type,
            'kj_share' => $restaurant->kj_share,
            'prior_table_booking_time' => $restaurant->prior_table_booking_time,
            'table_slot_time' => $restaurant->table_slot_time,
            'who_delivers' => $restaurant->who_delivers,
            'meta_keywords' => $restaurant->meta_keywords,
            'meta_description' => $restaurant->meta_description,
            'coupon_text' => $restaurant->coupon_text,
            'avg_rating' => $restaurant->avg_rating,
            'is_open' => $restaurant->isOpen(),
            'does_tablebooking' => $restaurant->does_tablebooking,
        );

        $data['delivery_areas'] = ArrayHelper::getColumn($restaurant->restaurantAreas, 'area_id');
        $data['delivery_areas_details'] = $restaurant->restaurantAreas;

        $data['timings'] = array();
        for ($time = strtotime($restaurant->open_datetime_1); $time <= strtotime($restaurant->close_datetime_1); $time += 1800) {
            array_push($data['timings'], array(
                'time' => date('H:i', $time),
                'status' => $time > strtotime(date('H:i:s')),
            ));
        }

        for ($time = strtotime($restaurant->open_datetime_2); $time <= strtotime($restaurant->close_datetime_2); $time += 1800) {
            array_push($data['timings'], array(
                'time' => date('H:i', $time),
                'status' => $time > strtotime(date('H:i:s')),
            ));
        }

        $restaurant_cuisines = $restaurant->restaurantCuisines;
        $cuisines_arr = array();
        foreach ($restaurant_cuisines as $key => $value) {
            array_push($cuisines_arr, $value->cuisine->title);
        }
        $restaurant_delivery_areas = $restaurant->restaurantAreas;
        $areas_arr = array();
        foreach ($restaurant_delivery_areas as $key => $value) {
            array_push($areas_arr, $value->area->area_name);
        }
        $data['restaurant']['restaurant_cuisines'] = implode(', ', $cuisines_arr);
        $data['restaurant']['restaurant_dareas'] = (count($areas_arr) > 0 ? implode(', ', $areas_arr) : 'Not Specified');

        $data['menu'] = array();

        foreach ($restaurant->menus as $menu_key => $menu_value) {
            $menu_dishes = array();
            foreach ($menu_value->dishes as $dish_key => $dish_value) {
                $dish_arr = $dish_value->attributes;
                if ($dish_value->toppingGroups) {
                    $group_arr = array();
                    foreach ($dish_value->toppingGroups as $group_key => $group_value) {
                        $group_arr1 = $group_value->attributes;
                        $dish_toppings = array();
                        foreach ($group_value->dishToppings as $dish_topping_key => $dish_topping_value) {
                            $topping_arr = $dish_topping_value->attributes;
                            $topping_arr['topping_info'] = $dish_topping_value->topping->attributes;
                            array_push($dish_toppings, $topping_arr);
                        }

                        $group_arr1['dish_toppings'] = $dish_toppings;
                        array_push($group_arr, $group_arr1);
                    }
                    $dish_arr['topping_groups'] = $group_arr;
                }
                array_push($menu_dishes, $dish_arr);
            }
            $menu_info = $menu_value->attributes;
            $menu_info['dishes'] = $menu_dishes;
            array_push($data['menu'], $menu_info);
        }

        $data['combos'] = array();

        foreach ($restaurant->combos as $combo_key => $combo_value) {
            array_push($data['combos'], array(
                'id' => $combo_value->id,
                'title' => $combo_value->title,
                'price' => $combo_value->price,
                'combo_type' => $combo_value->combo_type,
            ));
        }

        $data['gallery_items'] = array();
        $data['user_fav_dishes'] = array();

        $already_reviewed = FALSE;
        if ($user_id) {
            $user_review_model = RestaurantReview::findOne(['restaurant_id' => $id, 'user_id' => $user_id]);
            if ($user_review_model) {
                $already_reviewed = TRUE;
            }
            $data['user_fav_dishes'] = ArrayHelper::getColumn(\common\models\FavDish::findAll(['user_id' => $user_id]), 'dish_id');
        }

        foreach ($restaurant->restaurantImages as $images_key => $images_value) {
            array_push($data['gallery_items'], array(
                'url' => $images_value->imageUrl,
                'src' => $images_value->resizeImageUrl,
                'options' => array('title' => $images_value->title),
            ));
        }

        $data['already_reviewed'] = $already_reviewed;
        $data['reviews'] = $restaurant->restaurantReviews;
        if ($restaurant) {
            $this->response['status'] = 1;
            $this->response['message'] = "Restaurant details";
            $this->response['data'] = $data;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "Restaurant not found";
        }
        return $this->response;
    }

    public function actionSearch($q, $page = 0, $type = 'restaurant', $orderby = 'created_date') {
        $data = array(
            'q' => $q,
            'type' => $type,
            'orderby' => $orderby,
//            'filter' => $filter,
            'cuisine_id' => ''
        );
        $sort_order = SORT_DESC;
        if ($orderby == 'min_amount') {
            $sort_order = SORT_ASC;
        }
        $query = Restaurant::find()->orderBy([$orderby => $sort_order])->where(['tbl_restaurant.status' => 'Active']);
        switch ($type) {
            case 'restaurant':
                $query->andWhere('title LIKE :title_query')
                        ->addParams([':title_query' => '%' . $q . '%']);
                $restaurants_arr = ArrayHelper::getColumn($query->all(), 'id');
                break;
            case 'cuisine':
                $cuisine_query = Cuisine::find()->where('title LIKE :title_query')
                                ->addParams([':title_query' => '%' . $q . '%'])->all();
                $cuisine_id_arr = ArrayHelper::getColumn($cuisine_query, 'id');
                $data['cuisine_id'] = $cuisine_id_arr[0];
                $query->innerJoinWith('restaurantCuisines')->andWhere(['IN', 'cuisine_id', $cuisine_id_arr]);
                break;
            case 'dish':
                $query->innerJoinWith('dishes')->andWhere('tbl_dish.title LIKE :title_query')
                        ->addParams([':title_query' => '%' . $q . '%']);
                $restaurants_arr = ArrayHelper::getColumn($query->all(), 'id');
                break;
            case 'area':
                $query->andWhere('tbl_restaurant.area LIKE :area_query')
                        ->addParams([':area_query' => '%' . $q . '%']);
                        //->orWhere('tbl_restaurant.title LIKE :title_query')
                        //->addParams([':title_query' => '%' . $q . '%']);
                $restaurants_arr = ArrayHelper::getColumn($query->all(), 'id');
//                $cuisines_list = Cuisine::find()->where(['status' => 'Active'])->all();
                break;

            default:
                $query->andWhere('title LIKE :title_query')
                        ->addParams([':title_query' => '%' . $q . '%']);
                $restaurants_arr = ArrayHelper::getColumn($query->all(), 'id');
                break;
        }
//        if ($filter == 'deal') {
//            $query->innerJoinWith('restaurantActiveCoupons');
//        }
        $pagination = new \yii\data\Pagination(['defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $pagination->setPage($page);
        $query->limit($pagination->limit);
        $query->offset($pagination->offset);
        $search_result = $query->all();
        $data['search_result'] = array();
        $data['next_page'] = $pagination->page + 1;
        if (!$search_result) {
            $data['next_page'] = FALSE;
        }
        foreach ($search_result as $search_result_key => $search_result_value) {
            $restaurant_cuisines = $search_result_value->restaurantCuisines;
            $cuisines_arr = array();
            foreach ($restaurant_cuisines as $key => $value) {
                array_push($cuisines_arr, $value->cuisine->title);
            }
            $restaurant_delivery_areas = $search_result_value->restaurantAreas;
            $areas_arr = array();
            foreach ($restaurant_delivery_areas as $key => $value) {
                array_push($areas_arr, $value->area->area_name);
            }
            $restaurant_info_arr = array(
                'id' => $search_result_value->id,
                'title' => $search_result_value->title,
                'slogan' => $search_result_value->slogan,
                'address' => $search_result_value->address,
                'area' => $search_result_value->area,
                'city' => $search_result_value->city,
                'logo' => $search_result_value->logo,
                'food_type' => $search_result_value->food_type,
                'open_datetime_1' => $search_result_value->open_datetime_1,
                'close_datetime_1' => $search_result_value->close_datetime_1,
                'open_datetime_2' => $search_result_value->open_datetime_2,
                'close_datetime_2' => $search_result_value->close_datetime_2,
                'avg_rating' => $search_result_value->avg_rating,
                'restaurant_cuisines' => implode(', ', $cuisines_arr),
                'restaurant_dareas' => (count($areas_arr) > 0 ? implode(', ', $areas_arr) : 'Not Specified'),
                'min_amount' => $search_result_value->min_amount,
                'created_date' => $search_result_value->created_date,
                'is_discount_available' => (count($search_result_value->restaurantActiveCoupons) > 0 ? TRUE : FALSE),
            );
            array_push($data['search_result'], $restaurant_info_arr);
        }
        if ($data) {
            $this->response['data'] = $data;
            $this->response['status'] = 1;
            $this->response['message'] = 'List of restaurants';
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'No Restaurants found';
        }
        return $this->response;
    }

    public function actionLocationsearch($orderby = 'created_date', $page = 0) {
        $data = array(
            'orderby' => $orderby,
            'type' => 'nearby',
//            'filter' => $filter,
        );
        
        $sort_order = SORT_DESC;
        if ($orderby == 'min_amount') {
            $sort_order = SORT_ASC;
        }
        $latitude = Yii::$app->request->post('latitude');
        $longitude = Yii::$app->request->post('longitude');
        $radius = 3.1;
        $query = Restaurant::find()->orderBy([$orderby => $sort_order], "((69.1 * (latitude - " . $latitude . ")) * (69.1 * (latitude - " . $latitude . "))) + ((69.1 * (longitude - " . $longitude . ") * COS(" . $latitude . " / 57.3)) * (69.1 * (longitude - " . $longitude . ") * COS(" . $latitude . " / 57.3))")->where(['tbl_restaurant.status' => 'Active']);
        $query->where("((69.1 * (latitude - " . $latitude . ")) *  (69.1 * (latitude - " . $latitude . "))) + ((69.1 * (longitude - " . $longitude . ") * COS(" . $latitude . " / 57.3)) * (69.1 * (longitude - " . $longitude . ") * COS(" . $latitude . " / 57.3))) < " . pow($radius, 2) . " && tbl_restaurant.status = 'Active'");

//        if ($filter == 'deal') {
//            $query->innerJoinWith('restaurantActiveCoupons');
//        }
        $pagination = new \yii\data\Pagination(['defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $pagination->setPage($page);
        $query->limit($pagination->limit);
        $query->offset($pagination->offset);
//        $query->addSelect("((69.1 * (latitude - " . $latitude . ")) *  (69.1 * (latitude - " . $latitude . "))) + ((69.1 * (longitude - " . $longitude . ") * COS(" . $latitude . " / 57.3)) * (69.1 * (longitude - " . $longitude . ") * COS(" . $latitude . " / 57.3)))  AS distance");

        $search_result = $query->all();
        $data['search_result'] = array();

        $data['next_page'] = $pagination->page + 1;
        if (!$search_result) {
            $data['next_page'] = FALSE;
        }
        foreach ($search_result as $search_result_key => $search_result_value) {
            $restaurant_cuisines = $search_result_value->restaurantCuisines;
            $cuisines_arr = array();
            foreach ($restaurant_cuisines as $key => $value) {
                array_push($cuisines_arr, $value->cuisine->title);
            }
            $restaurant_delivery_areas = $search_result_value->restaurantAreas;
            $areas_arr = array();
            foreach ($restaurant_delivery_areas as $key => $value) {
                array_push($areas_arr, $value->area->area_name);
            }
            $restaurant_info_arr = array(
                'id' => $search_result_value->id,
                'title' => $search_result_value->title,
                'slogan' => $search_result_value->slogan,
                'address' => $search_result_value->address,
                'area' => $search_result_value->area,
                'city' => $search_result_value->city,
                'logo' => $search_result_value->logo,
                'food_type' => $search_result_value->food_type,
                'open_datetime_1' => $search_result_value->open_datetime_1,
                'close_datetime_1' => $search_result_value->close_datetime_1,
                'open_datetime_2' => $search_result_value->open_datetime_2,
                'close_datetime_2' => $search_result_value->close_datetime_2,
                'avg_rating' => $search_result_value->avg_rating,
                'restaurant_cuisines' => implode(', ', $cuisines_arr),
                'restaurant_dareas' => (count($areas_arr) > 0 ? implode(', ', $areas_arr) : 'Not Specified'),
                'min_amount' => $search_result_value->min_amount,
                'created_date' => $search_result_value->created_date,
                'is_discount_available' => (count($search_result_value->restaurantActiveCoupons) > 0 ? TRUE : FALSE),
            );
            array_push($data['search_result'], $restaurant_info_arr);
        }
        if ($data) {
            $this->response['data'] = $data;
            $this->response['status'] = 1;
            $this->response['message'] = 'List of restaurants';
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'No Restaurants found';
        }
        return $this->response;
    }
    
    public function actionFeatured() {
        $data = array();
        $data['search_result'] = array();
        $search_result = Restaurant::findAll(['is_featured' => '1', 'status' => 'Active']);
        
        foreach ($search_result as $search_result_key => $search_result_value) {
            $restaurant_cuisines = $search_result_value->restaurantCuisines;
            $cuisines_arr = array();
            foreach ($restaurant_cuisines as $key => $value) {
                array_push($cuisines_arr, $value->cuisine->title);
            }
            $restaurant_delivery_areas = $search_result_value->restaurantAreas;
            $areas_arr = array();
            foreach ($restaurant_delivery_areas as $key => $value) {
                array_push($areas_arr, $value->area->area_name);
            }
            $restaurant_info_arr = array(
                'id' => $search_result_value->id,
                'title' => $search_result_value->title,
                'slogan' => $search_result_value->slogan,
                'address' => $search_result_value->address,
                'area' => $search_result_value->area,
                'city' => $search_result_value->city,
                'logo' => $search_result_value->logo,
                'food_type' => $search_result_value->food_type,
                'open_datetime_1' => $search_result_value->open_datetime_1,
                'close_datetime_1' => $search_result_value->close_datetime_1,
                'open_datetime_2' => $search_result_value->open_datetime_2,
                'close_datetime_2' => $search_result_value->close_datetime_2,
                'avg_rating' => $search_result_value->avg_rating,
                'restaurant_cuisines' => implode(', ', $cuisines_arr),
                'restaurant_dareas' => (count($areas_arr) > 0 ? implode(', ', $areas_arr) : 'Not Specified'),
                'min_amount' => $search_result_value->min_amount,
                'created_date' => $search_result_value->created_date,
                'is_discount_available' => (count($search_result_value->restaurantActiveCoupons) > 0 ? TRUE : FALSE),
            );
            array_push($data['search_result'], $restaurant_info_arr);
        }
        
        if ($data) {
            $this->response['data'] = $data;
            $this->response['status'] = 1;
            $this->response['message'] = 'List of restaurants';
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'No Featured Restaurants found';
        }
        return $this->response;        
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Restaurant::findOne(['id' => $id, 'status' => 'Active'])) !== null) {
            return $model;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'Resaurant not found';
        }
    }

}
