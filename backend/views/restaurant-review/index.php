<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\RestaurantReviewSearch $searchModel
 */
$this->title = 'Restaurant Reviews';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="restaurant-review-index">

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
                                        'tbluser/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Restaurant</i>',
                                    'url' => [
                                        'tblrestaurant/index',
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
                                'view' => function ($url, $model) {
                                    return '';
                                },
                            ],
                            'contentOptions' => ['nowrap' => 'nowrap']
                        ],
//                        [
//                            'attribute' => 'status',
//                            'vAlign' => 'middle',
//                            'filterType' => GridView::FILTER_SELECT2,
//                            'filter' => ['Active' => 'Active', 'Inactive' => 'Inactive'], // any list of values
//                            'filterInputOptions' => ['placeholder' => 'Select Status'],
//                            'filterWidgetOptions' => [
//                                'pluginOptions' => ['allowClear' => true],
//                            ],
//                            'format' => 'raw',
//                            'class' => 'kartik\grid\EditableColumn',
//                            'editableOptions' => function ($model, $key, $index) {
//                        return [
//                            'model' => $model,
//                            'attribute' => 'status',
//                            'size' => 'md',
//                            'afterInput' => function($form, $widget) {
//                                echo $form->field($widget->model, 'id')->hiddenInput(['placeholder' => 'Enter id...']);
//                            },
//                                    'format' => 'button',
//                                    'editableValueOptions' => ['class' => 'well well-sm'],
//                                    'name' => 'status',
//                                    'header' => 'Status',
//                                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
//                                    'data' => ['Active' => 'Active', 'Inactive' => 'Inactive'], // any list of values
//                                    'formOptions' => ['action' => Url::to('changestatus')],
//                                ];
//                            },
//                                ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw', //It was 'html' before
                            'value' => function ($data) {
                                print_r($data->status);
                                if ($data->status == "Active") {
                                    return Html::a("Inactive", ['changestatus', 'status' => 'Inactive', 'id' => $data->id], ['class' => 'btn btn-success']);
                                } else {
                                    return Html::a("Active", ['changestatus', 'status' => 'Active', 'id' => $data->id], ['class' => 'btn btn-success']);
                                }
                            }                                 //missing parenthesis
                                ],
                                'id',
                                [
                                    'attribute' => 'user_id',
                                    'value' => function($model) {
                                        return $model->user->username;
                                    }
                                ],
                                'title',
                                'comment',
                                'rate',
                                'created_on',
                            /* 'status' */
                            ],
                        ]);
                        ?>
    </div>


</div>
