<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/country', 'v1/area', 'v1/site', 'v1/restaurant', 'v1/area', 'v1/cuisine', 'v1/dish', 'v1/tablebooking', 'v1/address', 'v1/order', 'v1/coupons', 'v1/review'],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'extraPatterns' => [
                        'GET act/search' => 'search',
                        'POST act/login' => 'login',
                        'POST act/autocomplete' => 'autocomplete',
                        'POST act/search' => 'search',
                        'POST act/view' => 'view',
                        'POST act/retrivetables' => 'retrivetables',
                        'POST act/useraddresses' => 'useraddresses',
                        'POST act/orders' => 'orders',
                        'POST act/orderview' => 'orderview',
                        'POST act/outputinfo' => 'outputinfo',
                        'POST act/tblbooking' => 'tblbooking',
                        'POST act/tblbookingview' => 'tblbookingview',
                        'POST act/tblbookingpdf' => 'tblbookingpdf',
                        'POST act/signup' => 'signup',
                        'POST act/editprofile' => 'editprofile',
                        'POST act/place' => 'place',
                        'POST act/requestpasswordreset' => 'requestpasswordreset',
                        'POST act/verifymobilecode' => 'verifymobilecode',
                        'POST act/applycoupon' => 'applycoupon',
                        'POST act/applytablecoupon' => 'applytablecoupon',
                        'POST act/addtofavourite' => 'addtofavourite',
                        'POST act/booktable' => 'booktable',
                        'POST act/paymentsuccess' => 'paymentsuccess',
                        'POST act/paymentcancle' => 'paymentcancle',
                        'POST act/create' => 'create',
                        'POST act/refreshreviews' => 'refreshreviews',
                        'POST act/deals' => 'deals',
                        'POST act/getrestaurenttable' => 'getrestaurenttable',
                        'POST act/locationsearch' => 'locationsearch',
                        'POST act/checkmobile' => 'checkmobile',
                        'POST act/sendmobileverificationcode' => 'sendmobileverificationcode',
                        'POST act/getarea' => 'getarea',
                        'POST act/addaddress' => 'addaddress',
                        'POST act/deleteaddress' => 'deleteaddress',
                        'POST act/autocompletetablebooking' => 'autocompletetablebooking',
                        'POST act/userinfo' => 'userinfo',
                        'POST act/claimreward' => 'claimreward',
                        'POST act/content' => 'content',
                        'POST act/fblogin' => 'fblogin',
                        'POST act/regdeviceid' => 'regdeviceid',
                        'POST act/featured' => 'featured',
                    ],
                ]
            ],
        ],
    ],
    'params' => $params,
];



