<?php

use yii\helpers\Html;

$this->title = 'Book Table';
$this->params['breadcrumbs'][] = ['label' => 'Table Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'New Booking for ' . $restaurant_info->title;
?>
<div class="col-md-12">

    <h3>Select tables to book</h3>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-3">
                <h5>2 Persons</h5>
                <div class="row">
                    <select id="two_seats" name="two_seats" class="multiple_dd" multiple="multiple">
                        <?php foreach ($two_tables as $twotblKey => $twotblValue) { ?>
                            <option value="<?= $twotblValue->id ?>" data-id='two_seats' data-img-src="<?= Yii::getAlias('@web/images/' . $twotblValue->no_of_seats . '.png'); ?>"><?= $twotblValue->price ?> Rs.</option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <h5>4 Persons</h5>
                <div class="row">
                    <select id="four_seats" name="four_seats" class="multiple_dd" multiple="multiple">
                        <?php foreach ($four_tables as $fourtblKey => $fourtblValue) { ?>
                            <option value="<?= $fourtblValue->id ?>" data-id='four_seats' data-img-src="<?= Yii::getAlias('@web/images/' . $fourtblValue->no_of_seats . '.png'); ?>"><?= $fourtblValue->price ?> Rs.</option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <h5>6 Persons</h5>
                <div class="row">
                    <select id="six_seats" name="six_seats" class="multiple_dd" multiple="multiple">
                        <?php foreach ($six_tables as $sixtblKey => $sixtblValue) { ?>
                            <option value="<?= $sixtblValue->id ?>" data-id='six_seats' data-img-src="<?= Yii::getAlias('@web/images/' . $sixtblValue->no_of_seats . '.png'); ?>"><?= $sixtblValue->price ?> Rs.</option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <h5>8 Persons</h5>
                <div class="row">
                    <select id="eight_seats" name="eight_seats" class="multiple_dd" multiple="multiple">
                        <?php foreach ($eight_tables as $eighttblKey => $eighttblValue) { ?>
                            <option value="<?= $eighttblValue->id ?>" data-id='eight_seats' data-img-src="<?= Yii::getAlias('@web/images/' . $eighttblValue->no_of_seats . '.png'); ?>"><?= $eighttblValue->price ?> Rs.</option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">

    </div>

</div>
<?php $this->registerJs($this->render('book_table_js'), \yii\web\VIEW::POS_END); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/image-picker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerCssFile(Yii::$app->request->baseUrl . '/css/image-picker.css'); ?>
<?php // $this->registerJsFile(Yii::$app->request->baseUrl . '/js/msdropdown/jquery.dd.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php // $this->registerCssFile(Yii::$app->request->baseUrl . '/css/msdropdown/dd.css'); ?>
<?php // $this->registerCss($this->render('book_table_css')); ?>


