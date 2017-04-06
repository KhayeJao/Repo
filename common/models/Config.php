<?php

namespace common\models;

use Yii;

Class Config {	
		static function MID()
		{
			$MID="MBK8017";  /* Enter Your Merchant ID here    --------- MBK9002 is for testing.------------ Dont change this for testing------------ Change to Live Mid When going Live   MBK8017------------	*/	
			
			return $MID;
		}	
		static function Secret_Key()
		{
			$secret="v6Ifn6zrImgpvSe0YO7gz5a5LCYm";/* Enter Your Merchant Secret-Key here ju6tygh7u7tdg554k098ujd5468o --------  Dont change this for testing-  live -- v6Ifn6zrImgpvSe0YO7gz5a5LCYm  */
			
			return $secret;
		}
		static function Merchant_Name()
		{
			$merchant_name="khayejao.com";//Enter Your Merchant NAme here---------------	Dont change this for testing------------for live-khayejao.com	
			
			return $merchant_name;
		}
		static function Return_URL()
		{
			$url =  "https://www.khayejao.com/order/returnprocess"; 
			//$return_URL=" /return_process.php";//Enter your site address here in place of http://localhost/test 
			
			return $url;
		}

		}

	
?>
