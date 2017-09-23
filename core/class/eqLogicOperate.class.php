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
	
	public function createDevice($device,$syncType = 0){
		error_log("|eqlogic create device|".$device[type]."|",3,"/tmp/rasbee.err");

		foreach (eqlogic::byType('RaspBEE') as $eqLogic){
		if ($eqLogic->getConfiguration('origid')==$device[origid] && $eqLogic->getConfiguration('type')==$device[type]) return false;		
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
				eqLogicOperate::createExtendedColorLight($device);
				break;
			}
		case "Dimmable light" :{
				eqLogicOperate::createDimmableLight($device);
				break;
			}
		case "LightGroup" :{
				eqLogicOperate::createLightGroup($device);
				break;
			}
		default : {
			error_log("eqLogicOperate : devicetype inconnu");
			}
		}
		return true;
	}
	
	public function createDimmableLight($device){
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
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[logicalId]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			$RaspBEECmd->setValue($command[value]);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
			$RaspBEECmd->setIsVisible($command[isVisible]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;
	}
	
	public function createLightGroup($device){
		error_log("|eqlogic create2|",3,"/tmp/rasbee.err");

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
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[logicalId]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			$RaspBEECmd->setValue($command[value]);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
			$RaspBEECmd->setIsVisible($command[isVisible]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		error_log("|fin de eqlogic group create2|",3,"/tmp/rasbee.err");
		return true;
	}
	
	public function createExtendedColorLight($device){
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
		$model = json_decode($configFile, true);
		$commands = $model['commands'];
		foreach ($model['commands'] as $command) {
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[logicalId]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
			$RaspBEECmd->setValue($command[value]);
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
			$RaspBEECmd->setIsVisible($command[isVisible]);
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
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
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
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
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
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
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
			$RaspBEECmd->setConfiguration("fieldname",$command[configuration][fieldname]);
			$RaspBEECmd->setConfiguration("minValue",$command[configuration][minValue]);
			$RaspBEECmd->setConfiguration("maxValue",$command[configuration][maxValue]);
			$RaspBEECmd->setType($command[type]);
			$RaspBEECmd->setSubType($command[subtype]);
			$RaspBEECmd->save();						
		}
		return true;	
	}
}

?>