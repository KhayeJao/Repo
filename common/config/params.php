<?php
use kartik\datecontrol\Module;
return [
    'adminEmail' => 'support@khayejao.com',
    'supportEmail' => 'support@khayejao.com',
    'user.passwordResetTokenExpire' => 3600,
    // format settings for displaying each date attribute (ICU format example)
    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'dd-MM-yyyy',
        Module::FORMAT_TIME => 'HH:mm a',
        Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss a',
    ],
    // format settings for saving each date attribute (PHP format example)
    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
        Module::FORMAT_TIME => 'php:H:i',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ]
];
