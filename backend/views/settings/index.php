<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SettingsSearch $searchModel
 */
$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="settings-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>


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
                                'view' => function ($url, $model) {
                                    return "";
                                },
                                'delete' => function ($url, $model) {
                                    return "";
                                }
                            ],
                            'contentOptions' => ['nowrap' => 'nowrap']
                        ],
                        'id',
                        'title',
//                        [
//                            'attribute' => 'value',
//                            'format' => 'html',
//                            'value' => function ($model) {
//                                if ($model->type == "file") {
//                                    return Html::img(Yii::$app->params['ImageUrl'] . 'resize/' . $model->value, ['width' => 120]);
//                                } else {
//                                    return $model->value;
//                                }
//                            },
//                                ],
                    ],
                ]);
                ?>
    </div>


</div>
