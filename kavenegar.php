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
	$pluginData[kavenegar][type] = 'notify';
	$pluginData[kavenegar][name] = 'کاوه نگار (وب سرویس پیام کوتاه)';
	$pluginData[kavenegar][uniq] = 'kavenegar';
	$pluginData[kavenegar][description] = 'ارسال اطلاعات خرید به موبایل کاربر';
	$pluginData[kavenegar][note] = 'برای تهیه پنل و ارسال پیامک به وب سایت <a href="https://panel.kavenegar.com/ style="color:#FF7200">panel.kavenegar.com</a> مراجعه کنید.';
	$pluginData[kavenegar][author][name] = 'kavenegar';
	$pluginData[kavenegar][author][url] = 'https://Kavenegar.com';
	$pluginData[kavenegar][author][email] = 'support@Kavenegar.com';
	
	//-- فیلدهای تنظیمات پلاگین
	$pluginData[kavenegar][field][config][1][title] = 'شماره ارسال';
	$pluginData[kavenegar][field][config][1][name] = 'sender';
	$pluginData[kavenegar][field][config][2][title] = 'کلید شناسایی';
	$pluginData[kavenegar][field][config][2][name] = 'apikey';
	
	
	//-- تابع پردازش و ارسال اطلاعات
	function notify__kavenegar($data,$output,$payment,$product,$cards)
	{
		global $db,$smarty;
		if ($output[status] == 1 AND $payment[payment_mobile] AND $cards)
		{
			$sms_text='';
			foreach($cards as $card)
			{
				$sms_text = 'نوع:' . $product[product_title] . "\r\n";
				if($product[product_first_field_title]!="")
					$sms_text .= $product[product_first_field_title] . ': ' . $card[card_first_field];
				if($card[card_second_field]!="")
					$sms_text .= "\r\n" . $product[product_second_field_title] . ': ' . $card[card_second_field];
				if($card[card_third_field]!="")
					$sms_text .=  "\r\n" . $product[product_third_field_title] . ': ' . $card[card_third_field];
                
                sendsms_kavenegar($data, $payment[payment_mobile], $sms_text);
                $sms_text='';
			}
		}
	}
    function sendsms_kavenegar($data, $receptor, $message)
    {
		if(is_array($receptor))
		{
			$receptor = implode(",",$receptor);
 		}
		file_get_contents("https://api.kavenegar.com/v1/".$data[apikey]."/sms/send.json?sender=" . $data[sender] . "&receptor=" .$receptor. "&message=" . urlencode($message)."&from=freer");
	}