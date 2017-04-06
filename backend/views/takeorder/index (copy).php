<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\export\ExportMenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\UserSearch $searchModel
 */
 
$this->title = 'Take Order';
$this->params['breadcrumbs'][] = $this->title;
$arrList = ArrayHelper::map(\common\models\base\Ordertable::findAll(), 'id'); 
//print_r($tableModel);die;
?>

<div class="user-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
        <?php if (Yii::$app->session->getFlash('success')) { ?>
            <div class="alert alert-success" role="alert">
                <button class="close" data-dismiss="alert"></button>
                <strong>Congratulations: </strong><?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } ?>

        <p class="">
            <?php 
           
            
                Modal::begin([
                    'header' => '<h2>Select Table</h2>',
                    'toggleButton' => ['label' => 'Place Order for Table', 'class' => 'btn btn-success'],
                ]);
                
               // echo Html::hiddenInput('user_id', '', ['id' => 'user_id']);
                echo Select2::widget([
                    'name' => 'table_id',
                    'data' => ArrayHelper::map($tableModel,'id','table_no'),
                    'options' => [
                        'placeholder' => 'Select Table..',
                        'id' => 'table_id'
                    ],
                ]);
                ?> 
            <div class="row p-t-10">
                <?php echo Html::button('Select', ['id' => 'select_table_btn', 'class' => 'btn btn-default pull-right']); ?>
            </div>
            <?php
            Modal::end();
            $this->registerJs($this->render('select_restaurant_js'), \yii\web\VIEW::POS_END);
         
        ?>
        </p>
 
    <?php if (Yii::$app->session->getFlash('error')) { ?>
        <div class="alert alert-danger" role="alert">
            <button class="close" data-dismiss="alert"></button>
            <strong>Error: </strong><?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php } ?> 
    
        <div class="table-responsive">
        <?= GridView::widget([
        'layout' => '{summary}{pager}{items}{pager}',
        'dataProvider' => $dataProvider,
        'pager'        => [
         'class'     => yii\widgets\LinkPager::className(),
         'firstPageLabel' => 'First',
         'lastPageLabel'  => 'Last' ],
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
    'contentOptions' => ['nowrap'=>'nowrap']
],
			'id',
			'table_no',
			 'date',
			'status',
        ],
    ]); ?> 
    </div>
 
