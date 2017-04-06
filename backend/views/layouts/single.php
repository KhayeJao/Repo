<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\AssetManager;
use yii\widgets\Breadcrumbs;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>
    <body class="fixed-header   ">
        <?php $this->beginBody() ?>
        <div class="login-wrapper ">


            <div class="bg-pic">

                <img src="<?= Yii::getAlias('@web/images/city.jpg'); ?>" data-src="<?= Yii::getAlias('@web/images/city.jpg'); ?>" data-src-retina="<?= Yii::getAlias('@web/images/city.jpg'); ?>" alt="" class="lazy">


                <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
                    <p class="small">
                        <!--<span class="hint-text">Developed By </span> <a href="http://www.flowdriven.com/">FlowDriven Technologies PVT. LTD.</a> -->
                    </p>
                </div>

            </div>
            <div class="login-container bg-white">
                <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
                    <img src="<?= Yii::getAlias('@web/images/logo.png'); ?>" alt="logo" data-src="<?= Yii::getAlias('@web/images/logo.png'); ?>" data-src-retina="<?= Yii::getAlias('@web/images/logo_2x.png'); ?>" height="22">
                    <?=
                    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                    <?= $content ?>
                    <?php echo $this->render('//includes/footer_single'); ?>
                </div>
            </div>



        </div>

        

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
