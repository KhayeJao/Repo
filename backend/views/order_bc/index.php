<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\editable\Editable;
use kartik\export\ExportMenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\OrderSearch $searchModel
 */
$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
<!--        <p class="pull-left">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

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
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Restaurant</i>',
                                    'url' => [
                                        'restaurant/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Order Combo</i>',
                                    'url' => [
                                        'order-combo/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Order Dish</i>',
                                    'url' => [
                                        'order-dish/index',
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
                'attribute' => 'status',
                'vAlign' => 'middle',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed'],
                'filterInputOptions' => ['placeholder' => 'Select Status'],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'format' => 'raw',
                'class' => 'kartik\grid\EditableColumn',
                'readonly' => function($model, $key, $index, $widget) {
            return ($model->status == 'Completed'); // do not allow editing of inactive records
        },
                'editableOptions' => function ($model, $key, $index) {
            return [
                'model' => $model,
                'attribute' => 'status',
                'size' => 'md',
                'afterInput' => function($form, $widget) {
                    echo "<div class='editable_status_dd_div form-group' style='display:none'>" . $form->field($widget->model, 'order_status_change_reason')->dropDownList(Yii::$app->params['order_cancle_reasons'], ['placeholder' => 'Select Reason', 'class' => 'form_control change_status_dd'])->label('Reason') . "</div>";
                    echo "<div class='editable_status_ta_div form-group' style='display:none'>" . Html::textarea('status_other_reason', '', ['class' => 'other_reason_ta form_control', 'placeholder' => 'Other reason']) . "</div>";
                },
                        'format' => 'button',
                        'editableValueOptions' => ['class' => 'well well-sm'],
                        'name' => 'status',
                        'header' => 'Status',
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => ['Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed'],
                        'formOptions' => ['action' => Url::to('changestatus')],
                        'ajaxSettings' => ['beforeSend' => 'function () {

                        }'],
                    ];
                },
                    ],
                    'order_unique_id',
                    'user_full_name',
                    [
                        'attribute' => 'restaurant_id',
                        'vAlign' => 'middle',
                        'value' => function ($model, $key, $index, $widget) {
                            return $model->restaurant->title;
                        }, 'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map(\common\models\base\Restaurant::find()->orderBy('title')->asArray()->all(), 'id', 'title'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Any Restaurant'],
                    ],
                    'mobile',
                    'email:email',
                    'address_line_1',
                    'address_line_2',
                    [
                        'attribute' => 'area',
                        'value' => function($model) {
                            return $model->area0->area_name;
                        }
                    ],
                    'city',
                    'pincode',
                    'delivery_time',
                    'coupon_code',
                    'discount_amount',
                    'tax',
                    'vat',
                    'service_charge',
                    'booking_time',
                    'order_ip',
                    'status',
                ];

                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'showConfirmAlert' => FALSE,
                    'filename' => 'order_' . date("M-d-Y"),
                    'target' => [
                        ExportMenu::TARGET_SELF => '_self'
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
                <?php if (Yii::$app->user->identity->type != "restaurant") { ?>
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
                                    [

                                        'attribute' => 'status',
                                        'vAlign' => 'middle',
                                        'filterType' => GridView::FILTER_SELECT2,
                                        'filter' => ['Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed'],
                                        'filterInputOptions' => ['placeholder' => 'Select Status'],
                                        'filterWidgetOptions' => [
                                            'pluginOptions' => ['allowClear' => true],
                                        ],
                                        'format' => 'raw',
                                        'class' => 'kartik\grid\EditableColumn',
                                        'readonly' => function($model, $key, $index, $widget) {
                                    return ($model->status == 'Completed'); // do not allow editing of inactive records
                                },
                                        'editableOptions' => function ($model, $key, $index) {
                                    return [
                                        'model' => $model,
                                        'attribute' => 'status',
                                        'size' => 'md',
                                        'afterInput' => function($form, $widget) {
                                            echo "<div class='editable_status_dd_div form-group' style='display:none'>" . $form->field($widget->model, 'order_status_change_reason')->dropDownList(Yii::$app->params['order_cancle_reasons'], ['placeholder' => 'Select Reason', 'class' => 'form_control change_status_dd'])->label('Reason') . "</div>";
                                            echo "<div class='editable_status_ta_div form-group' style='display:none'>" . Html::textarea('status_other_reason', '', ['class' => 'other_reason_ta form_control', 'placeholder' => 'Other reason']) . "</div>";
                                        },
                                                'format' => 'button',
                                                'editableValueOptions' => ['class' => 'well well-sm'],
                                                'name' => 'status',
                                                'header' => 'Status',
                                                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                                'data' => ['Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed'],
                                                'formOptions' => ['action' => Url::to('changestatus')],
                                                'pluginEvents' => [
                                                    "editableSuccess" => "function(event, val, form, data) { location.reload(); }",
                                                ],
                                            ];
                                        },
                                            ],
                                            'order_unique_id',
                                            'user_full_name',
                                            [
                                                'attribute' => 'restaurant_id',
                                                'vAlign' => 'middle',
                                                'value' => function ($model, $key, $index, $widget) {
                                                    return $model->restaurant->title;
                                                },
                                                'filterType' => GridView::FILTER_SELECT2,
                                                'filter' => ArrayHelper::map(\common\models\base\Restaurant::find()->where(['status' => 'Active'])->orderBy('title')->asArray()->all(), 'id', 'title'),
                                                'filterWidgetOptions' => [
                                                    'pluginOptions' => ['allowClear' => true],
                                                ],
                                                'filterInputOptions' => ['placeholder' => 'Any Restaurant'],
                                            ], [
                                                'attribute' => 'delivery_time',
                                                'label' => 'Delivery/Pickup time',
                                                'format' => 'datetime',
                                            ],
                                            [
                                                'attribute' => 'delivery_type',
                                                'vAlign' => 'middle',
                                                'value' => function ($model, $key, $index, $widget) {
                                                    return $model->delivery_type;
                                                }, 'filterType' => GridView::FILTER_SELECT2,
                                                'filter' => ['Pickup' => 'Pickup', 'Delivery' => 'Delivery'],
                                                'filterWidgetOptions' => [
                                                    'pluginOptions' => ['allowClear' => true],
                                                ],
                                                'filterInputOptions' => ['placeholder' => 'Any Type'],
                                            ],
                                            [
                                                'attribute' => 'booking_time',
                                                'format' => 'datetime',
                                            ],
                                            [
                                                'attribute' => 'accept_reject_datetime',
                                                'value' => function ($model, $key, $index, $widget) {
                                                    if ($model->accept_reject_datetime != '0000-00-00 00:00:00') {
                                                        return Yii::$app->formatter->asDatetime($model->accept_reject_datetime);
                                                    } else {
                                                        return ' - ';
                                                    }
                                                }
                                            ],
                                            [
                                                'attribute' => 'complete_datetime',
                                                'value' => function ($model, $key, $index, $widget) {
                                                    if ($model->complete_datetime != '0000-00-00 00:00:00') {
                                                        return Yii::$app->formatter->asDatetime($model->complete_datetime);
                                                    } else {
                                                        return ' - ';
                                                    }
                                                }
                                            ],
                                        ],
                                    ]);
                                    ?>
                                <?php } else { ?>
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
                                                    [

                                                        'attribute' => 'status',
                                                        'vAlign' => 'middle',
                                                        'filterType' => GridView::FILTER_SELECT2,
                                                        'filter' => ['Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed'],
                                                        'filterInputOptions' => ['placeholder' => 'Select Status'],
                                                        'filterWidgetOptions' => [
                                                            'pluginOptions' => ['allowClear' => true],
                                                        ],
                                                        'format' => 'raw',
                                                        'class' => 'kartik\grid\EditableColumn',
                                                        'readonly' => function($model, $key, $index, $widget) {
                                                    return ($model->status == 'Completed'); // do not allow editing of inactive records
                                                },
                                                        'editableOptions' => function ($model, $key, $index) {
                                                    return [
                                                        'model' => $model,
                                                        'attribute' => 'status',
                                                        'size' => 'md',
                                                        'afterInput' => function($form, $widget) {
                                                            echo "<div class='editable_status_dd_div form-group' style='display:none'>" . $form->field($widget->model, 'order_status_change_reason')->dropDownList(Yii::$app->params['order_cancle_reasons'], ['placeholder' => 'Select Reason', 'class' => 'form_control change_status_dd'])->label('Reason') . "</div>";
                                                            echo "<div class='editable_status_ta_div form-group' style='display:none'>" . Html::textarea('status_other_reason', '', ['class' => 'other_reason_ta form_control', 'placeholder' => 'Other reason']) . "</div>";
                                                        },
                                                                'format' => 'button',
                                                                'editableValueOptions' => ['class' => 'well well-sm'],
                                                                'name' => 'status',
                                                                'header' => 'Status',
                                                                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                                                'data' => ['Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed'],
                                                                'formOptions' => ['action' => Url::to('changestatus')],
                                                                'pluginEvents' => [
                                                                    "editableSuccess" => "function(event, val, form, data) { location.reload(); }",
                                                                ],
                                                            ];
                                                        },
                                                            ],
                                                            'order_unique_id',
                                                            'user_full_name',
                                                            [
                                                                'attribute' => 'restaurant_id',
                                                                'vAlign' => 'middle',
                                                                'value' => function ($model, $key, $index, $widget) {
                                                                    return $model->restaurant->title;
                                                                },
                                                            ], [
                                                                'attribute' => 'delivery_time',
                                                                'label' => 'Delivery/Pickup time',
                                                                'format' => 'datetime',
                                                            ],
                                                            [
                                                                'attribute' => 'delivery_type',
                                                                'vAlign' => 'middle',
                                                                'value' => function ($model, $key, $index, $widget) {
                                                                    return $model->delivery_type;
                                                                }, 'filterType' => GridView::FILTER_SELECT2,
                                                                'filter' => ['Pickup' => 'Pickup', 'Delivery' => 'Delivery'],
                                                                'filterWidgetOptions' => [
                                                                    'pluginOptions' => ['allowClear' => true],
                                                                ],
                                                                'filterInputOptions' => ['placeholder' => 'Any Type'],
                                                            ],
                                                            [
                                                                'attribute' => 'booking_time',
                                                                'format' => 'datetime',
                                                            ],
                                                            [
                                                                'attribute' => 'accept_reject_datetime',
                                                                'value' => function ($model, $key, $index, $widget) {
                                                                    if ($model->accept_reject_datetime != '0000-00-00 00:00:00') {
                                                                        return Yii::$app->formatter->asDatetime($model->accept_reject_datetime);
                                                                    } else {
                                                                        return ' - ';
                                                                    }
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'complete_datetime',
                                                                'value' => function ($model, $key, $index, $widget) {
                                                                    if ($model->complete_datetime != '0000-00-00 00:00:00') {
                                                                        return Yii::$app->formatter->asDatetime($model->complete_datetime);
                                                                    } else {
                                                                        return ' - ';
                                                                    }
                                                                }
                                                            ],
                                                        ],
                                                    ]);
                                                    ?>
                                                <?php } ?>
                                            </div>


                                        </div>
                                        <?php $this->registerJs($this->render('index_js'), \yii\web\VIEW::POS_END); ?>
                                        <?php $this->registerCss(".ui-datepicker-calendar {display: none;}"); ?>

