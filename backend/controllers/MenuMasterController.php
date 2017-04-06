<?php

namespace backend\controllers;

use common\models\MenuMaster;
use common\models\MenuMasterSearch; 
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;
use yii\helpers\Json;
use common\models\Restaurant;

/**
 * MenuMasterController implements the CRUD actions for MenuMaster model.
 */
class MenuMasterController extends Controller {

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete','menu', 'Refreshsearch','updateajax'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        // echo "hi..";die;
        if(yii::$app->user->identity->type=='admin'){ 
               
                $searchModel = new MenuMasterSearch;
                $dataProvider = $searchModel->search($_GET); 
                Tabs::clearLocalStorage(); 
                Url::remember();
                \Yii::$app->session['__crudReturnUrl'] = null; 
                return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
            
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
       
        
                $model = new MenuMaster;
                try {
                    if ($model->load(Yii::$app->request->post())) {
                        // process uploaded image file instance
                        $image = $model->uploadImage();
                        if ($model->save()) {
                            // upload only if valid uploaded file instance found
                            if ($image !== false) {
                                $path = $model->getImageFile();
                                $image->saveAs($path);
                                $destinationPath = Yii::$app->params['MImagePath'] . "resize/" . $model->image;
                                CommonHelper::resize($path, $destinationWidth = 250, $destinationHeight = 250, $destinationPath);
                            }


                            return $this->redirect(Url::previous());
                            //return $this->redirect(['index', 'modId' => $model->modId]);
                        } else {
                            // error
                        }
                    } elseif (!\Yii::$app->request->isPost) {
                        $model->load($_GET);
                    }
                } catch (\Exception $e) {
                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e
                                    ->getMessage();
                    $model->addError('_exception', $msg);
                }
//                try {
//                    if ($model->load($_POST) && $model->save()) {
//                        return $this->redirect(Url::previous());
//                    } elseif (!\Yii::$app->request->isPost) {
//                        $model->load($_GET);
//                    }
//                } catch (\Exception $e) {
//                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
//                    $model->addError('_exception', $msg);
//                }
                return $this->render('create', ['model' => $model]);
            
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) { 
		/*
		$params  = Yii::$app->request->queryParams;
		$ids     = $params['Menu']['restaurant_id'];
		$results = Menu::findAll(['restaurant_id' => $ids]);
        $count   = count($results); 
        */
        $model = $this->findModel($id);
        $oldFile = $model->getImageFile();
        $oldRFile = $model->getResizeImageFile();
        $oldAvatar = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            // process uploaded image file instance
            $image = $model->uploadImage();
            // revert back if no valid file instance uploaded
            if ($image === false) {
                $model->image = $oldAvatar;
            }
            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($image !== false) {  // delete old and overwrite
                    if (file_exists($oldFile))
                        unlink($oldFile);
                    if (file_exists($oldRFile))
                        unlink($oldRFile);
                    $path = $model->getImageFile();
                    $image->saveAs($path); 
                    $destinationPath = Yii::$app->params['MImagePath'] . "resize/" . $model->image;
                    CommonHelper::resize($path, $destinationWidth = 250, $destinationHeight = 250, $destinationPath);
                }
                return $this->redirect(Url::previous());
                //return $this->redirect(['view', 'id' => $model->_id]);
            } else {
                // error in saving model
            }
        } else {
            return $this->render('update', ['model' => $model, 'restaurant_id' => $id]);
        }
//        if ($model->load($_POST) && $model->save()) {
//            $this->redirect(Url::previous());
//        } else {
//            return $this->render('update', [
//                        'model' => $model,
//            ]);
//        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $model = $this->findModel($id);
            if ($model->delete()) {
                if (!$model->deleteImage()) {
                    Yii::$app->session->setFlash('error', 'Error deleting image');
                }
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->setFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            $this->redirect($url);
        } else {
            $this->redirect(['index']);
        }
    }
    
    
    
    
    public function actionRefreshsearch($data) {  
        $menudata = \common\models\MenuMasterSearch::MenuMasterSearch($data); 
        $arr =array();
        $arr_menu_id =array();
        $menudata_list="";
      	

 $menudata_list .=' <div class="col-md-3 no-padding col-sm-3">
    <nav class="nav-sidebar menu-tab-res">
      <ul class="nav tabs">';
        foreach ($menudata as $menudata_key => $menudata_value) { 
			//print_r($menudata_value);die;
				$menudata_list .= '<li class="'.($menudata_key != 0 ? '' : 'active').'"><a href="#menu_tab_'.$menudata_key.'"  data-toggle="tab">'.$menudata_value['title'].'</a></li>';
				$arr = $menudata_value['title'];
				array_push($arr_menu_id, array('menu_id' => $menudata_value->id,'restaurant_id' => $menudata_value->restaurant_id,));
			}
			
			$restaurant_combos = $menudata->combos;
			if ( $restaurant_combos ) {
				$menudata_list .= '<li><a href="#combo_tab"  data-toggle="tab">Combos</a></li>';
			}
	$menudata_list .= '</ul></nav></div>';
    $menudata_list .= '<div class="tab-content col-md-9 no-padding-mobile col-sm-9">'; 
  	foreach ($menudata as $restaurant_menus_key => $restaurant_menus_value) {
    	$menudata_list .= '<div class="tab-pane text-style '.($restaurant_menus_key != 0 ? '' : 'active').'" id="menu_tab_'.$restaurant_menus_key.'">';
        $menudata_list .= '<div class="col-md-12 no-padding-right1 no-padding-mobile">
        <div class="menu-right-side">
          <div class="menu-cat-img">';
		   $menudata_list .= '<img src="'.$restaurant_menus_value->imageUrl.'" alt="" class="img-responsive lazy"> </div>';
           $menudata_list .= '<div class="menu-cat-title">'.$restaurant_menus_value->title.'</div></div>';
		   
        foreach ($restaurant_menus_value->dishes as $dishesKey => $dishesValue) {
         	$menudata_list .= '<div class="dish-wise-tab '.($dishesKey % 2 == 0 ? 'odd' : 'even').'">
          
          <div class="col-md-10 col-sm-10 col-xs-10 no-padding-right1 no-padding-mobile">';
		   if ($dishesValue->toppingGroups) { 
		   		$menudata_list .= '<a href="javascript:void(0);" data-model-id="model_'.$dishesValue->id.'" class="open_model dish-order">
            <div class="col-md-8 col-sm-7 col-xs-8 no-padding">
              <div class="dish-title">
                '.$dishesValue->title.'
              </div>
              <div style="clear:both"></div>
              <div class="dish-contant">
                
              </div>
            </div>
            <div class="col-md-4 col-sm-5 col-xs-4 no-padding-right1">
              <p class="pull-right"><span><i class="fa fa-inr"></i></span>
                '.$dishesValue->price.'
                <span class="cart"><i class="right-arrow"></i></span></p>
            </div>
            </a>';
           
			$menudata_list .= "Modal::begin([
				'header' => '<h2>Choose toppings for '" . $dishesValue->title . "'</h2>',
				'id' => 'model_'" . $dishesValue->id."]);";
			$dish_topping_id_ele_arr = array();
			foreach ($dishesValue->toppingGroups as $tgroupskey => $tgroupsvalue) {
				$menudata_list .= '<div class="row padding-15 border-b">
								<h5>'.$tgroupsvalue->title.'
            </h5>';
           foreach ($tgroupsvalue->dishToppings as $dtKey => $dtValue) { 
            $menudata_list .= '<div class="col-md-6 p-r-50">
              <div class="row padding-15">';
               $menudata_list .= "Html::radio('topping_' . $dishesValue->id . '_' . $tgroupskey, (!$dtKey ? TRUE : FALSE), ['value' => $dtValue->id, 'id' => 'topping_' . $dishesValue->id . '_' . $tgroupskey])";
                 $menudata_list .= '<label for="topping_"'.$dishesValue->id .'_'.$tgroupskey.'">';
                		$menudata_list .= $dtValue->topping->title;
                 $menudata_list .= '</label>
                <span class="pull-right">';
                $menudata_list .= ($dtValue->price > 0 ? '<i class="fa fa-inr"></i> ' . $dtValue->price : 'Free');
                $menudata_list .= '</span> </div>
            </div>';
            }
			array_push($dish_topping_id_ele_arr, 'topping_' . $dishesValue->id . '_' . $tgroupskey);
          $menudata_list .= '</div>';
          } 
          $menudata_list .= "<div class=\"row p-t-10\">".Html::button('Add to cart', ['id' => 'select_topping_btn_' . $tgroupskey, 'class' => 'select_topping_btn btn btn-default pull-right', 'data-topping-id-ele' => implode('^_^', $dish_topping_id_ele_arr), 'data-dish_id' => $dishesValue->id])."</div>";
        $menudata_list .= "Modal::end();";
} else {

          $menudata_list .= '<a href="javascript:void(0);" data-dish_id="'.$dishesValue->id.'" class="add_to_cart_dish dish-order">
          <div class="col-md-8 col-sm-7 col-xs-8 no-padding">
            <div class="dish-title">
              '.$dishesValue->title.'
            </div>
            <div style="clear:both"></div>
            <div class="dish-contant">
               
            </div>
          </div>
          <div class="col-md-4 col-sm-5 col-xs-4 no-padding-right1">
            <p class="pull-right"><span><i class="fa fa-inr"></i></span>
             '.$dishesValue->price.'
              <span class="cart"><i class="fa fa-shopping-cart"></i></span></p>
          </div>
          </a>';
           } 
        $menudata_list .= '</div>
      </div>';
      } 
    $menudata_list .= '</div>
  </div>';
 } 
 if ($restaurant_combos) { 
  $menudata_list .= '<div class="tab-pane text-style" id="combo_tab">
    <div class="col-md-12 no-padding-right no-padding-mobile">';
      foreach ($restaurant_combos as $combosKey => $combosValue) { 
      $menudata_list .= '<div class="dish-wise-tab '.($combosKey % 2 == 0 ? 'odd' : 'even').'">
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding-right no-padding-mobile"> <a href="javascript:void(0);"  class="add_to_cart_combo dish-order" data-combo_id="'.$combosValue->id.'">
          <div class="col-md-8 col-sm-7 col-xs-8 no-padding">
            <div class="dish-title">
              '.$combosValue->title.'
            </div>
            <div style="clear:both"></div>';
            if ($combosValue->combo_type != "Genral") { 
            	$menudata_list .= '<div class="dish-contant">'.$combosValue->combo_type .'</div>';
             }
          $menudata_list .= '</div>
          <div class="col-md-4 col-sm-5 col-xs-4 no-padding-right1">
            <p class="pull-right"><span><i class="fa fa-inr"></i></span>
             '.$combosValue->price.'
              <span class="cart"><i class="fa fa-shopping-cart"></i></span></p>
          </div>
          </a> </div>
      </div>';
       } 
    $menudata_list .= '</div>
  </div>';
  }
 $menudata_list .= '</div>'; 
			
			
			if ($arr_menu_id) { 
				$data['list'] = $menudata_list;
				 //$data['list_data'] =$menu_datalist;
				return  $data;
			} else {
				$data['list'] = '';
				$data['list'] = "No Record Found in your search criteria..";
				return  $data;
			}
    }
    
public function actionMenuMaster()
{
if (Yii::$app->request->isAjax){
    $data = $_REQUEST;// Yii::$app->request->get();  
    $search =  self::actionRefreshsearch($data);
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
        'search' => $search, 
    ];
  }
}

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MenuMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
    
    
     public function actionUpdateajax($id,$order) {  
        $model = $this->findModel($id); 
        $model->order = $order;
            if ($model->save()) {  
                return 1; 
            }else{ 
                return 0; 
            } 
    }

    public static function makeDropDown($parents) {
        global $data;
        $data = array();
        $data['0'] = '-- ROOT --';
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
            self::subDropDown($parent->id);
        }
        return $data;
    }

    public static function subDropDown($children, $space = '---') {
        global $data;
        $childrens = MenuMaster::findAll(['parent_id' => $children]);
        foreach ($childrens as $child) {
            $data[$child->id] = $space . $child->title;
            self::subDropDown($child->id, $space . '---');
        }
    } 
    
    
    

}
