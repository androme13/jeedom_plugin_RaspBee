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
	 
	  public function createDevice($device){
		  //error_log("createDevice ".$device[origID]);
		  //error_log("createDevice ".$device[type]);
		  switch ($device[type]){
					case "ZHASwitch" :{
						//addZHASwitch($sensor);
						eqLogicOperate::createZHASwitch($device);
						break;
					}
				}
		  return true;
	  }
	 
	 
	 
	 
	 public function createZHASwitch($device){
		error_log("createZHASwitch ".$device[origID]);
		$eqLogic = new eqLogic();
			$eqLogic->setEqType_name('RaspBEE');
			$eqLogic->setName($device[name]." ".$device[origid]);
			$eqLogic->setIsEnable(1);
			$_logical_id = null;
			$eqLogic->setLogicalId($_logical_id);
			// on fabrique un sensor ZHASwitch (avec bouton)
			$eqLogic->setConfiguration('origid', $device[origid]);
			//$eqLogic->setConfiguration('etag', "e6797100e644d32ac0019ea2a8336bcd");
			$eqLogic->setConfiguration('manufacturername', $device[manufacturername]);
			//$eqLogic->setConfiguration('mode', $device[mode]);
			$eqLogic->setConfiguration('modelid', $device[modelid]);
			$eqLogic->setConfiguration('swversion', $device[swversion]);
			$eqLogic->setConfiguration('type', $device[type]);
			$eqLogic->setConfiguration('uniqueid', $device[uniqueid]);
			$eqLogic->setIsVisible(1);
			$eqLogic->batteryStatus(100);
			$eqLogic->save();
		if (!is_object($RaspBEECmd)) {
			$RaspBEECmd = new RaspBEECmd();
        }
		
		$RaspBEECmd->setName('buttonevent');
        $RaspBEECmd->setLogicalId('buttonevent');
        $RaspBEECmd->setEqLogic_id($eqLogic->getId());
		$RaspBEECmd->setValue($device[state][buttonevent]);
		$dateInLocal = new DateTime($device[state][lastupdated],new DateTimeZone('UTC'));
				// il faut connaitre le timezone local
		$dateInLocal->setTimeZone(new DateTimeZone('Europe/Paris'));
		//$RaspBEECmd->setValueDate($dateInLocal->format("Y-m-d H:i:s"));				
		//error_log("setValueDate ".$dateInLocal->format("Y-m-d H:i:s"));

        //$RaspBEECmd->setConfiguration('day', '-1');
        //$RaspBEECmd->setConfiguration('data', 'temp');
        //$RaspBEECmd->setUnite('°C');
        $RaspBEECmd->setType('info');
        $RaspBEECmd->setSubType('numeric');
        $RaspBEECmd->save();
		return true;	
			
			//$eqLogic->save();
			//return;

	}
 }
 
 ?>