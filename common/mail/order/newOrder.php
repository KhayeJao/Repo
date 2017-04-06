<?php

use common\models\base\OrderPayments;
use common\models\base\OrderTopping;
use yii\helpers\Url;
?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="format-detection" content="telephone=no"/>
        <title><?= \Yii::$app->name ?> Order - <?= $model->order_unique_id ?></title>
        <style type="text/css">

            a								{text-decoration:none;}
            .AnnouncementTD				{color:#7f8c9d;font-family: sans-serif;font-size:16px;text-align:right;line-height:150%;}
            .AnnouncementTD a				{color:#7f8c9d;}

            .viewOnlineTD					{color:#ffffff;font-family: sans-serif;font-size:12px;text-align:left;line-height:22px;}
            .viewOnlineTD a					{color:#ffffff;}

            .menuTD						{color:#ffffff;font-family: sans-serif;font-size:12px;text-align:right;line-height:22px;}
            .menuTD a						{color:#ffffff;}

            .buttonTD, .iconTextTD,.td528Button	{color:#ffffff;font-family: sans-serif;font-size:15px;font-weight:lighter;text-align:center;line-height:23px;}
            .iconTextTD							{text-align:left; font-size:13px;color:#c0c7d4;}
            .buttonTD a,.td528Button a			{color:#ffffff;display:block;}
            .iconTextTD a					{color:#ff675f; font-weight:bold;}

            .headerTD						{color:#7f8c9d;font-family: sans-serif;font-size:18px;text-align:center;line-height:25px;}
            .headerTD a						{color:#ff675f;}
            .header2TD,.iconHDTD			{color:#cfd6e2;font-family: sans-serif;font-size:17px;text-align:center;line-height:25px;}
            .header2TD a,.iconHDTD a		{color:#ff675f; font-weight:bold;}
            .header3TD						{color:#7f8c9d;font-family: sans-serif;font-size:17px;text-align:center;line-height:27px;}
            .header3TD a					{color:#ff675f; font-weight:bold;}
            .header4TD						{color:#7f8c9d;font-family: sans-serif;font-size:18px;text-align:left;line-height:25px;}
            .header4TD a					{color:#ff675f;}
            .headerPrcTD					{color:#7f8c9d;font-family: sans-serif;font-size:40px;text-align:center;}
            .headerPrcTD a					{color:#7f8c9d;}
            .iconHDTD						{color:#ffffff;}

            .RegularTextTD,
            .RegularText2TD,
            .RegularText3TD	,
            .confLinkTD						{color:#7f8c9d;font-family: sans-serif; font-size:13px;text-align:left;line-height:23px;}
            .RegularText3TD					{text-align:center; font-size:15px;}
            .RegularTextTD a,
            .RegularText2TD a,
            .RegularText3TD a				{color:#ff675f; font-weight:bold;}
            .confLinkTD a					{color:#67bffd; font-weight:bold;word-break:break-all;}

            .invoiceTD						{color:#7f8c9d;font-family: sans-serif; font-size:19px;text-align:center;line-height:23px;}
            .invoiceTD a						{color:#ff675f;}
            .invCap							{color:#7f8c9d;font-family: sans-serif;text-align:center;font-size:15px;}
            .invCap a						{color:#7f8c9d;}
            .invReg							{color:#7f8c9d;font-family: sans-serif;font-size:13px;text-align:center;}
            .invReg a						{color:#7f8c9d;}
            .invInfoA						{color:#7f8c9d;font-family: sans-serif; font-size:12px;text-align:right;line-height:20px;}
            .invInfoA a						{color:#7f8c9d;pointer-events:none;}
            .invInfoB						{color:#7f8c9d;font-family: sans-serif; font-size:12px;text-align:left;line-height:20px;}
            .invInfoB a						{color:#7f8c9d;pointer-events:none;}

            td a img							{text-decoration:none;border:none;}

            .companyAddressTD				{color:#7f8c9d;font-family: sans-serif;font-size:13px;text-align:center;line-height:190%;}
            .companyAddressTD a			{color:#7f8c9d;}
            .companyAddress2TD			{color:#7f8c9d;font-family: sans-serif;font-size:13px;text-align:center;line-height:190%;}
            .companyAddress2TD a			{color:#7f8c9d;pointer-events:none;}

            .mailingOptionsTD,.termsConTD,.termsCon2TD		{color:#888888;font-family: sans-serif;font-size:12px;text-align:center;line-height:170%;}
            .mailingOptionsTD a,.termsConTD a,.termsCon2TD a	{color:#888888;font-weight:bold;}

            .termsConTD {text-align:left;}
            .termsCon2TD {text-align:right;}
            .termsConTD a,.termsCon2TD a{font-weight:normal;}

            .ReadMsgBody{width:100%;}
            .ExternalClass{width:100%;}
            body{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-webkit-font-smoothing:antialiased;margin:0 !important;padding:0 !important;min-width:100% !important;}



            @media only screen and (max-width: 599px)
            {
                body{min-width:100% !important;}

                td[class=viewOnlineTD]						{text-align:center !important;}
                table[class=table600Logo]  					{width:440px !important;border-bottom-style:solid !important;border-bottom-color:#e1e1e1 !important;border-bottom-width:1px !important;}
                td[class=tdLogo]								{width:440px !important;}
                table[class=table600Menu]					{width:440px !important;}
                td[class=AnnouncementTD]					{width:440px !important;text-align:center !important;font-size:17px !important;}
                table[class=table600Menu] td					{height:20px !important;}
                table[class=tbl6AnctText] .menuTD			{text-align:center !important;font-size:13px !important;line-height:150% !important;}
                table[class=tbl6AnctText] 					{width:440px !important;}
                td[class=viewOnlineTD]						{width:440px !important;}
                td[class=menuTD]							{width:440px !important;font-weight:bold !important;}
                table[class=table600] 						{width:440px !important;}
                table[class=image600] img 					{width:440px !important; height:auto !important;}
                table[class=AnncTable]						{width:100% !important;border:none !important;}
                table[class=table280d]						{width:440px !important; border-radius:0 0 0 0 !important;}
                td[class=LMrg]								{height:8px !important;}
                td[class=LMrg2]								{height:6px !important;}
                td[class=LMrg3]								{height:10px !important;}
                table[class=tblRgBrdr]						{border-right:none !important;}
                td[class=td147]								{width:215px !important;}
                table[class=table147]						{width:215px !important;}
                table[class=table147tblp]						{width:175px !important;}
                td[class=mrgnHrzntlMdl]						{width:10px !important;}
                td[class=mvd]								{height:30px !important;width:440px !important;}
                table[class=centerize]						{margin:0 auto 0 auto !important;}
                table[class=tblBtnCntr2]						{width:398px !important; margin:0 auto 0 auto !important;}
                td[class=table28Sqr] img						{width:440px !important;height:auto !important;margin:0 auto 0 auto !important; border-radius:4px 4px 0 0 !important;}
                td[class=tbl28Rctngl] img						{width:215px !important;height:auto !important;margin:0 auto 0 auto !important;}
                td[class=headerTD] 							{text-align:center !important;}
                td[class=header4TD]							{text-align:center !important;}
                td[class=headerPrcTD]						{font-size:25px !important;}
                td[class=RegularTextTD] 						{font-size:13px !important;}
                td[class=RegularText2TD] 					{height:0 !important; font-size:13px !important;}
                td[class=RegularText3TD] 					{font-size:13px !important;}
                td[class=mailingOptionsTD]					{text-align:center !important;}
                td[class=companyAddressTD]					{text-align:center !important;}
                td[class=esFrMb] 							{width:0 !important;height:0 !important;display:none !important;}
                table[class=table280brdr]					{width:440px !important;}
                table[class=table608]						{width:438px !important;}
                table[class=table518b] 						{width:398px !important;}
                table[class=table518] 						{width:398px !important;}
                table[class=table518c] 						{width:195px !important;}
                table[class=table518c2] 						{width:195px !important;}
                td[class=imgAltTxticTD] img					{width:398px !important;height:auto !important; margin:0 auto 17px auto !important;}
                td[class=iconPdngErase]						{width:0 !important; display:none !important;}
                td[class=table608]							{width:438px !important;}
                td[class=invReg]								{width:133px !important; font-size:14px !important;text-align:center !important;font-weight:lighter !important;}
                td[class=invInfoA]							{text-align:right !important;width:195px !important;}
                td[class=invInfoB]							{text-align:left !important;width:195px !important;}
                td[class=invoiceTD]							{width:390px !important; font-weight:bold;}
                td[class=td528Button]						{width:358px !important;}
                table[class=table528Button]					{width:358px !important;}
                table[class=table528Social]					{width:398px !important;}
                table[class=table250]						{width:177px !important;}
            }



            @media only screen and (max-width: 479px)
            {
                body{min-width:100% !important;}

                td[class=viewOnlineTD]						{text-align:center !important;}
                table[class=table600Logo]  					{width:300px !important;border-bottom-style:solid !important;border-bottom-color:#e1e1e1 !important;border-bottom-width:1px !important;}
                td[class=tdLogo]								{width:300px !important;}
                table[class=table600Menu]					{width:300px !important;}
                td[class=AnnouncementTD]					{width:300px !important;text-align:center !important;font-size:17px !important;}
                table[class=table600Menu] td					{height:20px !important;}
                table[class=tbl6AnctText] .menuTD			{text-align:center !important;font-size:12px !important;line-height:150% !important;}
                table[class=tbl6AnctText] 					{width:300px !important;}
                td[class=viewOnlineTD]						{width:300px !important;}
                td[class=menuTD]							{width:300px !important;font-weight:bold !important;}
                table[class=table600] 						{width:300px !important;}
                table[class=image600] img 					{width:300px !important;height:auto !important;}
                table[class=table608]						{width:298px !important;}
                td[class=table608]							{width:298px !important;}
                table[class=table518] 						{width:260px !important;}
                table[class=table518b] 						{width:268px !important;}
                table[class=table518c] 						{width:268px !important;}
                table[class=table518c2] 						{width:268px !important; margin:20px 0 0 0 !important;}
                td[class=imgAltTxticTD] img					{width:268px !important;height:auto !important;margin:-4px auto 15px auto !important;}
                table[class=table280Button]					{width:264px !important;}
                table[class=centerize]						{margin:0 auto 0 auto !important;}
                table[class=tblBtnCntr2]						{width:264px !important; margin:0 auto 0 auto !important;}
                table[class=AnncTable]						{width:100% !important;border:none !important;}
                table[class=table280d]						{width:300px !important; border-radius:0 0 0 0 !important;}
                td[class=LMrg]								{height:8px !important;}
                td[class=LMrg2]								{height:6px !important;}
                td[class=LMrg3]								{height:10px !important;}
                td[class=wz]									{width:15px !important;}
                table[class=tblRgBrdr]						{border-right:none !important;}
                td[class=td147]								{width:147px !important;}
                table[class=table147]						{width:147px !important;}
                table[class=table147tblp]						{width:117px !important;}
                td[class=mrgnHrzntlMdl]						{width:6px !important;}
                td[class=mvd]								{height:30px !important;width:300px !important;}
                td[class=iconPdngErase]						{width:0 !important; display:none !important;}
                td[class=table28Sqr] img						{width:300px !important;height:auto !important;margin:0 auto 0 auto !important; border-radius:4px 4px 0 0 !important;}
                td[class=tbl28Rctngl] img						{width:147px !important;height:auto !important;margin:0 auto 0 auto !important;}
                td[class=headerTD] 							{font-size:16px !important; font-weight:bold !important;text-align:center !important;}
                td[class=header4TD]							{font-size:16px !important; font-weight:bold !important;text-align:center !important;}
                td[class=iconHDTD] 							{font-size:16px !important;text-align:center !important;}
                td[class=headerPrcTD]						{font-size:18px !important;}
                td[class=RegularTextTD] 						{font-size:13px !important;text-align:left !important;}
                td[class=RegularText2TD] 					{height:0 !important;font-size:13px !important;}
                td[class=RegularText3TD] 					{font-size:13px !important;}
                td[class=mailingOptionsTD]					{text-align:center !important;}
                td[class=companyAddressTD]					{text-align:center !important;}
                td[class=esFrMb] 							{width:0 !important;height:0 !important;display:none !important;}
                table[class=table280brdr]					{width:300px !important;}
                td[class=invReg]								{width:89px !important; font-size:13px !important;text-align:center !important;}
                td[class=invInfoA]							{text-align:center !important;width:268px !important;}
                td[class=invInfoB]							{text-align:center !important;width:268px !important;}
                td[class=invoiceTD]							{width:250px !important;}
                td[class="buttonVrt"]							{height:16px !important;}
                td[class="buttonVrt2"]						{height:12px !important;}
                td[class="buttonVrt3"]						{height:10px !important;}
                td[class=td528Button]						{width:238px !important;}
                table[class=table528Button]					{width:238px !important;}
                table[class=table528Social]					{width:266px !important;}
                table[class=table250]						{width:117px !important;}
                td[class=termsCon2TD]						{text-align:center !important;}
                td[class=termsConTD]						{text-align:center !important;}
            }



        </style>
    </head>
    <body style="background-color:#ededed; margin:0 auto; padding:0;">
        <center>
            <!--HEADER SECTION-->


            <!--LOGO SECTION-->
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" style="table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                <tbody><tr>
                        <td align="center">
                            <table width="600" bgcolor="#ededed" align="center" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                <tbody><tr>
                                        <td width="600" class="tdLogo" bgcolor="#ededed" align="left">
                                            <table width="230" align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" class="table600Logo" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                <tbody><tr>
                                                        <!--LOGO IMAGE'S WIDTH MUST BE 300px-->
                                                        <!--HEIGHT MUST BE 200 px-->
                                                        <!--Open the "logo.PSD" in photoshop-->
                                                        <!--Add your logo, center it VERTICALLY as I did by default (this ensures to have some space at top and bottom as A padding)-->
                                                        <!--FOR BEST RESULTS:MAKE YOUR IMAGE'S WIDTH JUST AS WIDE AS YOUR LOGO (NO SPACING at both LEFT and RIGHT sides at PSD File)-->
                                                        <td>
                                                            <table cellpadding="0" cellspacing="0" bgcolor="#ededed" border="0" class="centerize" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                <tbody><tr>
                                                                        <td valign="middle" align="center" height="47" style="line-height:15px;"><a href="#" target="_blank"><img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/red-logo.png' ?>" style="display:block;" alt="Logo Image" border="0" align="top" hspace="0" vspace="0" width="230" height="47"></a></td>
                                                                        <td width="30" class="esFrMb"></td>
                                                                    </tr>
                                                                </tbody></table>
                                                        </td>
                                                    </tr>
                                                </tbody></table>
                                            <table width="360" align="left" cellpadding="0" cellspacing="0" border="0" class="table600Menu" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                <tbody><tr>
                                                        <td height="10" style="font-size:0;line-height:0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <!-- SHORT SLOGAN (or any text) -->
                                                        <td width="360" valign="middle" align="right" height="47" class="AnnouncementTD"><img src="<?= Yii::getAlias('@uploadUrl') . '/uploads/email/number-right.png' ?>" style="display:block; float:right;" alt="Logo Image" border="0" align="top" hspace="0" vspace="0" width="240" height="47"></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="10" style="font-size:0;line-height:0;">&nbsp;</td>
                                                    </tr>
                                                </tbody></table>
                                        </td>
                                    </tr>
                                </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
            <!--END OF THE MODULE-->
            <!-- INVOICE SECTION -->
            <table width="100%" bgcolor="#ededed" align="center" cellpadding="0" cellspacing="0" border="0" style="table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                <tbody><tr>
                        <td align="center">
                            <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                <tbody><tr>
                                        <td>
                                            <table width="610" align="left" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                <tbody><tr>
                                                        <td>
                                                            <table width="608" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                <tbody><tr>
                                                                        <td>
                                                                            <table align="center" cellpadding="0" cellspacing="0" border="0" width="608" class="table608" bgcolor="#ffffff" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border:1px solid #ffffff; border-radius:4px 4px 0 0;">
                                                                                <tbody><tr>
                                                                                        <td height="15" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                <tbody><tr>
                                                                                                        <td width="20" class="wz"></td>
                                                                                                        <td>
                                                                                                            <!--(BILLED TO) SECTION-->
                                                                                                            <table width="270" align="right" cellpadding="0" cellspacing="0" border="0" class="table518c" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>
                                                                                                                        <!--BILLED TO-->
                                                                                                                        <td width="270" class="invInfoA"><strong>Billed To:</strong></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <!--NAME-->
                                                                                                                        <td class="invInfoA"><?= $model->user_full_name ?></td>
                                                                                                                    </tr>
                                                                                                                    <?php if($model->delivery_type != 'Pickup'){?>
                                                                                                                    <tr>
                                                                                                                        <!-- ADDRESS-->
                                                                                                                        <td class="invInfoA"><?= $model->address_line_1 . ($model->address_line_2 ? ", " . $model->address_line_2 : '') . ", " . $model->area0->area_name . ", " . $model->city . ", " . $model->pincode ?></td>
                                                                                                                    </tr>
                                                                                                                    <?php }?>
                                                                                                                    <tr>
                                                                                                                        <!--email-->
                                                                                                                        <td class="invInfoA"><strong>Mobile : </strong> <?= $model->mobile ?>  <br> <strong>Email : </strong><?= $model->email ?></td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                            <!--YOUR COMPANY'S INFORMATION-->
                                                                                                            <table width="270" align="left" cellpadding="0" cellspacing="0" border="0" class="table518c2" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>

                                                                                                                        <td width="250" class="invInfoB"><strong><?= $model->restaurant->title ?></strong> <br><?= $model->restaurant->address . ", " . $model->restaurant->area . ", " . $model->restaurant->city ?></td>
                                                                                                                        <td width="20" class="iconPdngErase" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                        </td>
                                                                                                        <td width="20" class="wz"></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td colspan="3" height="15" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td width="20" class="wz"></td>
                                                                                                        <td>
                                                                                                            <table width="270" align="right" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table518c" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>
                                                                                                                        <!--INVOICE DATE-->
                                                                                                                        <td width="270" class="invInfoA"><a href="#"><strong>Order Date</strong>: <?= Yii::$app->formatter->asDatetime($model->booking_time) ?></a></td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                            <table width="270" align="left" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table518c" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>
                                                                                                                        <!--INVOICE NO-->
                                                                                                                        <td width="250" class="invInfoB"><a href="#"><strong>Order ID:</strong><?= $model->order_unique_id ?></a></td>
                                                                                                                        <td width="20" class="iconPdngErase"></td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                        </td>
                                                                                                        <td width="20" class="wz"></td>
                                                                                                    </tr>
                                                                                                </tbody></table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td height="30" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                    </tr>
                                                                                </tbody></table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody></table>
                                                            <table width="608" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border:1px solid #ffffff; border-radius:0 0 4px 4px;">
                                                                <tbody><tr>
                                                                        <td>
                                                                            <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tbody><tr>
                                                                                        <td>
                                                                                            <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                <tbody><tr>
                                                                                                        <td width="20" class="wz"></td>
                                                                                                        <td>
                                                                                                            <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>
                                                                                                                        <td height="20" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <!-- INVOICE LABEL -->
                                                                                                                        <td width="564" class="invoiceTD">Invoice</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td height="20" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                            <!--= PRODUCT GROUP (you can copy and paste the entire section to duplicate)=-->
                                                                                                            <?php foreach ($model->orderDishes as $dishKey => $dishValue) { ?>
                                                                                                                <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                    <tbody><tr>
                                                                                                                            <td colspan="3" valign="middle" height="40" class="invCap"><?= $dishValue->dish_title ?>
                                                                                                                                <?php
                                                                                                                                $order_toppings = OrderTopping::findAll(['dish_id' => $dishValue->dish_id, 'order_id' => $model->id]);
                                                                                                                                $topping_arr = array();
                                                                                                                                $productTotal = $dishValue->dish_price * $dishValue->dish_qty;
                                                                                                                                foreach ($order_toppings as $orderToppingKey => $orderToppingValue) {
                                                                                                                                    array_push($topping_arr, $orderToppingValue->topping->title . " (" . ($orderToppingValue->price > 0 ? "Rs " . $orderToppingValue->price : "Free") . ")");
                                                                                                                                    $productTotal = $productTotal + $orderToppingValue->price;
                                                                                                                                }
                                                                                                                                if (count($topping_arr)) {
                                                                                                                                    echo ' - ' . implode(', ', $topping_arr);
                                                                                                                                }
                                                                                                                                ?>

                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color:#f9f9f9;">
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Qty.</td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Price</td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Sub Total</td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color:#f4f4f4;">
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg"><?= $dishValue->dish_qty ?></td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $dishValue->dish_price ?></td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $productTotal ?></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="3" height="20" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            <?php } ?>

                                                                                                            <!--================================ END PRODUCT GROUP =====-->
                                                                                                            <!--= PRODUCT GROUP (you can copy and paste the entire section to duplicate)=-->
                                                                                                            <?php foreach ($model->orderCombos as $comboKey => $comboValue) { ?>
                                                                                                                <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                    <tbody><tr>
                                                                                                                            <td colspan="3" valign="middle" height="40" class="invCap"><?= $comboValue->combo->title ?>
                                                                                                                                <?php
                                                                                                                                $combo_dishes_arr = array();
                                                                                                                                foreach ($comboValue->orderComboDishes as $comboDishesKey => $comboDishesValue) {
                                                                                                                                    array_push($combo_dishes_arr, ($comboDishesValue->dish_qry > 1 ? $comboDishesValue->dish_qry : '') . " " . $comboDishesValue->dish->title);
                                                                                                                                }
                                                                                                                                echo " (" . implode(', ', $combo_dishes_arr) . ")";
                                                                                                                                ?>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color:#f9f9f9;">
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Qty.</td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Price</td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Sub Total</td>
                                                                                                                        </tr>
                                                                                                                        <tr style="background-color:#f4f4f4;">
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg"><?= $comboValue->combo_qty ?></td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $comboValue->price ?></td>
                                                                                                                            <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $comboValue->price * $comboValue->combo_qty ?></td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td colspan="3" height="20" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            <?php } ?>

                                                                                                            <!--============================== END PRODUCT GROUP =====-->
                                                                                                            <!--============================ SUBTOTAL, TOTAL, VAT, ETC. ==-->
                                                                                                            <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <!-- you can duplicate this group or delete it-->
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td width="189" class="invReg"></td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Subtotal</td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $model->sub_total ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="189" class="invReg"></td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Discount</td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg"><?php if ($model->coupon_code) { ?>Rs <?= $model->discount_amount ?><?= ($model->discount_text ? "( " . $model->discount_text . ")" : "") ?><?php } else { ?> N/A <?php } ?></td>
                                                                                                                    </tr>

                                                                                                                    <tr>
                                                                                                                        <td width="189" class="invReg"></td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Tax (<?= $model->tax_text ?>)</td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $model->tax ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="189" class="invReg"></td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Vat (<?= $model->vat_text ?>)</td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $model->vat ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="189" class="invReg"></td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Service Charge</td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $model->service_charge ?> <?= ($model->service_charge_text ? "( " . $model->service_charge_text . ")" : "") ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="189" class="invReg"></td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Total</td>
                                                                                                                        <td width="189" valign="middle" height="25" class="invReg">Rs. <?= $model->grand_total ?></td>
                                                                                                                    </tr>
                                                                                                                    <!-- end group -->
                                                                                                                </tbody></table>
                                                                                                            <!--======================= END SUBTOTAL, TOTAL, VAT. GROUP =-->
                                                                                                            <table width="567" align="left" cellpadding="0" cellspacing="0" border="0" class="table518b" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>
                                                                                                                        <td height="40" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <!--SHIPPING ADDRESS AND Payment Method Information-->
                                                                                                                        <td height="20" class="RegularTextTD"><strong>Payment Mode :</strong><?= ($model->payment_mode == 'COD' ? 'Cash On Delivery' : 'Through ' . $model->payment_mode) ?><br><strong>Order Date-Time :</strong><?= Yii::$app->formatter->asDatetime($model->booking_time) ?><strong>Delivery Type :</strong><?= $model->delivery_type ?></td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                        </td>
                                                                                                        <td width="20" class="wz"></td>
                                                                                                    </tr>
                                                                                                </tbody></table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td height="15" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                    </tr>
                                                                                </tbody></table>
                                                                            <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tbody><tr>
                                                                                        <td>
                                                                                            <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                <tbody><tr>
                                                                                                        <td align="center">
                                                                                                            <table align="center" cellpadding="0" cellspacing="0" bgcolor="#f11e00" border="0" style="border-radius:4px 4px 4px 4px;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                <tbody><tr>
                                                                                                                        <td width="20" class="wz"></td>
                                                                                                                        <td>
                                                                                                                            <table width="528" align="center" cellpadding="0" cellspacing="0" border="0" class="table528Button" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                                                                <tbody><tr>
                                                                                                                                        <td height="8" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <!--BUTTON-->
                                                                                                                                        <!--Use shorter phrases , or the text jumps into an another line-->
                                                                                                                                        <td width="528" align="center" class="td528Button"><a href="<?= Url::to(['user/order', 'id' => $model->order_unique_id],TRUE) ?>" target="_blank">Thank You For Your Order!</a></td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td height="8" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                                                                    </tr>
                                                                                                                                </tbody></table>
                                                                                                                        </td>
                                                                                                                        <td width="20" class="wz"></td>
                                                                                                                    </tr>
                                                                                                                </tbody></table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody></table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td height="21" class="buttonVrt" style="font-size:0;line-height:0;">&nbsp;</td>
                                                                                    </tr>
                                                                                </tbody></table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody></table>
                                                        </td>
                                                    </tr>
                                                </tbody></table>
                                        </td>
                                    </tr>
                                </tbody></table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                <tbody>
                    <tr>
                        <td align="center" width="610" height="30" class="mvd" bgcolor="#ededed" style="font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <!--END OF THE MODULE-->
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" style="table-layout:fixed;margin:0 auto;">
                <tbody><tr>
                        <td align="center">
                            <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" class="table600">
                                <tbody><tr>
                                        <td>
                                            <table width="610" align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" class="table600">
                                                <tbody><tr>
                                                        <td height="0" style="font-size:0;line-height:0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <?php //TODO : Add url for contact us, about etc pages ?>
                                                        <td height="5" class="mailingOptionsTD"><a href="#" target="_blank">Contact Us</a> | <a href="#" target="_blank">Terms & Condition </a> | <a href="#" target="_blank">Privacy Policy </a><br>
                                                                &COPY;<?= date('Y') ?> <a href="<?= Url::home(TRUE) ?>"><?= \Yii::$app->name ?></a>. All rights reserved</td>
                                                        <!--= End of the section -->
                                                    </tr>
                                                    <tr>
                                                        <td height="25" style="font-size:0;line-height:0;">&nbsp;</td>
                                                    </tr>
                                                </tbody></table>
                                        </td>
                                    </tr>
                                </tbody></table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--FOOTER ENDS HERE-->
        </center>

    </body></html>