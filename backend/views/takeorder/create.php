<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\base\User $model
*/
echo "klhklfh";die;
$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Take Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
