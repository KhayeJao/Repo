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
$this->title = 'User ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="user-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?php
        if (\Yii::$app->user->can('placeOrder')) {
//            echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Place Order for this user', ['order/placeorder/', 'user_id' => $model->id], ['class' => 'btn btn-success']);

            Modal::begin([
                'header' => '<h2>Select restaurant</h2>',
                'toggleButton' => ['label' => 'Place Order for this user', 'class' => 'btn btn-success'],
            ]);

            echo Html::hiddenInput('user_id', $model->id, ['id' => 'user_id']);
            echo Select2::widget([
                'name' => 'restaurant_id',
                'data' => ArrayHelper::map(\common\models\base\Restaurant::findAll(['status' => 'Active']), 'id', 'title'),
                'options' => [
                    'placeholder' => 'Select Restaurant..',
                    'id' => 'restaurant_id'
                ],
            ]);
            ?>
        <div class="row p-t-10" id="restaurant_info_div" style="display: none">

        </div>
        <div class="row p-t-10">
            <?php echo Html::button('Select', ['id' => 'select_restaurant_btn', 'class' => 'btn btn-default pull-right']); ?>
        </div>

        <?php
        Modal::end();
        $this->registerJs($this->render('select_restaurant_js'), \yii\web\VIEW::POS_END);
    }
    ?>
</p>

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
    <?= $model->username ?>    </h3>


<?php $this->beginBlock('common\models\base\User'); ?>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
//        'id',
        'username',
        'email:email',
        'first_name',
        'last_name',
        'mobile_no',
        'fb_id',
        'fb_profile',
        'mobile_v_code',
        'is_mobile_verified',
        'is_email_verified',
        'reward_points',
//        'type',
//        'auth_key',
//        'password_hash',
//        'password_reset_token',
//        'status',
        'created_at:datetime',
        'updated_at:datetime',
        'last_login_ip',
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



<?php $this->beginBlock('Addresses'); ?>
<div style='position: relative'><div style='float: right'>
        <?php
//        Html::a(
//                '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Addresses', ['address/index'], ['class' => 'btn text-muted btn-xs']
//        )
        ?>
    </div></div><?php $this->endBlock() ?>


<?php $this->beginBlock('FavDishes'); ?>
<div style='position: relative'><div style='float: right'>
        <?php
//        Html::a(
//                '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Fav Dishes', ['fav-dish/index', 'FavDishSearch' => ['user_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
//        )
        ?>
    </div></div><?php $this->endBlock() ?>

<?php $this->beginBlock('Orders'); ?>
<div style='position: relative'><div style='float: right'>
        <?php
//        Html::a(
//                '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Orders', ['order/index'], ['class' => 'btn text-muted btn-xs']
//        )
        ?>
        
    </div></div><?php $this->endBlock() ?>

<?php $this->beginBlock('TableBookings'); ?>
<div style='position: relative'><div style='float: right'>
        <?php
//        Html::a(
//                '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Table Bookings', ['table-booking/index', 'TableBookingSearch' => ['user_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
//        )
        ?>
        
    </div>
</div><?php $this->endBlock() ?>


<?=
Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [ [
                    'label' => '<span class="glyphicon glyphicon-asterisk"></span> User',
                    'content' => $this->blocks['common\models\base\User'],
                    'active' => true,
                ], [
                    'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Addresses</small>',
                    'content' => $this->blocks['Addresses'],
                    'active' => false,
                ], [
                    'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Fav Dishes</small>',
                    'content' => $this->blocks['FavDishes'],
                    'active' => false,
                ], [
                    'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Orders</small>',
                    'content' => $this->blocks['Orders'],
                    'active' => false,
                ], [
                    'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Table Bookings</small>',
                    'content' => $this->blocks['TableBookings'],
                    'active' => false,
                ],]
        ]
);
?></div>
