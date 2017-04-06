<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CouponsSearch $searchModel
 */
$this->title = 'Coupons';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="coupons-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <div class="clearfix">
        <p class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Coupons', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

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
                                    'label' => '<i class="glyphicon glyphicon-arrow-left"> Discount Key</i>',
                                    'url' => [
                                        'discount-key/index',
                                    ],
                                ],
                                [
                                    'label' => '<i class="glyphicon glyphicon-arrow-right"> Restaurant Coupons</i>',
                                    'url' => [
                                        'restaurant-coupons/index',
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
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{view}{update}{delete}{sendPushMessage}',
                            'buttons' => [

                                //view button
                            'sendPushMessage' => function ($url, $model) {
                            return Html::a('<span class="fa fa-cloud-upload"></span> Send Push Message', $url, [
										'data-toggle' => 'modal',
										'data-target' => '#msgModal',
                                        'title' => 'Manage Dishes',
                                        'class' => 'btn btn-primary btn-xs',
                                        'Onclick' => 'GetID('.$model->id.')',
                            ]);
                        },
                            ],
                            'urlCreator' => function($action, $model, $key, $index) {
                        if ($action === 'sendPushMessage') {						
                            //$url = Url::to(['coupons/send-push-message', 'id' => $model->id]);
                           // return $url;
                        } else {
                            // using the column name as key, not mapping to 'id' like the standard generator
                            $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                            $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                            return Url::toRoute($params);
                        }
                    },
                            'contentOptions' => ['nowrap' => 'nowrap']
                        ],
                'id',
                'code',
                'coupon_key',
                'title',
                'description',
                'status',
                [
                    'attribute' => 'type',
                    'value' => function ($model) {
                $str = $model->type;
                if ($model->type == 'Restaurant') {
                    $restaurant_coupon_model = common\models\base\RestaurantCoupons::findOne(['coupon_id' => $model->id]);
                    $str .= " - ".$restaurant_coupon_model->restaurant->title;
                }
                return $str;
            },
                ],
//			'perameters',
            /* 'notify' */
            /* 'status' */
            /* 'created_on' */
            ],
        ]);
        ?>
    </div>


</div>

<script>
function GetID(cid){
	$('#coupid').val(cid);
}
</script>
<div id="msgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" > Send Custom Message</h4>
      </div>
      <div class="modal-body text-center">
       <form action="<?=$url = Url::to(['coupons/send-push-message']); ?>" method="post">
		   <input type="hidden" name="id" id="coupid" value="">
		   <textarea class="form-control" name="cmsg"></textarea>
		   <input type="submit"  class="btn btn-success" value="Submit" style="margin-top:20px;">
		    
       </form>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>

  </div>
</div>

