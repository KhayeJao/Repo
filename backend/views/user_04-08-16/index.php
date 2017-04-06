<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\export\ExportMenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\UserSearch $searchModel
 */
$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
        <?php if (Yii::$app->session->getFlash('success')) { ?>
            <div class="alert alert-success" role="alert">
                <button class="close" data-dismiss="alert"></button>
                <strong>Congratulations: </strong><?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } ?>

        <p class="pull-left">
            <?php
            if (\Yii::$app->user->can('manageUsers')) {
                echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New User', ['create'], ['class' => 'btn btn-info m-r-10']);
            }

            if (\Yii::$app->user->can('placeOrder')) {
                Modal::begin([
                    'header' => '<h2>Select restaurant</h2>',
                    'toggleButton' => ['label' => 'Place Order for as a guest', 'class' => 'btn btn-success'],
                ]);
                echo Html::hiddenInput('user_id', '', ['id' => 'user_id']);
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

        <div class="pull-right">

            <?php
            $gridColumns = [
                'id',
                'username',
                'email:email',
                'first_name',
                'last_name',
                'mobile_no',
                'fb_id',
                'created_at',
                'updated_at',
                'last_login_ip'
            ];

            echo ExportMenu::widget([
                'dataProvider' => $dataProviderCust,
                'columns' => $gridColumns,
                'showConfirmAlert' => FALSE,
                'target' => [
                    ExportMenu::TARGET_SELF => '_blank'
                ],
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
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Address</i>',
                                    'url' => [
                                        'address/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Fav Dish</i>',
                                    'url' => [
                                        'fav-dish/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Guest</i>',
                                    'url' => [
                                        'guest/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Order</i>',
                                    'url' => [
                                        'order/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant</i>',
                                    'url' => [
                                        'restaurant/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Review</i>',
                                    'url' => [
                                        'restaurant-review/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Table Booking</i>',
                                    'url' => [
                                        'table-booking/index',
                                    ],
                                ],
                            ]],
                    ]
            );
            ?>        </div>
    </div>
    <?php if (Yii::$app->session->getFlash('error')) { ?>
        <div class="alert alert-danger" role="alert">
            <button class="close" data-dismiss="alert"></button>
            <strong>Error: </strong><?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php } ?>


    <div class="table-responsive">
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
                    },
                            'buttons' => [
                                'delete' => function($url, $model) {
                                    if (!yii::$app->user->can('manageUsers')) {
                                        return '';
                                    }
                                },
                                'update' => function($url, $model) {
                                    if (!yii::$app->user->can('manageUsers')) {
                                        return '';
                                    }
                                },
                            ],
                            'contentOptions' => ['nowrap' => 'nowrap']
                        ],
//                        'id',
                        'username',
                        'email:email',
                        'first_name',
                        'last_name',
                        'mobile_no',
//                        'fb_id',
                        /* 'fb_profile' */
                        /* 'mobile_v_code' */
                        /* 'is_mobile_verified' */
                        /* 'is_email_verified:email' */
                        /* 'type' */
                        /* 'auth_key' */
                        /* 'password_hash' */
                        /* 'password_reset_token' */
                        /* 'status' */
                        'created_at:datetime',
                        'updated_at:datetime'
                    /* 'last_login_ip' */
                    ],
                ]);
                ?>
    </div>


</div>
