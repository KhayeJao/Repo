<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use \dmstr\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\export\ExportMenu;
/**
* @var yii\web\View $this
* @var common\models\Dish $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dish-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
     <p class="pull-left">
      <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Dishes', ['dish/index', 'DishesSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            ) 
			     
            ?>
	 
 <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal">Add Dishes From Master Tabale</button>
            </p>
    <div class="clearfix"></div>

    <?= $this->render('_form1', [
    'model' => $model,
	 'title'=>$title,
	  'menu_name'=>$menu_name,
    ]); ?>

</div>
