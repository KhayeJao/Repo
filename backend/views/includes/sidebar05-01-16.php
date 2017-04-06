<?php

use yii\helpers\Url;
?>
<nav class="page-sidebar" data-pages="sidebar">
    <div class="sidebar-header">
        <a href="<?= Url::home(); ?>"><img src="<?= Yii::getAlias('@web/images/logo_white.png'); ?>" alt="logo" class="brand" data-src="<?= Yii::getAlias('@web/images/logo_white.png'); ?>" data-src-retina="<?= Yii::getAlias('@web/images/logo_white_2x.png'); ?>" height="22"></a>
    </div>


    <div class="sidebar-menu">

        <ul class="menu-items">

            <li class="m-t-30 open">
                <a href="<?= Url::to(['order/index']) ?>" class="detailed">
                    <span class="title">Orders</span>
                    <span class="details">All orders</span>
                </a>
                <span class="icon-thumbnail bg-success"><i class="fa fa-paper-plane-o"></i></span>
            </li>
            <?php if (\Yii::$app->user->can('placeOrder')) { ?>
                <li class="">
                    <a href="<?= Url::to(['user/index']) ?>" class="detailed">
                        <span class="title">Users</span>
                    </a>
                    <span class="icon-thumbnail "><i class="fa fa-user"></i></span>
                </li>
            <?php } ?>
            <li class="">
                <a href="<?= Url::to(['restaurant/index']) ?>" class="detailed">
                    <span class="title">Restaurants</span>
                </a>
                <span class="icon-thumbnail "><i class="fa fa-cutlery"></i></span>
            </li>
            <li class="">
                <a href="<?= Url::to(['coupons/index']) ?>"><span class="title">Coupons</span></a>
                <span class="icon-thumbnail "><i class="fa fa-scissors"></i></span>
            </li>


            <?php if (\Yii::$app->user->can('manageCuisine')) { ?>
                <li class="">
                    <a href="<?= Url::to(['cuisine/index']) ?>"><span class="title">Cuisine</span></a>
                    <span class="icon-thumbnail"><i class="fa fa-globe"></i></span>
                </li>
            <?php } ?>
            <?php if (\Yii::$app->user->can('manageService')) { ?>
                <li class="">
                    <a href="<?= Url::to(['service/index']) ?>">
                        <span class="title">Services</span>
                    </a>
                    <span class="icon-thumbnail"><i class="fa fa-sliders"></i></span>
                </li>
            <?php } ?>
            <?php if (\Yii::$app->user->can('manageArea')) { ?>
                <li class="">
                    <a href="<?= Url::to(['area/index']) ?>">
                        <span class="title">Area</span>
                    </a>
                    <span class="icon-thumbnail"><i class="fa fa-globe"></i></span>
                </li>
            <?php } ?>
            <?php if (\Yii::$app->user->can('manageSiteSetting')) { ?>
                <li class="">
                    <a href="<?= Url::to(['settings/index']) ?>"><span class="title">Setting</span></a>
                    <span class="icon-thumbnail"><i class="fa fa-cogs"></i></span>
                </li>
            <?php } ?>
            <?php if (\Yii::$app->user->can('manageSiteContent')) { ?>
                <li class="">
                    <a href="<?= Url::to(['content/index']) ?>">
                        <span class="title">CMS</span>
                        <span class="details">Manage Site Content</span>
                    </a>
                    <span class="icon-thumbnail"><i class="fa fa-list-alt"></i></span>
                </li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
    </div>

</nav>