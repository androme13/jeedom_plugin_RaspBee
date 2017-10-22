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
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class eqLogicOperate extends eqLogic {
	
	
	/*function checkConfigFile(){
		if (!is_file(dirname(__FILE__) . '/../config/devices/ZHASwitch.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ZHASwitch.json');
		if (!is_json($configFile)) {
			return false;
		}
	}*/
	private $responseHelper = array("error" => 0, "message" => "", "state" => "");
	
	public function createDevice($device,$syncType = ''){
		//error_log("|eqlogic create device|".json_encode($device[type])."|",3,"/tmp/prob.txt");
		$response = $responseHelper;
		foreach (eqlogic::byType('RaspBEE') as $eqLogic){
			
		if ($eqLogic->getConfiguration('origid')==$device[origid] && $eqLogic->getConfiguration('type')==$device[type]){
			error_log("|eqlogic create device deja existant|".$device[type]."|",3,"/tmp/prob.txt");
			$response->state="nok";
			$response->error=1;
			$response->message="Equipement deja existant : <strong>".$eqLogic->name."</strong>";
			return $response;
		// return array("state"=> "nok", "message" => "Equipement deja existant : <strong>".$eqLogic->name."</strong>");
		}
		
		}
		//error_log("|eqlogic create device NON existant|".$device[type]."|",3,"/tmp/prob.txt");
		switch ($device[type]){
		case "ZHASwitch" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHASwitch.json',$device,$syncType);
				break;
			}
		case "ZHATemperature" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHATemperature.json',$device,$syncType);
				break;
			}
		case "ZHAHumidity" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHAHumidity.json',$device,$syncType);
				break;
			}
		case "ZHAPressure" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHAPressure.json',$device,$syncType);
				break;
			}
		case "Color light" :{
				return eqLogicOperate::createColorLight($device,$syncType);
				break;
		}
		case "Extended color light" :{
				return eqLogicOperate::createExtendedColorLight($device,$syncType);
				break;
			}
		case "Dimmable light" :{
				return eqLogicOperate::createDimmableLight($device,$syncType);
				break;
			}
		case "LightGroup" :{
				return eqLogicOperate::createLightGroup($device,$syncType);
				break;
			}
		default : {
				$response->state="nok";
				$response->error=1;
				$response->message="Equipement inconnu";
				return $response;
			}
		}
		//return true;
	}
	
	public function createDimmableLight($device,$syncType){
		if (!is_file(dirname(__FILE__) . '/../config/devices/DimmableLight.json')) return false;
	
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/DimmableLight.json');
		if (!is_json($configFile)) {
			return false;
		}
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un DIMMABLE LIGHT
		$eqLogic->setConfiguration('origid', $device[origid]);
		$eqLogic->setConfiguration('hascolor', $device[hascolor]);
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		$eqLogic->setConfiguration('reachable', $device[state][reachable]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);
		$eqLogic->setConfiguration('colormode', $device[state][colormode]);
		$eqLogic->setIsVisible(1);
		$eqLogic->save();
		return self::setGenericCmdList("DimmableLight.json",$eqLogic,$syncType);
	}
	
	public function createLightGroup($device,$syncType){
		//error_log("|createLightGroup syncType| ".$syncType,3,"/tmp/prob.txt");

		if (!is_file(dirname(__FILE__) . '/../config/devices/Group.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/Group.json');
		if (!is_json($configFile)) {
					error_log("Fichier json invalide",3,"/tmp/rasbee.err");

			return false;
		}
		//error_log("|group create2|".json_encode($device),3,"/tmp/rasbee.err");
		//$test = json_decode($device);
		error_log("|group create2|".$device[type],3,"/tmp/rasbee.err");
		// on traite le type de synchro
		/*switch ($syncType){
			case "limited" :
			break;
			case "basic" :
			break;
			case "renew" :
			break;
			case "renewbutidandname"
			break;
		}*/
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un LIGHT
		$eqLogic->setConfiguration('origid', $device[origid]);
		$eqLogic->setConfiguration('lights', json_encode($device[lights]));		$eqLogic->setConfiguration('devicemembership', json_encode($device[devicemembership]));
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setIsVisible(1);
		$eqLogic->save();
		return self::setGenericCmdList("Group.json",$eqLogic,$syncType);
	}
	
	
	public function createColorLight($device,$syncType){
		if (!is_file(dirname(__FILE__) . '/../config/devices/ColorLight.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ColorLight.json');
		if (!is_json($configFile)) {
			return false;
		}
		//error_log("createLight ".$device[origID],0);
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un LIGHT
		$eqLogic->setConfiguration('origid', $device[origid]);
		$eqLogic->setConfiguration('hascolor', $device[hascolor]);
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		$eqLogic->setConfiguration('reachable', $device[state][reachable]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);
		$eqLogic->setConfiguration('colormode', $device[state][colormode]);
		$eqLogic->setIsVisible(1);
		$eqLogic->save();			
		return self::setGenericCmdList("ColorLight.json",$eqLogic,$syncType);
	}	
	
	public function createExtendedColorLight($device,$syncType){
		if (!is_file(dirname(__FILE__) . '/../config/devices/ExtendedColorLight.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ExtendedColorLight.json');
		if (!is_json($configFile)) {
			return false;
		}
		//error_log("createLight ".$device[origID],0);
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un LIGHT
		$eqLogic->setConfiguration('origid', $device[origid]);
		$eqLogic->setConfiguration('hascolor', $device[hascolor]);
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		$eqLogic->setConfiguration('reachable', $device[state][reachable]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);
		$eqLogic->setConfiguration('colormode', $device[state][colormode]);
		$eqLogic->setIsVisible(1);
		$eqLogic->save();			
		return self::setGenericCmdList("ExtendedColorLight.json",$eqLogic,$syncType);
	}
	
	public function createGenericDevice($path,$device,$syncType){
		if (!is_file(dirname(__FILE__) . $path)){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . $path);
		if (!is_json($configFile)) {
			return false;
		}
		$eqLogic = self::setGenericEqLogic($device,$syncType);
		return self::setGenericCmdList(basename($path),$eqLogic,$syncType);
	}
		
	function setGenericEqLogic($device,$syncType){
		error_log("synctype: ".$syncType,3,"/tmp/prob.text");
		$syncType=0;
		switch ($syncType){
			case 0:
				$eqLogic = new eqLogic();
				$eqLogic->setEqType_name('RaspBEE');
				$eqLogic->setName($device[name]);
				//$eqLogic->setName($device[name]." ".$device[origid]);
				$eqLogic->setIsEnable(1);
				$eqLogic->setIsVisible(1);
				$_logical_id = null;
				$eqLogic->setLogicalId($_logical_id);
				$eqLogic->setConfiguration('origid', $device[origid]);
				$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
				$eqLogic->setConfiguration('modelid', $device[modelid]);
				$eqLogic->setConfiguration('reachable', $device[config][reachable]);
				$eqLogic->setConfiguration('swversion', $device[swversion]);
				$eqLogic->setConfiguration('type', $device[type]);
				$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);
				$eqLogic->batteryStatus($device[config][battery]);
				$eqLogic->save();
			break;
			case 1:
			break;
			case 2:
			break;
			
		}		
		return $eqLogic;		
	}
	
	function setGenericCmdList($file=null,$eqLogic=null,$syncType=0){
		
		if ($file == null ||$eqLogic==null) return false;
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/'.$file);
		if (!is_json($configFile)) return false;
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		$response = $responseHelper;
		$cmdSyncCount = 0;
		switch ($syncType){			
			case 0:
				foreach ($model['commands'] as $command) {
					$RaspBEECmd = new RaspBEECmd();
					$RaspBEECmd->setName($command[name]);
					$RaspBEECmd->setLogicalId($command[name]);
					$RaspBEECmd->setEqLogic_id($eqLogic->getId());
					//error_log("|isvisible|".$command[isVisible],3,"/tmp/prob.txt");
					if (array_key_exists('isVisible', $command)) {
						$RaspBEECmd->setIsVisible($command[isVisible]);
					}
					if (array_key_exists('isHistorized', $command)) {
						$RaspBEECmd->setIsHistorized($command[isHistorized]);
					}
					if (array_key_exists('display', $command)) {
						$RaspBEECmd->setDisplay('generic_type',$command[display][generic_type]);
					}			

					if (array_key_exists('template', $command)) {
						if (array_key_exists('dashboard',$command[template])){
							$RaspBEECmd->setTemplate('dashboard',$command[template][dashboard]);
						}
						//if ($command[template][mobile])
						if (array_key_exists('mobile',$command[template])){
							$RaspBEECmd->setTemplate('mobile',$command[template][mobile]);
						}
					}						

					if (array_key_exists('unite', $command)) {
						$RaspBEECmd->setUnite($command[unite]);
					}	
					if (array_key_exists('type', $command)) {
						 $RaspBEECmd->setType($command[type]);
					}				
					if (array_key_exists('subtype', $command)) {
						 $RaspBEECmd->setSubType($command[subtype]);
					}						
					foreach ($command[configuration] as $command => $key){
						$RaspBEECmd->setConfiguration($command,$key);				
					}				
					$RaspBEECmd->save();
					$cmdSyncCount++;
				}
			break;
		}
		
		

		//error_log("|setGenericCmdList|",3,"/tmp/rasbee.err");

		$response->state="ok";
		$response->error=0;
		$response->message="Commandes ajoutées : <strong>".$cmdSyncCount."</strong>";
		return $response;		
	}
}

?>