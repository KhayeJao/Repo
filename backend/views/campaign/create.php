<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Content $model
 */
$this->title = 'Campaign';
$this->params['breadcrumbs'][] = ['label' => 'All', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-create">

    <?=
    $this->render('_form', [
        'users' => $users
    ]);
    ?>

</div>
