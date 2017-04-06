<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Dish $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Dishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dish-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
     <p class="pull-left">
      <?=
            Html::a(
                    '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Dishes', ['dish-master/index'], ['class' => 'btn text-muted btn-xs']
            )
            ?>
            </p>
    <div class="clearfix"></div>

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
