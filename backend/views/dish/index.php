<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\DishesSearch $searchModel
 */
$this->title = 'Dishes';
$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => BASE_URL. 'backend/web/index.php/restaurant/view?id='.$_GET['DishesSearch']['restaurant_id']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dish-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>
    <?php
    if (Yii::$app->session->getFlash('error')) {
        echo $this->render('../includes/error_message');
    }
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
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Combo Dish</i>',
                                    'url' => [
                                        'combo-dish/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Menu</i>',
                                    'url' => [
                                        'menu/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Restaurant</i>',
                                    'url' => [
                                        'restaurant/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Dish Topping</i>',
                                    'url' => [
                                        'dish-topping/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Fav Dish</i>',
                                    'url' => [
                                        'fav-dish/index',
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
                'id',
                [
                    'attribute' => 'menu_id',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->menu->title;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(\common\models\base\Menu::find()->where(['restaurant_id' => $resraurant_id])->orderBy('title')->all(), 'id', 'title'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Any Menu'],
                ],
//                'restaurant_id',
                'title',
                'description',
                'ingredients',
                'price',
            /* 'discount' */
            /* 'is_deleted' */
            /* 'status' */
            ],
        ]);
        ?>
    </div>


</div>
