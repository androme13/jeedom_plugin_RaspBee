<?php
/* This file is part of Plugin RaspBEE for jeedom.
 *
 * Plugin RaspBEE for jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plugin RaspBEE for jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plugin RaspBEE for jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

 require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
 
// interactions vers le RASPBEE


class RaspBEECom {
	
	private $apikey = null;
	private $ip = null;
	private $responseHelper = array("error" => 0, "message" => "", "state" => "");
	
	public function __construct() {
       	$this->ip = config::byKey('raspbeeIP','RaspBEE');
		$this->apikey = config::byKey('raspbeeAPIKEY','RaspBEE');		
    }	
	
	public function deleteRaspBEEUser($user){
		error_log("user : ".$user,3,"/tmp/prob.txt");

		if ($user===null){
			$return="Utilisateur à supprimer non defini";
			$return->state="error";
			return $return;
			//return array('message' => 'Utilisateur à supprimer non defini', 'code' => 0, 'state' => 'error');
		}
		$url= 'http://'.$this->ip.'/api/'.$this->apikey.'/config/whitelist/'.$user;		
		//if ($id===null || $command===null || $type===null)return false;
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FORBID_REUSE   => true,
		//CURLOPT_POSTFIELDS     => $command,
		CURLOPT_CUSTOMREQUEST =>  "DELETE",
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => $url,
		//CURLOPT_POST		   => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_CONNECTTIMEOUT => 30
		];
		curl_setopt_array($ch, $opts);
		$result=curl_exec ($ch);
		error_log("deleteRaspBEEUser result : ".$result,3,"/tmp/prob.txt");

		if (curl_errno($ch)) {
			$curl_error = curl_error($ch);
			curl_close($ch);
			throw new Exception(__('Echec de la requête http : ', __FILE__) . $url . ' Curl error : ' . $curl_error, 404);
		}
		curl_close($ch);
		return (is_json($result)) ? json_decode($result, true) : $result;			
	}
	
	public function findRaspBEE(){
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => "https://dresden-light.appspot.com/discover",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_CONNECTTIMEOUT => 30
		];
		curl_setopt_array($ch, $opts);
		$result=curl_exec ($ch);
		$error=curl_error($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$response = $responseHelper;
		if ($result===false){
			$response->state="nok";
			$response->error=$httpcode;
			$response->message=$error;
			return $response;
		}else{		
			$response->state="ok";
			$response->error=$httpcode;
			if ($response->error!='200')$response->state="nok";
			$response->message=$result;
			if ($response->message=='')
			$response->message=strval($response->error);			
			return $response;
		}		
	}

	public function getAPIAccess(){
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FORBID_REUSE   => true,
		CURLOPT_POSTFIELDS     => "{\"devicetype\":\"jeedomRaspBEEPlugin\"}",
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => "http://".$this->ip."/api",
		CURLOPT_POST		   => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 30,
		CURLOPT_CONNECTTIMEOUT => 30
		];
		curl_setopt_array($ch, $opts);
		$result=curl_exec ($ch);
		$error=curl_error($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$response = $responseHelper;
		if ($result===false){
			$response->state="nok";
			$response->error=$httpcode;
			$response->message=$error;
			return $response;
		}else{		
			$response->state="ok";
			$response->error=$httpcode;
			if ($response->error!='200')$response->state="nok";			
			$response->message=$result;
			if ($response->message=='')
			$response->message=strval($response->error);			
			return $response;
		}		
	}
	
	
	public function getConf(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/config");
	}
	
	public function getSensors(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/sensors");		
	}
	
	public function getGroups(){
		//error_log("|synchro groupes|",3,"/tmp/rasbee.err");
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/groups");
		
	}
	
	private function genericGet($url=null){
		if ($url==null) return false;
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FORBID_REUSE   => true,
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => $url,
		//CURLOPT_POST		   => true,
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
		return $result;//substr($result,1,-1);
		}
	}
	
	public function getLights(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/lights");
	}
	
	
	
	public function sendCommand($type=null,$id=null,$command=null){
		//error_log("sendLightCommand(".$id.":".$command.")",3,"/tmp/prob.txt");
		$url= 'http://'.$this->ip.'/api/'.$this->apikey.'/'.$type.'/'.$id;
		if ($type=="groups") $url=$url."/action";
		if ($type!="groups") $url=$url."/state";
		error_log("url :".$url,3,"/tmp/prob.txt");
		if ($id===null || $command===null || $type===null)return false;
		$ch = curl_init();
		$opts = [
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_FORBID_REUSE   => true,
		CURLOPT_POSTFIELDS     => $command,
		CURLOPT_CUSTOMREQUEST =>  "PUT",
		CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
		CURLOPT_URL            => $url,
		//CURLOPT_POST		   => true,
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
		return $result;//substr($result,1,-1);
		}
	}
}

?>