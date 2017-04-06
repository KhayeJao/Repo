<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Content $model
 */
$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
