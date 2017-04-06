<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Order $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Order', 'url' => ['takeorder/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
