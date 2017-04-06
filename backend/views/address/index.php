<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\AddressSearch $searchModel
 */
$this->title = 'Addresses';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="address-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">

        <div class="pull-right">
            <?php
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
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Area</i>',
                                    'url' => [
                                        'area/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> User</i>',
                                    'url' => [
                                        'user/index',
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
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
//                'id',
                [
                    'attribute' => 'user_id',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                return $model->user->first_name . " " . $model->user->last_name;
            }, 'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(\common\models\base\User::find()->orderBy('username')->asArray()->all(), 'id', 'username'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Any User'],
                ],
                'address_line_1',
                'address_line_2',
//                'area',
                [
                    'attribute' => 'area',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                return $model->area0->area_name;
            }, 'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(\common\models\base\Area::find()->orderBy('area_name')->asArray()->all(), 'id', 'area_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Any Area'],
                ],
                'city',
                'pincode',
            /* 'country' */
            /* 'created_on' */
            ],
        ]);
        ?>
    </div>


</div>
