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
 
$this->title = 'Call Center';
$this->params['breadcrumbs'][] = $this->title; 
$arrList = \common\models\base\Restaurant::findAll(['is_callcenter' => '1']); 

//print_r($arrList);die;
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

        <p class="row p-t-10">
		 <?php foreach($arrList as $key=>$val){   ?>  
		 <a tabindex="0" href="<?php echo Url::to(['order/placeorder']); ?>?restaurant_id=<?php echo $val->id;?>&user_type=callcenter" class="btn btn-lg btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo $val->title;?>" data-content="And here's some amazing content. It's very engaging. Right?"><?php echo $val->title;?></a>
		 <?php } ?>	
			
        </p>
 
     
    
        
 </div>
