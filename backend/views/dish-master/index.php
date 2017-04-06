<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\DishesSearch $searchModel
 */
$this->title = 'Dishes';
//$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => BASE_URL. 'backend/web/index.php/restaurant/view?id='.$_GET['DishesSearch']['restaurant_id']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dish-index">
 <!-- menu buttons -->
     
    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>
	    <?php 
	  //if($_GET['MenuSearch']['restaurant_id']==1){
            echo Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Dish', ['dish-master/create1'], ['class' => 'btn btn-success btn-xs']
            );
	 // }
    ?>

    <?php
    if (Yii::$app->session->getFlash('error')) {
        echo $this->render('../includes/error_message');
    }
    ?>

    <div class="clearfix">

       


    <div class="table-responsive">
        <?=
        GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last'],
            'filterModel' => $searchModel,
            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function($action, $model, $key, $index) {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                return Url::toRoute($params);
            },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
                'id', 
                'title', 
                'status'  
            ],
        ]);
        ?>
    </div>


</div>

