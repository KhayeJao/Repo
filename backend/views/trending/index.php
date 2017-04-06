<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\TrendingSearch $searchModel
 */
$this->title = 'Sponsored';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cuisine-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
        <p class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Sponsored', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
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
								'view' => function ($url, $model) {
								return '';
							},
						 ],
                            'contentOptions' => ['nowrap' => 'nowrap']
                        ],
                        'id',
						'type',
                        'title',
                        'description',
                        [
                            'attribute' => 'image',
                            'format' => 'image',
                            'value' => function ($model) {
                                return $model->getResizeImageUrl();	
                            },
                        ],
                        'status',
                       
                    ],
                ]);
                ?>
    </div>
</div>
