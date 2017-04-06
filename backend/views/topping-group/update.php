<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ToppingGroup $model
 */
$this->title = 'Topping Group ' . $model->title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Topping Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="topping-group-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
