<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0 
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/ui.css',
        'plugins/pace/pace-theme-flash.css',
        'plugins/font-awesome/css/font-awesome.css',
        'plugins/jquery-scrollbar/jquery.scrollbar.css',
        'plugins/bootstrap-select2/select2.css',
        'plugins/switchery/css/switchery.min.css',
        'plugins/nvd3/nv.d3.min.css',
        'plugins/mapplic/css/mapplic.css',
        'plugins/rickshaw/rickshaw.min.css',
        'plugins/jquery-metrojs/MetroJs.css',
        'css/pages-icons.css',
        'css/pages.css', 
        'http://cdn.jsdelivr.net/rupyainr/1.0.0/rupyaINR.min.css?5f3697'
    ];
    public $js = [
        
        'plugins/pace/pace.min.js',
        'plugins/modernizr.custom.js', 
        'plugins/jquery-ui/jquery-ui.min.js', 
        'plugins/jquery/jquery-easy.js',
        'plugins/jquery-unveil/jquery.unveil.min.js',
        'plugins/jquery-bez/jquery.bez.min.js',
        'js/typeahead.bundle.js',
        'plugins/jquery-ios-list/jquery.ioslist.min.js',
        'plugins/jquery-actual/jquery.actual.min.js',
        'plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'plugins/bootstrap-select2/select2.min.js',
        'plugins/classie/classie.js',
        'plugins/switchery/js/switchery.min.js',
        'plugins/jquery-validation/js/jquery.validate.min.js',
        'js/pages.min.js', 
        'js/common.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
