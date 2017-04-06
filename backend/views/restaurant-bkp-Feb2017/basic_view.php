<?php
use yii\widgets\DetailView;

?>
<div class="restaurant-view">


    <h3>
        <?= $model->title ?>    </h3>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'slogan',
            'address',
            'area',
            'city',
            'min_amount',
//            'logo',
            'food_type',
        ],
    ]);
    ?>
</div>
