<?php
	
	function apiRequest($url)
	{
		global $API_KEY;
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: '.$API_KEY, 'Accept-Charset: application/x-www-form-urlencoded; charset=UTF-8'));
		
		$object = json_decode(curl_exec($ch)); 
		curl_close($ch);
		return $object;
	}