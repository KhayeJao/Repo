<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\RestaurantServicesSearch $searchModel
 */
$this->title = 'Restaurant Services';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="restaurant-services-index">

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
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Restaurant</i>',
                                    'url' => [
                                        'tblrestaurant/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Service</i>',
                                    'url' => [
                                        'tblservice/index',
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
                        'id',
                        [
                            'attribute' => 'service_id',
                            'value' => function ($model) {
                                return $model->service->title;
                            },
                        ],
                    ],
                ]);
                ?>
    </div>


</div>
