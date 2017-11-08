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
	
	public function deleteRaspBEEUser($user=''){
		$opts = [
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FORBID_REUSE   => true,
			CURLOPT_CUSTOMREQUEST =>  "DELETE",
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
			CURLOPT_URL            => 'http://'.$this->ip.'/api/'.$this->apikey.'/config/whitelist/'.$user,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_CONNECTTIMEOUT => 30
		];
		return self::genericResponseProcess($opts);
	}
	
	public function findRaspBEE(){
		$opts = [
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
			CURLOPT_URL            => "https://dresden-light.appspot.com/discover",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_CONNECTTIMEOUT => 30
		];		
		return self::genericResponseProcess($opts);
	}
	
	private function genericDelete($url=null,$param=null){
		//if ($url==null) return false;
		//$ch = curl_init();
		$opts = [
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FORBID_REUSE   => true,
			CURLOPT_CUSTOMREQUEST =>  "DELETE",
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
			CURLOPT_POSTFIELDS     => $param,
			CURLOPT_URL            => $url,
			CURLOPT_POST		   => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_CONNECTTIMEOUT => 30
		];
		return self::genericResponseProcess($opts);
	}
	
	private function genericGet($url=null){
		//if ($url==null) return false;
		//$ch = curl_init();
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
		return self::genericResponseProcess($opts);
	}
	
	private function genericPost($url=null,$param=null){
		//if ($url==null) return false;
		//$ch = curl_init();
		$opts = [
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FORBID_REUSE   => true,
			CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
			CURLOPT_POSTFIELDS     => $param,//"{\"devicetype\":\"jeedomRaspBEEPlugin\"}",
			CURLOPT_URL            => $url,
			CURLOPT_POST		   => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_CONNECTTIMEOUT => 30
		];
		return self::genericResponseProcess($opts);
	}
	
	private function genericResponseProcess($opts){
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$result=curl_exec ($ch);
		$error=curl_error($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		//error_log("genericResponseProcess result : ".$result,3,"/tmp/prob.txt");
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
		// méthode 1
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
		$response = self::genericResponseProcess($opts);
		if ($response->state=="ok")
			return $response;
		// si la premère méthode échoue on passe à la seconde
		//user et pwd par défaut
		$usr = "delight";
		$pwd = "delight";
		$opts[CURLOPT_HTTPHEADER] = array('Content-Type: application/json','Authorization: Basic '.base64_encode(utf8_encode($usr.':'.$pwd)));
		$response = self::genericResponseProcess($opts);		
		return $response;								
	}
	
	public function getConf(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/config");
	}
	
	public function getSensors(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/sensors");		
	}
	
	public function getGroups(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/groups");		
	}
	
	public function getGroupAttributes($groupId){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/groups/".$groupId);
	}
		
	public function getLights(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/lights");
	}
	
	public function getTouchlink(){
		return self::genericGet("http://".$this->ip."/api/".$this->apikey."/touchlink/scan");
	}
	
	public function getTouchlinkIdentify($id){
		return self::genericPost("http://".$this->ip."/api/".$this->apikey."/touchlink/".$id."/identify");
	}
	
	public function getTouchlinkRefresh($id){
		return self::genericPost("http://".$this->ip."/api/".$this->apikey."/touchlink/scan");
	}
	
	public function groupCreate($name){
		return self::genericPost("http://".$this->ip."/api/".$this->apikey."/groups",'{"name":"'.$name.'"}');
	}
	
	public function groupDelete($id){
		return self::genericDelete("http://".$this->ip."/api/".$this->apikey."/groups/".$id);
	}
	
	public function removeFromGroup($params){
		$groupAttrAction = self::getGroupAttributes($groupId);
		$groupAttr = json_decode($groupAttrAction->message);
		$lightsGroup = $groupAttr->{$params['groupId']}->lights;
		foreach ($lightsGroup as $light){
			
			error_log("comparaison(".$light."<->".$params['deviceId'].")",3,"/tmp/prob.txt");
			if ($light === $params['deviceId']){
				error_log("lumiere trouvée(".$light.")",3,"/tmp/prob.txt");
				break;				
			}
		}
		//error_log("removeFromGroup(".json_encode($groupAttr).")",3,"/tmp/prob.txt");
			$response->state="ok";
			$response->error=0;
			$response->message="Element supprimé du groupe";
			return $response;		
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