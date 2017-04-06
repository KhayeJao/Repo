
<?php
use yii\helpers\Url;
?>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Cancel Order - <?= $model->order_unique_id ?></title>


        <style>
            
            a{text-decoration:none;}
            .mailingOptionsTD a {color:#888888;font-weight:bold;};
            ul li {list-style: none};
            img { display: block; }
            @media only screen and (max-device-width: 480px) {

                table[class="table"] {
                    width: 310px !important;
                }

                td[class="cell"] {
                    width: 300px !important;
                }

                td[class="logocell"] {
                    padding-top: 15px !important; 
                    padding-left: 15px !important;
                    width: 300px !important;
                }
                table[class="hide"], img[class="hide"], td[class="hide"] {
                    display: none !important;
                }
                img[class="divider"] {
                    height: 1px !important;
                }
                td[class="divider2"] {
                    width: 7px !important;
                }
                img[id="screenshot"] {
                    width: 300px !important;
                    height: 200px !important;
                }
                table[class="promotable"], td[class="promocell"], td[class="contentblock"], table[class="footercontents"], table[class="footermetadata"], td[class="footerlogodetails"], table[class="featureditems"] {
                    width: 300px !important;
                }
                img[class="itemimage"] {
                    width: 95px !important;
                    height: 130px !important;
                }
                table[class="featureditems_left"], table[class="featureditems_center"], table[class="featureditems_right"] {
                    width: 95px !important;
                }
                h4[class="secondary"] {
                    line-height: 22px !important;
                    margin-bottom: 15px !important;
                    font-size: 18px !important;
                }
                td[class="footermenucontents"] {
                    font-size: 13px !important;
                }
                p[class="metadata"] {
                    font-size: 11px !important;
                }
                span[class="item_description"]{
                    font-size: 14px !important;
                    line-height: 20px !important;
                }
                a[class="price"] {
                    padding-left:  0px !important;
                }
                /*   
                     
                        
                        td[class="footershow"] {
                          width: 300px !important;
                     }
                        
                        
                     
                        
                        p[class="reminder"] {
                                font-size: 11px !important;
                        }
                */
            }



        </style>
        <meta name="robots" content="noindex,nofollow">
        <meta property="og:title" content="Holday Sale! Up to 20% off all items">
    </head>

    <body topmargin="0" leftmargin="0" style="width: 100% ! important; background-image: none; background-repeat: repeat; background-position: left top; background-attachment: scroll;-webkit-text-size-adjust:none;" bgcolor="#ffffff" marginheight="0" marginwidth="0">

        <!-- MAIN TABLE -->
        <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td bgcolor="#ffffff" width="100%">
                        <!-- CONTENT TABLE 620px -->
                        <table class="table" align="center" border="0" cellpadding="10" cellspacing="0" width="620" bgcolor="#f11e00" >
                            <tbody>
                                <tr>
                                    <td class="cell" width="600">
                                        <!-- OK Header details -->
                                        <table class="table" border="0" cellpadding="0" cellspacing="0" width="600" >
                                            <tbody>
                                                <tr>
                                                    <!-- logo -->
                                                    <td class="logocell" bgcolor="#f11e00" width="600">
                                                        <img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/spacer.gif' ?>" class="hide" style="display: block;" border="0" height="0" width="1"><br class="hide">
                                                        <a href="<?= Url::home(TRUE) ?>"><img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/logo.png' ?>" alt="Storesletter" style="display: block;" height="41" width="600"></a><br>
                                                        <img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/spacer.gif' ?>" style="display: block;" border="0" height="0" width="1">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- OK MAIN Banner TEXT -->
                                        <table class="promotable" border="0" cellpadding="20" cellspacing="0" width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="promocell" bgcolor="#ededed" width="600" >
                                                        <h4 class="secondary" style="color:#333333 !important;font-size:28px;line-height:32px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:15px;padding-top:0;padding-bottom:0;font-weight:bold;"><strong>ORDER #<?= $model->order_unique_id ?></strong></h4>
                                                        <p style="color:#454545;font-size:22px;line-height:24px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:5px;padding-top:0;padding-bottom:0px;font-weight:bold;">
                                                            Voila!
                                                        </p>
                                                        <p style="color:#454545;font-size:15px;line-height:22px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:5px;padding-top:0;padding-bottom:0px;font-weight:normal;">
                                                            Your order with ID <?= $model->order_unique_id ?> has been confirmed by Admin. Check your profile for further updates!
                                                        </p>
                                                        <p style="color:#454545;font-size:15px;line-height:22px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:5px;padding-top:0;padding-bottom:0px;font-weight:bold;">
                                                            Thank you for choosing <?= \Yii::$app->name ?>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/spacer.gif' ?>" class="divider" style="display: block;" border="0" height="20" width="1"><br>
                                        <!-- OK Subscription edit and meta data -->             
                                        <!-- OK Site logo details -->	
                                        <table class="footerlogodetails" border="0" cellspacing="0" cellpadding="0" width="100%" style="padding-bottom: 20px;">
                                            <tbody>
                                                <tr>
                                                    <td style="vertical-align: top; width: 150px;">
                                                        <a href="<?= Url::home(TRUE) ?>"><img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/f-logo.png' ?>" style="border: 0px none;" alt="tw" height="27" width="132"></a>
                                                    </td>
                                                    <td style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;  padding: 8px 0px 0px 10px; vertical-align: top;" bgcolor="#ffffff" align="left">
                                                        <span style="display:block;color:#999999;font-size: 11px">
                                                            <span style="text-decoration:none;color:#999999" class="mailingOptionsTD">&COPY; <a href="<?= Url::home(TRUE) ?>"><?= \Yii::$app->name ?></a>, <?= date('Y') ?>. All rights reserved.</span>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table> <!-- close contents table -->
                    </td>
                </tr>		
            </tbody>
        </table> <!--close main table -->
    </body>
</html>