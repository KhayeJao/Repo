<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\Dish $model
 */
$this->title = 'Dish ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="dish-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>



    <h3>
        <?= $model->title ?>    </h3>


    <?php $this->beginBlock('common\models\DishMaster'); ?>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id', 
            'title', 
            'status',
        ],
    ]);
    ?>

    <hr/>

    <?=
    Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data-confirm' => '' . 'Are you sure to delete this item?' . '',
        'data-method' => 'post',
    ]);
    ?>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('ToppingGroup'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Topping Group', ['topping-group/index', 'ToppingGroupSearch' => ['dish_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Topping Group', ['topping-group/create', 'ToppingGroup' => ['dish_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>
    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> Dish',
                        'content' => $this->blocks['common\models\Dish'],
                        'active' => true,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Topping Group</small>',
                        'content' => $this->blocks['ToppingGroup'],
                        'active' => false,
                    ],]
            ]
    );
    ?></div>
