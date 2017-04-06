<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\Restaurant $model
 */
$this->title = 'Restaurant ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="restaurant-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . '
        Restaurant', ['create'], ['class' => 'btn btn-success']) ?>
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


    <?php $this->beginBlock('common\models\Restaurant'); ?>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'user_id',
            [
                'attribute' => 'user_id',
                'label' => 'Owner',
                'value' => $model->user->first_name . " " . $model->user->last_name . " ( " . $model->user->mobile_no . " )",
                'format' => ['html']
            ],
            'title',
            'slogan',
            'address',
            'area',
            'city',
            'sms_number',
            'order_number',
            'latitude',
            'longitude',
            'min_amount',
//            'logo',
            [
                'attribute' => 'logo',
                'label' => 'Logo',
                'value' => '<img src=' . $model->getImageUrl() . ' width="150px"/>',
                'format' => ['html']
            ],
            [
                'attribute' => 'delivery_network',
                'label' => 'DELIVERY (IN KM)'
            ],
            [
                'attribute' => 'delivery_mins',
                'label' => 'DELIVERY TIME (IN MINUITS)'
            ],
            'food_type',
//            'open_datetime_1',
            [
                'label' => 'Restaurant open and close time',
                'value' => $model->open_datetime_1 . ' to ' . $model->close_datetime_1 . ' AND ' . $model->open_datetime_2 . ' to ' . $model->close_datetime_2,
            ],
//            'close_datetime_1',
//            'open_datetime_2',
//            'close_datetime_2',
            [
                'attribute' => 'tax',
                'label' => 'Tax (%)',
            ],
            [
                'attribute' => 'vat',
                'label' => 'Vat (%)',
            ],
            'service_charge',
            [
                'attribute' => 'service_charge',
                'value' => $model->service_charge . ' ' . ($model->scharge_type == 'Percentage' ? '%' : 'Rs'),
            ],
            'service_charge',
            [
                'attribute' => 'kj_share',
                'label' => 'KhayeJao Share (%)',
            ],
//            'scharge_type',
            'prior_table_booking_time',
            [
                'attribute' => 'prior_table_booking_time',
                'label' => 'Prior Table Booking Time (Hours)',
            ],
            [
                'attribute' => 'table_slot_time',
                'label' => 'Table Slot Time (Minutes)',
            ],
//            'table_slot_time',
            'who_delivers',
            'meta_keywords',
            'meta_description',
            'coupon_text',
            'avg_rating',
            'is_featured:boolean',
            [
                'attribute' => 'featured_image',
                'label' => 'Featured Image',
                'value' => '<img src=' . $model->getResizeImageFUrl() . ' width="150px"/>',
                'format' => ['html']
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



    <?php $this->beginBlock('Combos'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Combos', ['combo/index', 'ComboSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Combo', ['combo/create', 'Combo' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('Dishes'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Dishes', ['dish/index', 'DishesSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Dish', ['dish/create1', 'Dish' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('Menus'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Menus', ['menu/index', 'MenuSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Menu', ['menu/create', 'Menu' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('Orders'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Orders', ['order/index', 'Order' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('RestaurantAreas'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Areas', ['restaurant-area/index', 'RestaurantAreaSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Area', ['restaurant-area/create', 'RestaurantArea' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>
    <?php $this->beginBlock('RestaurantCuisines'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Cuisines', ['restaurant-cuisine/index', 'RestaurantCuisineSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Cuisine', ['restaurant-cuisine/update', 'restaurant_id' => $model->id], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('RestaurantImages'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Images', ['restaurant-images/index', 'RestaurantImageSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Image', ['restaurant-images/create', 'RestaurantImage' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('RestaurantPhones'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Phones', ['restaurant-phone/index', 'RestaurantPhoneSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Phone', ['restaurant-phone/create', 'RestaurantPhone' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('RestaurantReviews'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Reviews', ['restaurant-review/index', 'RestaurantReviewSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>

        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('RestaurantServices'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Restaurant Services', ['restaurant-services/index', 'RestaurantServicesSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant Service', ['restaurant-services/update', 'restaurant_id' => $model->id], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>


    <?php $this->beginBlock('Toppings'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Toppings', ['topping/index', 'ToppingSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Topping', ['topping/create', 'Topping' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>

    <?php $this->beginBlock('Tables'); ?>
    <div style='position: relative'><div style='float: right;'>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Tables', ['table/index', 'TableSearch' => ['restaurant_id' => $model->id]], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            <?=
            Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Table', ['table/create', 'Table' => ['restaurant_id' => $model->id]], ['class' => 'btn btn-success btn-xs']
            );
            ?>
        </div></div><?php $this->endBlock() ?>
		
		
		<?php $this->beginBlock('Import'); ?>
    <div style='position: relative'><div style='float: right;'>
                <?php
    $form = ActiveForm::begin([
                'id' => 'Menu',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE,
                'options' => ['enctype' => 'multipart/form-data']
                    ]
    );
    ?>
	<?php
            
			
			echo Select2::widget([
                    'name' => 'menu_id',
                    'data' => ArrayHelper::map(\common\models\base\MenuMaster::findAll(['status' => 'Active']), 'id', 'title'),
                    'options' => [
                        'placeholder' => 'Select Menu',
                        'id' => 'menu_id'
                    ],
                ]);
            ?>
 
<?php ActiveForm::end(); ?>
</div>

        </div></div><?php $this->endBlock() ?>


    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> Restaurant',
                        'content' => $this->blocks['common\models\Restaurant'],
                        'active' => true,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Combos</small>',
                        'content' => $this->blocks['Combos'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Dishes</small>',
                        'content' => $this->blocks['Dishes'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Menus</small>',
                        'content' => $this->blocks['Menus'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Orders</small>',
                        'content' => $this->blocks['Orders'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Areas</small>',
                        'content' => $this->blocks['RestaurantAreas'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Cuisines</small>',
                        'content' => $this->blocks['RestaurantCuisines'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Images</small>',
                        'content' => $this->blocks['RestaurantImages'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Phones</small>',
                        'content' => $this->blocks['RestaurantPhones'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Reviews</small>',
                        'content' => $this->blocks['RestaurantReviews'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Restaurant Services</small>',
                        'content' => $this->blocks['RestaurantServices'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Toppings</small>',
                        'content' => $this->blocks['Toppings'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Tables</small>',
                        'content' => $this->blocks['Tables'],
                        'active' => false,
                    ],[
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Import Menu And Dish From Admin </small>',
                        'content' => $this->blocks['Import'],
                        'active' => false,
                    ],]
            ]
    );
    ?></div>
