<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var common\models\base\User $model
 */
 
?>
<div class="user-view"> 
   
        <?php if (Yii::$app->session->getFlash('success')) { ?>
            <div class="alert alert-success" role="alert">
                <button class="close" data-dismiss="alert"></button>
                <strong>Congratulations: </strong><?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } ?>

         

<div class="clearfix"></div>
<?php if (Yii::$app->session->getFlash('error')) { ?>
    <div class="alert alert-danger" role="alert">
        <button class="close" data-dismiss="alert"></button>
        <strong>Error: </strong><?= Yii::$app->session->getFlash('error'); ?>
    </div>
<?php } ?>
<!-- flash message -->
<?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
<?php endif; ?> 
<h3>
    <?= $model->table_no ?>   
 </h3> 
 <?php   $this->beginBlock('common\models\Ordertablestatus'); ?>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
         'id',
        'table_no',
        'date',
        'status', 
    ],
    ]); ?>

    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
    'data-method' => 'post',
    ]); ?>
    <?php $this->endBlock(); ?>


 

  
