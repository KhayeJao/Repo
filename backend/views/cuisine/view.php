<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\Cuisine $model
 */
$this->title = 'Cuisine ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Cuisines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="cuisine-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . '
        Cuisine', ['create'], ['class' => 'btn btn-success']) ?>
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


    <?php $this->beginBlock('common\models\Cuisine'); ?>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description',
            [
                'attribute' => 'image',
                'format' => 'image',
                'value' => $model->getResizeImageUrl()
            ],
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



    <?php $this->beginBlock('RestaurantCuisines'); ?>
    <?php /*<div style='position: relative'><div style='position:absolute; right: 0px; top: 0px;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Cuisines', ['restaurant-cuisine/index'], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Cuisine', ['restaurant-cuisine/update', 'RestaurantCuisine' => ['cuisine_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php */$this->endBlock() ?>


    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> Cuisine',
                        'content' => $this->blocks['common\models\Cuisine'],
                        'active' => true,
                    ], ]
            ]
    );
        
//        [
//                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Cuisines</small>',
//                        'content' => $this->blocks['RestaurantCuisines'],
//                        'active' => false,
//                    ],
    ?></div>
