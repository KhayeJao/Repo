<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\TableBookingSearch $searchModel
 */
$this->title = 'Table Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="table-booking-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
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
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> User</i>',
                                    'url' => [
                                        'user/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Table</i>',
                                    'url' => [
                                        'table/index',
                                    ],
                                ],
                            ]],
                    ]
            );
            ?>        </div>
    </div>


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
                        'update' => function ($url, $model) {
                    return '';
                },
                        'delete' => function ($url, $model) {
                    return '';
                },
                    ],
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
                'id',
                'order_unique_id',
                [
                    'attribute' => 'user_id',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                return $model->user->first_name . " " . $model->user->last_name;
            },
                ],
                [
                    'attribute' => 'checkin_datetime',
                    'vAlign' => 'middle',
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'booking_date',
                    'vAlign' => 'middle',
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'grand_total',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                return "Rs. " . $model->grand_total;
            },
                ],
            ],
        ]);
        ?>
    </div>


</div>
