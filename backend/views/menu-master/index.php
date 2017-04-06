<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView; 
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MenuSearch $searchModel
 */
$this->title = 'Menus';
//$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => BASE_URL. 'backend/web/index.php/restaurant/view?id='.$_GET['MenuSearch']['restaurant_id']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="menu-index">

    <?php 
	  //if($_GET['MenuSearch']['restaurant_id']==1){
            echo Html::a(
                    '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Menu', ['menu-master/create'], ['class' => 'btn btn-success btn-xs']
            );
	 // }
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
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Dish</i>',
                                    'url' => [
                                        'tbldish/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Menu</i>',
                                    'url' => [
                                        'tblmenu/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Menu</i>',
                                    'url' => [
                                        'tblmenu/index',
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
                 'rowOptions'   => function ($model, $key, $index, $grid) {
                     return ['data-id' => $model->id];
                  },

            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                            'contentOptions' => ['nowrap' => 'nowrap'],

                        ],
                        'id',
                        'title',
                        'excerpt',
                        [
                            'attribute' => 'image',
                            'format' => 'image',
                            'value' => function ($model) {
                                return $model->getResizeImageUrl();
                            },
                        ],
                        'discount',
                        [
                             'attribute' => 'order',
                             'contentOptions' =>['class' => 'order_class','contenteditable'=>'true'],
                              
                        ],
                        'status',
                    ],
                ]);
                ?>
                <?php
                /*
                echo Sortable::widget([
                'type' => Sortable::TYPE_LIST,
                'items' => [
                    ['content' => 'Item # 1'],
                    ['content' => 'Item # 2'],
                    ['content' => 'Item # 3'],
                ]   
            ]);*/
                ?>
                
                
    </div>


</div> 
<?php $this->registerJs($this->render('index_js'), \yii\web\VIEW::POS_END); ?>
 
