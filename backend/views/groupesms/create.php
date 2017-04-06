<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Template $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Group SMS', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupesms-create">  
    
    <div class="clearfix"></div>  
    <?= $this->render('_form', [
    'model' => $model,'model1' => $model1,'marketing'=>$marketing,'template'=>$template,
    ]); ?>

</div>
