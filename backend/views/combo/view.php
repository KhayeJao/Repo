<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\Combo $model
 */
$this->title = 'Combo ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Combos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="combo-view">

    <!-- menu buttons -->
    <p class='pull-right'>
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


    <?php $this->beginBlock('common\models\Combo'); ?>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'restaurant_id',
                'value' => $model->restaurant->title
            ],
            'title',
            'price',
            'combo_type',
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



    <?php $this->beginBlock('ComboDishes'); ?>
    <div style='position: relative'><div style='float: right'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Combo Dishes', ['combo-dish/index', 'ComboDishSearch' => ['combo_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Combo Dish', ['combo-dish/create', 'ComboDish' => ['combo_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>
    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> Combo',
                        'content' => $this->blocks['common\models\Combo'],
                        'active' => true,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Combo Dishes</small>',
                        'content' => $this->blocks['ComboDishes'],
                        'active' => false,
                    ],]
            ]
    );
    ?></div>
