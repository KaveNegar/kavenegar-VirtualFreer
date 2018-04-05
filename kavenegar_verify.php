<?php
	/*
	  Virtual Freer
	  http://freer.ir/virtual

	  Copyright (c) 2011 Mohammad Hossein Beyram, freer.ir

	  This program is free software; you can redistribute it and/or modify
	  it under the terms of the GNU General Public License v3 (http://www.gnu.org/licenses/gpl-3.0.html)
	  as published by the Free Software Foundation.
	*/
	//-- اطلاعات کلی پلاگین
	$pluginData[kavenegar_verify][type] = 'notify';
	$pluginData[kavenegar_verify][name] = 'کاوه نگار (وب سرویس پیام کوتاه)';
	$pluginData[kavenegar_verify][uniq] = 'kavenegar_verify';
	$pluginData[kavenegar_verify][description] = 'ارسال اطلاعات خرید به موبایل کاربر (با استفاده از کداعتبار سنجی)';
	$pluginData[kavenegar_verify][note] = 'برای تهیه پنل و ارسال پیامک به وب سایت <a href="https://panel.kavenegar.com/ style="color:#FF7200">panel.kavenegar.com</a> مراجعه کنید.';
	$pluginData[kavenegar_verify][author][name] = 'Kavenegar';
	$pluginData[kavenegar_verify][author][url] = 'https://kavenegar.com';
	$pluginData[kavenegar_verify][author][email] = 'support@kavenegar.com';
	
	//-- فیلدهای تنظیمات پلاگین
	$pluginData[kavenegar_verify][field][config][1][title] = 'کلید شناسایی';
	$pluginData[kavenegar_verify][field][config][1][name] = 'apikey';
	$pluginData[kavenegar_verify][field][config][2][title] = 'نام الگوی اعتبار سنجی';
	$pluginData[kavenegar_verify][field][config][2][name] = 'template';
	
	//-- تابع پردازش و ارسال اطلاعات
	function notify__kavenegar_verify($data,$output,$payment,$product,$cards)
	{
		global $db,$smarty;
		if ($output[status] == 1 AND $payment[payment_mobile] AND $cards)
		{
			foreach($cards as $card)
			{
				$token=$token2=$token3='';
				if($product[product_first_field_title]!="")
					$token = $card[card_first_field];
				if($card[card_second_field]!="")
					$token2 = $card[card_second_field];
				if($card[card_third_field]!="")
					$token3 = $card[card_third_field];
                sendsms_kavenegar_verify($data, $payment[payment_mobile], $token,$token2,$token3);  
			}
		}
	}
    function sendsms_kavenegar_verify($data, $receptor,$token,$token2,$token3)
	{
		if(is_array($receptor))
			$receptor = implode(",",$receptor);
		file_get_contents("https://api.kavenegar.com/v1/".$data[apikey]."/verify/lookup.json?receptor=".$receptor."&template=".$data[template]."&token=".$token."&token2=".$token2."&token3=".$token3);
    }