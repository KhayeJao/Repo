<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="fixed-header  dashboard ">
        <?php $this->beginBody() ?>
        <?php echo $this->render('//includes/sidebar'); ?>
        <div class="page-container">
            <?php echo $this->render('//includes/header'); ?>
            <div class="page-content-wrapper">
                <div class="content sm-gutter">
                    <?= Alert::widget() ?>
                    <div class="container-fluid padding-25 sm-padding-10">
                        <?=
                        Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ])
                        ?>
                        <?= $content ?>
                    </div>
                </div>
                <div class="container-fluid container-fixed-lg footer">
                    <?php echo $this->render('//includes/footer'); ?>
                </div>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
 <script>
 
  $( document ).ready(function() {
	 $("#notifi").on("click", ".sp", function () {
		 var id= $(this).attr('id'); 
		 var url = "http://khayejao.com/backend/web/index.php/order/orderviewstaus?id="+id;  
		 $.ajax({url: url, success: function(result){ }});
		
	  });
  });
	 
	$(".mess").hover(function () {
    $(".notifi").stop(true,true).delay(500).show(0);
}, function () {
    $(".notifi").stop(true,true).delay(500).hide(0);
}); 
	 var url = "http://khayejao.com/backend/web/index.php/order/neworder";
	var url1 = "http://khayejao.com/backend/web/index.php/order/newordershow";
	$(document).ready(function(){
	  setInterval(function(){
		$('#sp-noti').load(url);
		 
		}, 3000);
		
		setInterval(function(){
		$('#notifi').load(url1);
		 
		}, 3000);
		
	});
 
	
	 
	 
</script> 
 