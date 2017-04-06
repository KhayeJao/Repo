<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use yii\helpers\Json;

/**
 * @var yii\web\View $this
 * @var common\models\Coupons $model
 */
$this->title = 'Coupons ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="coupons-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . '
        Coupons', ['create'], ['class' => 'btn btn-success']) ?>
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


    <?php $this->beginBlock('common\models\Coupons'); ?>

    <?php
    $coupon_perameter_str = "";
    foreach (Json::decode($model->coupon_perameter) as $key => $value) {
        $coupon_perameter_str .= "<p><label>" . ucfirst(str_replace("_", " ", $key)) . " : </label><span>" . $value . "</span></p>";
    }
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'coupon_key',
            'title',
            'description',
            'type',
//            'coupon_perameter',
            [
                'attribute' => 'coupon_perameter',
                'value' => "<div>" . $coupon_perameter_str . '<div>',
                'format' => ['html']
            ],
            'notify',
            'status',
            'created_on',
            'expired_on',
            'updated_on',
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



<?php $this->beginBlock('RestaurantCoupons'); ?>
    <div style='position: relative'>
        <div style='float: right'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Coupons', ['restaurant-coupons/index'], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Coupon', ['restaurant-coupons/create', 'RestaurantCoupon' => ['coupon_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div>
    </div><?php $this->endBlock() ?>


    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> Coupons',
                        'content' => $this->blocks['common\models\Coupons'],
                        'active' => true,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Coupons</small>',
                        'content' => $this->blocks['RestaurantCoupons'],
                        'active' => false,
                    ],]
            ]
    );
    ?></div>
