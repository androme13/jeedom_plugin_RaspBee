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

/* * ***************************Includes********************************* */

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../class/RaspBEECom.class.php';
require_once dirname(__FILE__) . '/eqLogicOperate.class.php';
require_once dirname(__FILE__) . '/colorHelper.class.php';

class RaspBEE extends eqLogic {
	//private $raspbeecom = null; // attention les variables déclarées ici s'enregistrent dans la base sql lors du save
	
	public static function RaspBEECall($_url) {
	
	}
	
	
	public static function test(){
		return "test ok";
	}
		
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'RaspBEE_dep';
		$websocket = realpath(dirname(__FILE__) . '/../../daemon/node_modules/websocket');
		$return['progress_file'] = '/tmp/RaspBEE_dep';
		if (is_dir($websocket)) {
		  $return['state'] = 'ok';
		} else {
		  $return['state'] = 'nok';
		}
		return $return;
	}
	
	public static function dependancy_install() {
		log::add('RaspBEE','info','Installation des dépendances nodejs');
		$resource_path = realpath(dirname(__FILE__) . '/../../resources');
		$daemon_path = realpath(dirname(__FILE__) . '/../../daemon');
		passthru('/bin/bash ' . $resource_path . '/deps.sh ' . $daemon_path . ' > ' . log::getPathToLog('RaspBEE_dep') . ' 2>&1 &');
	}
	
	public static function deamon_info() {	
		$return = array();
		$return['log'] = 'RaspBEE_node';	
		$return['state'] = 'nok';
		$pid_file = '/tmp/raspbee.pid';
		if (file_exists($pid_file)) {
			if (posix_getsid(trim(file_get_contents($pid_file)))) {
				$return['state'] = 'ok';
			} else {
				shell_exec('sudo rm -rf ' . $pid_file . ' 2>&1 > /dev/null;rm -rf ' . $pid_file . ' 2>&1 > /dev/null;');
			}
		}
		$return['launchable'] = 'ok';
		$ip = config::byKey('raspbeeIP','RaspBEE');
		$apikey = config::byKey('raspbeeAPIKEY','RaspBEE');
		if ($ip == '') {
			$return['launchable'] = 'nok';
			$return['launchable_message'] = __('<br><br>L\'IP de la passerelle RaspBEE n\'est pas configurée', __FILE__);
			return $return;
			}
		if ($apikey == '') {
			$return['launchable'] = 'nok';
			$return['launchable_message'] = __('<br><br>La clé API de la passerelle RaspBEE n\'est pas configurée', __FILE__);
			}			
		return $return;
	}
	
	public static function deamon_start($_debug = false) {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$daemon_path = realpath(dirname(__FILE__) . '/../../daemon');
		$jurl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]"."/plugins/RaspBEE/core/php/jeeRaspBEE.php";
		$rurlRAW=config::byKey('raspbeeIP','RaspBEE');
		$rurl = explode(":",config::byKey('raspbeeIP','RaspBEE'));
		$japikey = jeedom::getApiKey('RaspBEE');
		$cmd = 'nice -n 19 nodejs ' . $daemon_path . '/daemon.js ' .'apikey='.$japikey . ' jurl='.$jurl . ' rurl='.$rurl[0];
		log::add('RaspBEE', 'info', 'Lancement du démon RAspBEE : ' . $cmd);
		exec('nohup ' . $cmd . ' >> ' . log::getPathToLog('RaspBEE_node') . ' 2>&1 &');
		$i = 0;
		while ($i < 3) {
			$deamon_info = self::deamon_info();
			if ($deamon_info['state'] == 'ok') {
				break;
			}
			sleep(1);
			$i++;
		}
		if ($i >= 3) {
			log::add('RaspBEE', 'error', 'Impossible de lancer le démon RaspBEE, relancer le démon en debug et vérifiez les log', 'unableStartDeamon');
			return false;
		}
		message::removeAll('RaspBEE', 'unableStartDeamon');
		log::add('RaspBEE', 'info', 'Démon RaspBEE lancé');
	}

	public static function deamon_stop() {			
		$pid_file = '/tmp/raspbee.pid';
		if (file_exists($pid_file)) {
			$pid = intval(trim(file_get_contents($pid_file)));
			system::kill($pid);
		}
		
		
		
		exec('kill $(ps aux | grep "RaspBEE/daemon/daemon.js" | awk \'{print $2}\')');
		log::add('RaspBEE', 'info', 'Arrêt du service RaspBEE');
		$deamon_info = self::deamon_info();
		if ($deamon_info['state'] == 'ok') {
			sleep(1);
			exec('kill -9 $(ps aux | grep "RaspBEE/daemon/daemon.js" | awk \'{print $2}\')');
		}
		
		if ($deamon_info['state'] == 'ok') {
			sleep(1);
			exec('sudo kill -9 $(ps aux | grep "RaspBEE/daemon/daemon.js" | awk \'{print $2}\')');
		}
		sleep(1);
	}

	public static function deamon_changeAutoMode($_mode) {	
	/*$cron = cron::byClassAndFunction('RaspBEE', 'pull');
    if (!is_object($cron)) {
        $cron = new cron();
        $cron->setClass('RaspBEE');
        $cron->setFunction('pull');
        $cron->setEnable(1);
        $cron->setDeamon(1);
        $cron->setSchedule('* * * * *');
        $cron->save();
	}
		$cron = cron::byClassAndFunction('RaspBEE', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();*/
		//config::save('api::raspbee::mode', 'localhost');
	}
	/*     * *************************Attributs****************************** */



	/*     * ***********************Methode static*************************** */

	/*
	* Fonction exécutée automatiquement toutes les minutes par Jeedom
	public static function cron() {

	}
	*/


	/*
	* Fonction exécutée automatiquement toutes les heures par Jeedom
	public static function cronHourly() {

	}
	*/

	/*
	* Fonction exécutée automatiquement tous les jours par Jeedom
	public static function cronDayly() {

	}
	*/



	/*     * *********************Méthodes d'instance************************* */
	
	
	public function preInsert() {
		//this->setConfiguration('value',100);
		
	}

	public function postInsert() {
		
	}

	public function preSave() {
		
	}

	public function postSave() {
		
	}

	public function preUpdate() {
		
	}

	public function postUpdate() {
		
	}

	public function preRemove() {
		
	}

	public function postRemove() {
		
	}

	
	public function syncEqLogicWithRaspBEE($_logical_id = null, $_exclusion = 0){
		return eqLogicOperate::createDevice();
	}
	
	/*public function createGroup($group){
	error_log("|group create raspbee.class|".$group,3,"/tmp/rasbee.err");
	return eqLogicOperate::createGroup($group);
	}*/
	
	public function findRaspBEE(){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->findRaspBEE();
		unset($raspbeecom);
		return $result;
	}
	
	public function getApiKey(){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getAPIAccess();
		unset($raspbeecom);
		return $result;
	}
	
	public function getRaspBEEConf(){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getConf();
		unset($raspbeecom);
		return $result;
	}
	
	public function getRaspBEEGroups(){
		//error_log("|getRaspBEEGroups|".$result,3,"/tmp/rasbee.err");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getGroups();		
		unset($raspbeecom);
		return $result;
	}
	
	public function getRaspBEESensors(){
		//error_log("getRaspBEESensors pass");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getSensors();
		unset($raspbeecom);
		return $result;
	}
	
	public function getRaspBEELights(){
		//error_log("getRaspBEESensors pass");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getLights();
		unset($raspbeecom);
		return $result;
	}
	
	public function createDevice($device){
		//error_log("createDevice pass");
		return eqLogicOperate::createDevice($device);
	}
	
	public function removeAll(){
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			$equipement->remove();
		}
	}
	/*
	* Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
	public function toHtml($_version = 'dashboard') {

	}
	*/

	/*     * **********************Getteur Setteur*************************** */
}

class RaspBEECmd extends cmd {
	/*     * *************************Attributs****************************** */


	/*     * ***********************Methode static*************************** */


	/*     * *********************Methode d'instance************************* */

	/*
	* Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	public function dontRemoveCmd() {
	return true;
	}
	*/

	public function execute($_options = array()) {
		if ($this->getType() == 'action'){
			
			$eqLogic = $this->getEqLogic();
			//error_log("execute action : ".$this->getConfiguration('fieldname')." ".$this->getName(),3,"/tmp/prob.txt");
			//error_log("execute action : ".$eqLogic->getConfiguration('type')." ".$this->getName(),3,"/tmp/prob.txt");
			//error_log("options :".json_encode($_options),3,"/tmp/prob.txt");
			
			switch ($this->getConfiguration('fieldname'))
			{
				case "on":
				if ($this->getName()=='On')
					$commandtosend='{"on" : true}';
				else
					$commandtosend='{"on" : false}';
				break;
				case "color":
				$color = $_options[color];
					$temp = HEX2RGB($color);
					$xy = colorHelper::RGB2XY($temp[0],$temp[1],$temp[2],false);				
					$commandtosend='{"xy" :['.$xy[x].','.$xy[y].']}';
				break;
				default :
					$commandtosend='{"'.$this->getConfiguration('fieldname').'" : '.$_options[slider].'}';
					
				
			}
			switch ($eqLogic->getConfiguration('type')){
				case "Extended color light":
				case "Dimmable light":
				self::sendCommand("lights",$this->getEqlogic()->getConfiguration('origid'),$commandtosend);
				break;
				case "LightGroup":
				self::sendCommand("groups",$this->getEqlogic()->getConfiguration('origid'),$commandtosend);
				break;				
			}
			
			//error_log("commande : ".$commandtosend,3,"/tmp/prob.txt");
			return;
		}
		
		if ($this->getType() == 'info'){
			error_log("execute info",3,"/tmp/prob.txt");
			//error_log(json_encode($_options),3,"/tmp/prob.txt");
			return;
		}	
	}

	
	/**
	 * #rrggbb or #rgb to [r, g, b]
	 */
	function HEX2RGB(string $hex)
	{
		return colorHelper::HEX2RGB($hex);
	}

	
	public static function convert(){
	error_log("convert :",3,"/tmp/prob.txt");	
	}
	
	
	private function sendCommand($type=null,$id=null,$command=null){
		//error_log("sendCommand",3,"/tmp/prob.txt");

		if ($id===null || $command===null || $type===null)return false;
		//error_log("getRaspBEESensors pass");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->sendCommand($type,$id,$command);
		unset($raspbeecom);
		//error_log("error :".$result,3,"/tmp/prob.txt");
				//error_log("commande :".$command,3,"/tmp/prob.txt");

		return $result;
		
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
