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
	
	private $responseHelper = array("error" => 0, "message" => "", "state" => "");
	
	public function createEqLogic($device,$syncType="basic"){
		//error_log("|eqlogic create device|".json_encode($device[type])."|",3,"/tmp/prob.txt");
		$response = $responseHelper;
		$eqLogicMode = false;
		$eqLogic=null;
		// on check si l'eqLogic existe
		foreach (eqlogic::byType('RaspBEE') as $eqLogicPass){			
			if ($eqLogicPass->getConfiguration('origid')==$device[origid] && $eqLogicPass->getConfiguration('type')==$device[type]){
				/*error_log("|eqlogic create device deja existant|".$device[type]."|",3,"/tmp/prob.txt");
				$response->state="nok";
				$response->error=1;
				$response->message="Equipement deja existant : <strong>".$eqLogic->name."</strong>";
				return $response;*/
				error_log("|eqlogic create device deja existant|".$eqLogicPass->getName()."|",3,"/tmp/prob.txt");
				$eqLogicMode = true;
				$eqLogic = $eqLogicPass;
				break;
			}		
		}
		
		//error_log("|eqlogic create device NON existant|".$device[type]."|",3,"/tmp/prob.txt");
		switch ($device[type]){
		case "ZHASwitch" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHASwitch.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
			}
		case "ZHATemperature" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHATemperature.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
			}
		case "ZHAHumidity" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHAHumidity.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
			}
		case "ZHAPressure" :{
				return eqLogicOperate::createGenericDevice('/../config/devices/ZHAPressure.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
			}
		case "Color light" :{
				return eqLogicOperate::createLight('/../config/devices/ColorLight.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
		}
		case "Extended color light" :{
				return eqLogicOperate::createLight('/../config/devices/ExtendedColorLight.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
			}
		case "Dimmable light" :{
				return eqLogicOperate::createLight('/../config/devices/DimmableLight.json',$eqLogic,$device,$syncType,$eqLogicMode);
				break;
			}
		case "LightGroup" :{
				return eqLogicOperate::createLightGroup('/../config/devices/Group.json',$eqLogic,$device,$syncType,$eqLogicMode);
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
	
	public function createLight($config='',$eqLogic,$device,$syncType="basic",$eqLogicMode){
		if (!is_file(dirname(__FILE__) . $config)){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . $config);
		if (!is_json($configFile)) {
			return false;
		}			
		if ($eqLogicMode ==false && $eqLogic==null){
			$eqLogic = new eqLogic();
			$eqLogic->setIsEnable(1);
			$eqLogic->setIsVisible(1);
			$_logical_id = null;
			$eqLogic->setLogicalId($_logical_id);
			$eqLogic->setEqType_name('RaspBEE');
			$eqLogic->setName($device[name]);
		}
		switch ($syncType){
			case "limited" :
			break;
			case "basic" :			
				$eqLogic = self::setGenericEqLogicConf($eqLogic,$device,$syncType,$eqLogicMode);
				$eqLogic = self::checkAndSetEqLogicConfiguration($eqLogic,'hascolor',$device[hascolor],$syncType);
				$eqLogic = self::checkAndSetEqLogicConfiguration($eqLogic,'colormode',$device[state][colormode],$syncType);
				$eqLogic->save();
			break;
			case "renew" :
			break;
			case "renewbutidandname" :
			break;
		}
		return self::setGenericCmdList(basename($config),$eqLogic,$syncType);			
	}
	
	public function createLightGroup($config='',$eqLogic,$device,$syncType="basic",$eqLogicMode){
		//error_log("|createLightGroup syncType| ".$syncType,3,"/tmp/prob.txt");

		if (!is_file(dirname(__FILE__) . $config)){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/Group.json');
		if (!is_json($configFile)) {
			//error_log("Fichier json invalide",3,"/tmp/rasbee.err");
			return false;
		}
		
		
		if ($eqLogicMode == false && $eqLogic==null){
			$eqLogic = new eqLogic();
			$eqLogic->setIsEnable(1);
			$eqLogic->setIsVisible(1);
			$_logical_id = null;
			$eqLogic->setLogicalId($_logical_id);
			$eqLogic->setEqType_name('RaspBEE');
			$eqLogic->setName($device[name]);
		}
		switch ($syncType){
			case "limited" :
			break;
			case "basic" :			
				$eqLogic = self::setGenericEqLogicConf($eqLogic,$device,$syncType,$eqLogicMode);
				$eqLogic = self::checkAndSetEqLogicConfiguration($eqLogic,'lights',json_encode($device[lights]),$syncType);
				$eqLogic = self::checkAndSetEqLogicConfiguration($eqLogic,'devicemembership',json_encode($device[devicemembership]),$syncType);
				$eqLogic->save();
			break;
			case "renew" :
			break;
			case "renewbutidandname" :
			break;
		}
		return self::setGenericCmdList(basename($config),$eqLogic,$syncType);	
		
		
		
		
		
		
		
		
		
		//error_log("|group create2|".json_encode($device),3,"/tmp/rasbee.err");
		//$test = json_decode($device);
		//error_log("|group create2|".$device[type],3,"/tmp/rasbee.err");
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
		/*$eqLogic = new eqLogic();
		$eqLogic->setEqType_name('RaspBEE');
		$eqLogic->setName($device[name]." ".$device[origid]);
		$eqLogic->setIsEnable(1);
		$eqLogic->setIsVisible(1);
		$_logical_id = null;
		$eqLogic->setLogicalId($_logical_id);
		$eqLogic->setConfiguration('origid', $device[origid]);*/
	//	$eqLogic->setConfiguration('lights', json_encode($device[lights]));
		//$eqLogic->setConfiguration('devicemembership', json_encode($device[devicemembership]));
		//$eqLogic->setConfiguration('type', $device[type]);
		//$eqLogic->save();
		//return self::setGenericCmdList("Group.json",$eqLogic,$syncType);
	}
	
	public function createGenericDevice($path,$eqLogic=null,$device,$syncType="basic",$eqLogicMode){
		if (!is_file(dirname(__FILE__) . $path)){
		return false;
		};
		$configFile = file_get_contents(dirname(__FILE__) . $path);
		if (!is_json($configFile)) {
			return false;
		}
		$eqLogic = self::setGenericEqLogic($eqLogic,$device,$syncType,$eqLogicMode);
		return self::setGenericCmdList(basename($path),$eqLogic,$syncType,$eqLogicMode);
	}
	
	
	private function checkAndSetEqLogicConfiguration($eqLogic=null,$attr,$value,$syncType="basic"){
		
		switch ($syncType){
			case "limited" :
			break;
			case "basic" :
			//error_log("|checkAndSetEqLogicConfiguration attr|".$eqLogic->getConfiguration($attr),3,"/tmp/rasbee.err");
				$eqLogic->setConfiguration($attr, $value);
			break;
			case "renew" :
			break;
			case "renewbutidandname" :
			break;
		}
		return $eqLogic;
	}
	
	function setGenericEqLogicConf($eqLogic=null,$device,$syncType="basic",$eqLogicMode){
		//error_log("synctype: ".$syncType,3,"/tmp/prob.text");
		switch ($syncType){
			case "limited" :
			break;
			case "basic" :
				self::checkAndSetEqLogicConfiguration($eqLogic,'origid',$device[origid],$syncType);
				self::checkAndSetEqLogicConfiguration($eqLogic,'manufacturername',$device[manufacturername],$syncType);
				self::checkAndSetEqLogicConfiguration($eqLogic,'modelid',$device[modelid],$syncType);
				self::checkAndSetEqLogicConfiguration($eqLogic,'reachable',$device[reachable],$syncType);
				self::checkAndSetEqLogicConfiguration($eqLogic,'swversion',$device[swversion],$syncType);
				self::checkAndSetEqLogicConfiguration($eqLogic,'type',$device[type],$syncType);
				self::checkAndSetEqLogicConfiguration($eqLogic,'uniqueid',$device[uniqueid],$syncType);
			break;
			case "renew" :
			break;
			case "renewbutidandname" :
			break;
		}	
		return $eqLogic;		
	}
		
	function setGenericEqLogic($eqLogic=null,$device,$syncType="basic",$eqLogicMode){
		error_log("synctype: ".$syncType,3,"/tmp/prob.text");

		if ($eqLogicMode == false && $eqLogic==null){
			$eqLogic = new eqLogic();
			$eqLogic->setIsEnable(1);
			$eqLogic->setIsVisible(1);
			$_logical_id = null;
			$eqLogic->setLogicalId($_logical_id);
			$eqLogic->setEqType_name('RaspBEE');
			$eqLogic->setName($device[name]);
			$eqLogic->batteryStatus($device[config][battery]);
		}
		switch ($syncType){
			case "limited" :
			break;
			case "basic" :			
				$eqLogic = self::setGenericEqLogicConf($eqLogic,$device,$syncType,$eqLogicMode);
				$eqLogic->save();
			break;
			case "renew" :
			break;
			case "renewbutidandname" :
			break;
		}
		return $eqLogic;		
	}
	
	function setGenericCmd($eqLogic=null,$cmdPass,$command,$syncType="basic",$cmdMode){
		error_log("setGenericCmd synctype: ".$syncType,3,"/tmp/prob.text");

		if ($cmdMode == false && $cmdPass==null){
			$RaspBEECmd = new RaspBEECmd();
			$RaspBEECmd->setName($command[name]);
			$RaspBEECmd->setLogicalId($command[name]);
			$RaspBEECmd->setEqLogic_id($eqLogic->getId());
		} else
			$RaspBEECmd = $cmdPass;
		switch ($syncType){
			case "limited" :
			break;
			case "basic" :			
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
					//$cmdAddCount++;
			break;
			case "renew" :
			break;
			case "renewbutidandname" :
			break;
		}
		return $RaspBEECmd;		
	}
	
	
	
	
	function setGenericCmdList($file=null,$eqLogic=null,$syncType=0,$eqLogicMode){
		
		if ($file == null ||$eqLogic==null) return false;
		$configFile = file_get_contents(dirname(__FILE__) . '/../config/devices/'.$file);
		if (!is_json($configFile)) return false;
		$eqLogicModel = json_decode($configFile, true);
		$commands = $eqLogicModel['commands'];
		
		$response = $responseHelper;
		$cmdAddCount = 0;
		$cmdNotTouchedCount =0;
		$cmdRemoveCount =0;
		
				foreach ($eqLogicModel['commands'] as $command) {
					// on check si la commande existe ou pas
					$cmdMode = false;
					$cmdPass = null;
					if ($eqLogic!=null)
					foreach ($eqLogic->getCmd(null,null,null,true) as $checkCmd) {
						if (($checkCmd->getType() === $command[type]) && ($checkCmd->getConfiguration("fieldname") === $command[configuration][fieldname]) && ($checkCmd->getName() === $command[name])){
						error_log("|commande existante|".$checkCmd->getName(),3,"/tmp/prob.txt");
						$cmdMode = true;
						$cmdPass = $checkCmd;
						break;
						}
	
					}
					switch ($syncType){
						case "limited" :
						break;
						case "basic" :
							// si la commande n'existe pas
							if ($cmdMode===false){
							error_log("|ajout de la commande|".$command[name],3,"/tmp/prob.txt");
							$cmd = self::setGenericCmd($eqLogic,$cmdPass,$command,$syncType,$cmdMode);
							$cmdAddCount++;
							}
							else
							{
							error_log("|commande non ajoutée|".$command[name],3,"/tmp/prob.txt");	
							$cmdNotTouchedCount++;
							}
						break;
						case "renew" :
						break;
						case "renewbutidandname" :
						break;
					}

				}
		$response->state="ok";
		$response->error=0;
		$response->message='{"notTouched":'.$cmdNotTouchedCount.', "added":'.$cmdAddCount.', "removed":'.$cmdRemoveCount.'}';
		return $response;		
	}
	
	function setGenericCommand($eqLogic=null,$device,$syncType="basic",$eqLogicMode){
	}
}

?>