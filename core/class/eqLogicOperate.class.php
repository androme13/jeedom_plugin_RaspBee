<?php
/* This file is part of Plugin RaspBEE for jeedom.
*
* Plugin openzwave for jeedom is free software: you can redistribute it and/or modify
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
	
	public function createDevice($device,$syncType = 0){
		foreach (eqlogic::byType('RaspBEE') as $eqLogic){
		if ($eqLogic->getConfiguration('origid')==$device[origid] && ($eqLogic->getConfiguration('type')==$device[type])) return false;		
		}
		switch ($device[type]){
		case "ZHASwitch" :{
				eqLogicOperate::createZHASwitch($device);
				break;
			}
		case "ZHATemperature" :{
				eqLogicOperate::createZHATemperature($device);
				break;
			}
		case "ZHAHumidity" :{
				eqLogicOperate::createZHAHumidity($device);
				break;
			}
		case "ZHAPressure" :{
				eqLogicOperate::createZHAPressure($device);
				break;
			}
		case "Extended color light" :{
				eqLogicOperate::createLight($device);
				break;
			}
		default : {
			error_log("eqLogicOperate : devicetype inconnu");
			}
		}
		return true;
	}
	
	private function isDeviceExist($device){
		
	}
	
	
	public function createLight($device){
		if (!is_file(dirname(__FILE__) . '/../config/devices/lights.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/lights.json');
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
		
		/*foreach ($device[] as $actioncmd => $key){
			
		}*/
		
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
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[logicalId]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			$RaspBEECmd->setValue($command[value]);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;
	}
	
	
	
	public function createZHASwitch($device){
		if (!is_file(dirname(__FILE__) . '/../config/devices/ZHASwitch.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ZHASwitch.json');
		if (!is_json($configFile)) {
			return false;
		}
		//error_log("createZHATemperature ".$device[origID]);
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$eqLogic->setIsVisible(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un sensor ZHASwitch
		$eqLogic->setConfiguration('origid', $device[origid]);
		//$eqLogic->setConfiguration('etag', "e6797100e644d32ac0019ea2a8336bcd");
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		//$eqLogic->setConfiguration('mode', $device[mode]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('reachable', $device[config][reachable]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);

		$eqLogic->batteryStatus($device[config][battery]);
		$eqLogic->save();

		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[name]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			//$RaspBEECmd->setValue($device[state][buttonevent]);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;
	}
	
	public function createZHATemperature($device){
		if (!is_file(dirname(__FILE__) . '/../config/devices/ZHATemperature.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ZHATemperature.json');
		if (!is_json($configFile)) {
			return false;
		}
		//error_log("createZHATemperature ".$device[origID]);
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$eqLogic->setIsVisible(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un sensor ZHATemperature
		$eqLogic->setConfiguration('origid', $device[origid]);
		//$eqLogic->setConfiguration('etag', "e6797100e644d32ac0019ea2a8336bcd");
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		//$eqLogic->setConfiguration('mode', $device[mode]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('reachable', $device[config][reachable]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);

		$eqLogic->batteryStatus($device[config][battery]);
		$eqLogic->save();

		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[name]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			//$RaspBEECmd->setValue($device[state][temperature]/100);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;	
	}
	
	public function createZHAHumidity($device){		
		if (!is_file(dirname(__FILE__) . '/../config/devices/ZHAHumidity.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ZHAHumidity.json');
		if (!is_json($configFile)) {
			return false;
		}
		//error_log("createZHATemperature ".$device[origID]);
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$eqLogic->setIsVisible(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un sensor ZHAHumidity
		$eqLogic->setConfiguration('origid', $device[origid]);
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('reachable', $device[config][reachable]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);

		$eqLogic->batteryStatus($device[config][battery]);
		$eqLogic->save();
		
		
		
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[name]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			//$RaspBEECmd->setValue($device[state][humidity]/100);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;	
	}
	
	public function createZHAPressure($device){		
		if (!is_file(dirname(__FILE__) . '/../config/devices/ZHAPressure.json')){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/ZHAPressure.json');
		if (!is_json($configFile)) {
			return false;
		}
		//error_log("createZHATemperature ".$device[origID]);
		$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$eqLogic->setIsVisible(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		// on fabrique un sensor ZHAHumidity
		$eqLogic->setConfiguration('origid', $device[origid]);
		$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
		$eqLogic->setConfiguration('modelid', $device[modelid]);
		$eqLogic->setConfiguration('reachable', $device[config][reachable]);
		$eqLogic->setConfiguration('swversion', $device[swversion]);
		$eqLogic->setConfiguration('type', $device[type]);
		$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);

		$eqLogic->batteryStatus($device[config][battery]);
		$eqLogic->save();
		
		
		
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[name]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			//$RaspBEECmd->setValue($device[state][humidity]/100);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;	
	}
}

?>