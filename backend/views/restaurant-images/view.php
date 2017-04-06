<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantImages $model
 */
$this->title = 'Restaurant Images ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="restaurant-images-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . '
        Restaurant Images', ['create'], ['class' => 'btn btn-success']) ?>
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


    <?php $this->beginBlock('common\models\RestaurantImages'); ?>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'restaurant_id',
                'value' => $model->restaurant->title
            ],
            [
                'attribute' => 'image',
                'format' => 'image',
                'value' => $model->getResizeImageUrl()
            ],
            'title',
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



    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> RestaurantImages',
                        'content' => $this->blocks['common\models\RestaurantImages'],
                        'active' => true,
                    ],]
            ]
    );
    ?></div>
