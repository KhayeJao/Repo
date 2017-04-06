<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\RestaurantSearch $searchModel
 */
$this->title = 'Restaurants';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'id',
    'name',
    'color',
    'publish_date',
    'status',
    ['class' => 'yii\grid\ActionColumn'],
];
?>

<div class="restaurant-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
        <?php if (\Yii::$app->user->can('createRestaurant')) { ?>
            <p class="pull-left">
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Restaurant', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        <?php } ?>


        <div class="pull-right">



            <?=
            \yii\bootstrap\ButtonDropdown::widget(
                    [
                        'id' => 'giiant-relations',
                        'encodeLabel' => false,
                        'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . 'Relations',
                        'dropdown' => [
                            'options' => [
                                'class' => 'dropdown-menu-right'
                            ],
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Combo</i>',
                                    'url' => [
                                        'combo/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Dish</i>',
                                    'url' => [
                                        'dish/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Menu</i>',
                                    'url' => [
                                        'menu/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Order</i>',
                                    'url' => [
                                        'order/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> User</i>',
                                    'url' => [
                                        'user/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Area</i>',
                                    'url' => [
                                        'restaurant-area/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Coupons</i>',
                                    'url' => [
                                        'restaurant-coupons/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Cuisine</i>',
                                    'url' => [
                                        'restaurant-cuisine/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Images</i>',
                                    'url' => [
                                        'restaurant-images/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Phone</i>',
                                    'url' => [
                                        'restaurant-phone/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Review</i>',
                                    'url' => [
                                        'restaurant-review/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Services</i>',
                                    'url' => [
                                        'restaurant-services/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Topping</i>',
                                    'url' => [
                                        'topping/index',
                                    ],
                                ],
                            ]],
                    ]
            );
            ?>        </div>
    </div>


    <div class="table-responsive">
        <?php
        $gridColumns = [
            [
                'attribute' => 'user_id',
                'vAlign' => 'middle',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->user->first_name . " " . $model->user->last_name . " (" . $model->user->username . ")";
                },
            ],
            'title',
            'slogan',
            'address',
            'area',
            'city',
            'min_amount',
            'tax',
            'vat',
            'service_charge',
            'scharge_type',
            'kj_share',
            'avg_rating',
            'status',
            'created_date',
        ];

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'showConfirmAlert' => FALSE,
            'target' => [
                ExportMenu::TARGET_SELF => '_self'
            ],
            'filename' => 'Restaurants-' . date('M-d-Y', time()),
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL_X => [
                    'iconOptions' => ['class' => 'text-success'],
                    'linkOptions' => [],
                    'mime' => 'application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'extension' => 'xlsx',
                    'writer' => 'Excel2007'
                ],
            ],
        ]);
        ?>
        <?=
        GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last'],
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    }, 'buttons' => [
                                'delete' => function($url, $model) {
                                    return "";
                                },
                                'update' => function($url, $model) {
                                    if (\Yii::$app->user->can('createRestaurant') OR \Yii::$app->user->can('editRestaurant')) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'Update']);
                                    } else {
                                        return "";
                                    }
                                }
                                    ],
                                    'contentOptions' => ['nowrap' => 'nowrap']
                                ],
//                'id',
                                [
                                    'attribute' => 'user_id',
                                    'vAlign' => 'middle',
                                    'value' => function ($model, $key, $index, $widget) {
                                        return $model->user->first_name . " " . $model->user->last_name . " (" . $model->user->username . ")";
                                    },
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filter' => ArrayHelper::map(\common\models\base\User::find()->orderBy('username')->asArray()->all(), 'id', 'username'),
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => ['allowClear' => true],
                                    ],
                                    'filterInputOptions' => ['placeholder' => 'Any User'],
                                ],
                                'title',
//                'slogan',
                                'address',
                                'area',
                                'city',
                                /* 'latitude' */
                                /* 'longitude' */
                                'min_amount',
                                /* 'logo' */
                                /* 'delivery_network' */
                                /* 'delivery_mins' */
                                /* 'food_type' */
                                /* 'open_datetime_1' */
                                /* 'close_datetime_1' */
                                /* 'open_datetime_2' */
                                /* 'close_datetime_2' */
                                /* 'tax' */
                                /* 'vat' */
                                /* 'service_charge' */
                                /* 'scharge_type' */
                                /* 'kj_share' */
                                /* 'prior_table_booking_time' */
                                /* 'table_slot_time:datetime' */
                                /* 'who_delivers' */
                                /* 'meta_keywords' */
                                /* 'meta_description' */
                                /* 'coupon_text' */
                                /* 'avg_rating' */
                                /* 'is_featured' */
                                /* 'featured_image' */
                                /* 'status' */
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw', //It was 'html' before
                                    'value' => function ($data) {
                                        if ($data->status == "Active") {
                                            return Html::a("Active", ['changestatus', 'status' => 'Inactive', 'id' => $data->id], ['class' => 'btn btn-success']);
                                        } else {
                                            return Html::a("Inactive", ['changestatus', 'status' => 'Active', 'id' => $data->id], ['class' => 'btn btn-success']);
                                        }
                                    }                                 //missing parenthesis
                                        ],
                                        [
                                            'attribute' => 'created_date',
                                            'value' => 'created_date',
                                            'filter' => kartik\widgets\DatePicker::widget([
                                                'name' => 'RestaurantSearch[created_date]',
                                                'type' => kartik\widgets\DatePicker::TYPE_INPUT,
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'yyyy-MM'
                                                ],
                                            ]),
                                            'format' => 'date',
                                        ],
                                    ],
                                ]);
                                ?>
    </div>


</div>
