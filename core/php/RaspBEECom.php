<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

// interactions vers le RASPBEE

class RaspBEECom{

	public function findRaspBEE(){
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => "https://dresden-light.appspot.com/discover",
		CURLOPT_RETURNTRANSFER => true,
		//CURLOPT_TIMEOUT        => 30,
		//CURLOPT_CONNECTTIMEOUT => 30
		];
		curl_setopt_array($ch, $opts);
		$result=curl_exec ($ch);
		curl_close($ch);
		if ($result===false){
		return false;	
		}else{
		return substr($result,1,-1);	
		}
			
		
		//return json_encode(substr($result,1,-1));
	}

	public function getAPIAccess(){
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FORBID_REUSE   => true,
		CURLOPT_POSTFIELDS     => "{\"devicetype\":\"jeedomPlugin\"}",
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => "http://10.0.0.19/api",
		CURLOPT_POST		   => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_CONNECTTIMEOUT => 30
		];
		curl_setopt_array($ch, $opts);
		$result=curl_exec ($ch);
		curl_close($ch);
		if ($result===false){
		return false;	
		}else{
		return substr($result,1,-1);
		}
	}
}
?>