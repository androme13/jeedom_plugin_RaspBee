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
	public function getAllEqLogics(){
		$returnArray=array();			
			foreach(eqLogic::byType('RaspBEE') as $eqLogic)
			{
				$return=null;
				$return->id=$eqLogic->getId();
				$return->logicalId=$eqLogic->getLogicalId();
				$return->isEnabled=$eqLogic->getIsEnable();
				$return->type=$eqLogic->getConfiguration('type');
				$return->name=$eqLogic->getName();//->getHumanName(true,true);
				$return->humanName=$eqLogic->getHumanName(true,true);
				$return->origId=$eqLogic->getConfiguration('origid');
				array_push($returnArray,$return);			
			}
			return $returnArray;			
	}
	
	
	public function getById($data){
		$eqLogic=eqLogic::byId($data[id]);
		$return=null;
		$return->id=$eqLogic->getId();
		$return->logicalId=$eqLogic->getLogicalId();
		$return->isEnabled=$eqLogic->getIsEnable();
		$return->type=$eqLogic->getConfiguration('type');
		$return->name=$eqLogic->getName();//->getHumanName(true,true);
		$return->humanName=$eqLogic->getHumanName(true,true);
		$return->origId=$eqLogic->getConfiguration('origid');
		$return->lights=$eqLogic->getConfiguration('lights');
		return $return;
	}
	
	// recupere les groupes d'un equipement par son id
	// return array(humanName)
	public function getOwnersGroups($data){	
		$groups = array();
		foreach (eqLogic::byType('RaspBEE') as $equipement) {				
			$isGroup = stristr($equipement->getConfiguration('type'), "LightGroup");
			if ($isGroup){					
				$obj = json_decode($equipement->configuration);
				$lights = json_decode($obj->lights);
				foreach ($lights as $light){
					if ($light===$data[origId]){
						array_push($groups,$equipement->getId());
						break;
					}
				}
			}
		}
		return $groups;		
	}

	// recupere un humaname par son id
	// return humanName
	public function humanNameById($data){
		$humanName="";
		foreach (eqLogic::byType('RaspBEE') as $equipement) {			
			if ($equipement->getId()===$data[id]) {
				$id = $equipement->id;
				$origid = $equipement->getConfiguration('origid');
				$humanName = $equipement->getHumanName(true,true);
				return array('id' => $id, 'origid' => $origid, 'humanName' => $humanName);
			}				
		}		
	}
	
	// recupere un humaname et un id par l'originid et le type (ex : switch ou light)
	// return array(id,origid,humanName)
	public function humanNameByOrigIdAndType($data){	
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
				error_log("name: ".$equipement->getName()."|",3,"/tmp/prob.txt");
				error_log("name: ".$equipement->getConfiguration('type')."|",3,"/tmp/prob.txt");
				if ($equipement->getConfiguration('origid')==$data[origId] && strstr(strtolower($equipement->getConfiguration('type')), strtolower($data[type]))!==false) {
					$id = $equipement->id;
					$origid = $equipement->getConfiguration('origid');
					$humanName = $equipement->getHumanName(true,true);
					return array('id' => $id, 'origid' => $origid, 'humanName' => $humanName);
				}
		}			
		return array('id' => -1, 'origid' => -1, 'humanName' => "none");	
	}
		
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'RaspBEE_dep';
		$websocket = realpath(dirname(__FILE__) . '/../../daemon/node_modules/websocket');
		$return['progress_file'] = jeedom::getTmpFolder('RaspBEE') . '/dependance';
		if (is_dir($websocket)) {
		  $return['state'] = 'ok';
		} else {
		  $return['state'] = 'nok';
		}
		return $return;
	}

	public static function dependancy_install() {
		log::remove(__CLASS__ . '_dep');
		return array('script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . jeedom::getTmpFolder('RaspBEE') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_dep'));
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
		$raspbeeCom = new RaspBEECom;		
		$wsconfig = json_decode($raspbeeCom->getConf()->message);
		$cmd = 'nice -n 19 nodejs ' . $daemon_path . '/daemon.js ' .'apikey='.$japikey . ' jurl='.$jurl . ' rurl='.$rurl[0]. ' wsp='.$wsconfig->websocketport;		
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
		
	}

	public function postInsert() {
		
	}

	public function preSave() {
		$eqLogic=$this;
		//$isError =0;
		// on traite les sensors
		if (strpos($this->getConfiguration("type"), "ZHA")!==false){
			//error_log("maj sensor : "."|\n",3,"/tmp/prob.txt");
			$raspbeecom = new RaspBEECom;
			$attr='{';
			$attr.='"name":"'.$this->getName().'"';
			$attr.='}';
			$result = $raspbeecom->setSensorAttributes($this->getConfiguration("origid"),$attr);
			unset($raspbeecom);
			if($result->state!=="ok"){
				error_log("error sensor : ".json_encode($result)."|\n",3,"/tmp/prob.txt");
			}
		}

		
		// on traite les groupes
		if ($this->getConfiguration("type")=== "LightGroup") {
		//	$groupId = $this->getId();
			$groupOrigid = $this->getConfiguration("origid");
			if (json_decode($this->getConfiguration("lights"))!==null)
			{
				$groupsJSON = $this->getConfiguration("lights");
				error_log("presave group recuperation lights:\n",3,"/tmp/prob.txt");
			}
			else
			{
				$groupsJSON = "[]";
				error_log("presave group creation tableau vide lights:\n",3,"/tmp/prob.txt");
			}
			$raspbeecom = new RaspBEECom;
			//$hidden = 
			$attr='{';
			$attr.='"name":"'.$this->getName().'",';
			//$attr.='"hidden":'.($this->getIsEnable() ? 'false' : 'true').',';
			$attr.='"lights":'.$groupsJSON;
			$attr.='}';
			$result = $raspbeecom->setGroupAttributes($groupOrigid,$attr);
			unset($raspbeecom);
			if($result->state!=="ok"){
				error_log("error group : ".json_encode($result)."|\n",3,"/tmp/prob.txt");
			}
			
		}
		// on traite les eql de type éclairage
		if (strpos($this->getConfiguration("type"), 'light') !== false && $this->getConfiguration("type") !== "LightGroup" && $this->getConfiguration("lights")!==null) {
			$lightId = $this->getId();
			$lightOrigid = $this->getConfiguration("origid");
			if (json_decode($this->getConfiguration("lights"))!==null)
			{
				$groupsJSON = $this->getConfiguration("lights");
				error_log("presave lights recuperation lights:\n",3,"/tmp/prob.txt");
			}
			else
			{
				$groupsJSON = "[]";
				error_log("presave lights creation tableau vide lights:\n",3,"/tmp/prob.txt");
			}
			$actualGroups=json_decode($groupsJSON);
			$allEqlGroups=eqLogic::byType('RaspBEE');
			foreach ($allEqlGroups as $group) {
				$isGroupModified=false;
				if ($group->getConfiguration("type")==="LightGroup"){				
					$groupOrigid=$group->getConfiguration("origid");
					$lightsInGroupJson=$group->getConfiguration("lights");
					if (json_decode($lightsInGroupJson)!==null){
						$lightsInGroup=json_decode($lightsInGroupJson);
					}
					else
					{
						$lightsInGroup=array();
					}
					// on ne garde que les values dans le tableau
					$lightsInGroup=array_values($lightsInGroup);
					
					foreach ($actualGroups as $actualGroupOrigid){
						$needToAdd = false;
						$needToRemove = false;
						$inGroup = false;
						if ($groupOrigid===$actualGroupOrigid){
							if (in_array($lightOrigid, $lightsInGroup))
							{
								$needToAdd = false;
								$inGroup = true;							
							}
							else
							{
								$needToAdd = true;
								$inGroup = false;
							}
							break;
						}						
					}
					if ($needToAdd===true && $inGroup===false){
						//non presente dans le field, besoin d'être ajoutée
						$lightsInGroup[]=$lightOrigid;
							$isGroupModified=true;							
					}
					if (($needToAdd===false && $inGroup===false) || count($actualGroups)<1){			
						$pos=array_search($lightOrigid,$lightsInGroup);
						if ($pos!==false) {
							// non presente dans le field mais présente dans le groupe, à supprimer donc.
							unset($lightsInGroup[$pos]);
							if (count($lightsInGroup)===0){
								$lightsInGroup=null;
								$attrLights='{"lights":[]}';
							}
							else
							{
								$attrLights='{"lights":'.json_encode($lightsInGroup).'}';
							}
							$isGroupModified=true;
						}						
					}
				}
				// on set le group sur deconz
				if ($isGroupModified===true){
					$raspbeecom = new RaspBEECom;
					$attrLights='{"lights":'.json_encode($lightsInGroup).'}';
					$result = $raspbeecom->setGroupAttributes($groupOrigid,$attrLights);
					unset($raspbeecom);
					if($result->state==="ok"){
						$group->setConfiguration("lights",json_encode($lightsInGroup));
						$group->save();
					}
					else
					{
						error_log("error group : ".json_encode($result)."|\n",3,"/tmp/prob.txt");
					}
				}
			}
			//on supprime le champ lights, car il ne sert qu'à gerer les groupes au niveau de l'UI						
			// on set le nom de l'éclairage dans deconz
			$raspbeecom = new RaspBEECom;
			$attr='{';
			$attr.='"name":"'.$this->getName().'"';
			$attr.='}';
			$result = $raspbeecom->setLightAttributes($lightOrigid,$attr);
			unset($raspbeecom);
			if($result->state!=="ok"){
				error_log("error group : ".json_encode($result)."|\n",3,"/tmp/prob.txt");
			}
			$this->setConfiguration("lights",null);
		}		
	}

	public function postSave() {
		//error_log("postSave:",3,"/tmp/prob.txt");
		
	}

	public function preUpdate() {

		
	}

	public function postUpdate() {
		

	}

	public function preRemove() {
		$eqLogic= $this;
		// si c'est un groupe que l'on supprime
		if ($eqLogic->getConfiguration("type")==="LightGroup"){
			$raspbeecom = new RaspBEECom;
			$result = $raspbeecom->groupDelete($eqLogic->getConfiguration("origid"));
			unset($raspbeecom);
		};
	}

	public function postRemove() {
		
	}

	
	/*public function syncEqLogicWithRaspBEE($_logical_id = null, $_exclusion = 0){
		return eqLogicOperate::createEqLogic();
	}*/
		
	public function deleteRaspBEEUser($user){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->deleteRaspBEEUser($user);
		unset($raspbeecom);
		return $result;
	}
	
	public function eqLogicDelete($id){
		//$raspbeecom = new RaspBEECom;
		return eqLogicOperate::deleteEqLogic($id);
		//$result = $raspbeecom->eqLogicDelete($id);
		//unset($raspbeecom);
		//return $result;
	}
	
	public function findRaspBEE(){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->findRaspBEE();
		unset($raspbeecom);
		return $result;
	}
	
	public function groupCreate($name){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->groupCreate($name);
		unset($raspbeecom);
		return $result;
	}
	
	public function groupDelete($id){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->groupDelete($id);
		unset($raspbeecom);
		return $result;
	}
	
	public function getApiKey(){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getAPIAccess();
		unset($raspbeecom);
		return $result;
	}
	
	public function getGroupsMembers($id){
		$eql=$eqLogic->getById($id);
		$result = array("error" => 0, "message" => "", "state" => "");
		$result->state="ok";
		$result->message=$eql->getConfiguration('lights');
		return $result;
	}
	
	public function setGroupsMembers($id,$members){
		$eql = eqLogic::byId($id);		
		$eql->setConfiguration('lights',$members);
		$eql->save();
		$eql->refresh();		
		$result = array("error" => 0, "message" => "", "state" => "");
		$result->state="ok";
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
	
	public function getTouchlink(){
		//error_log("getRaspBEESensors pass");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getTouchlink();
		unset($raspbeecom);
		return $result;
	}
	
	public function getTouchlinkIdentify($id){
		//error_log("getRaspBEESensors pass");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getTouchlinkIdentify($id);
		unset($raspbeecom);
		return $result;
	}
	
	public function getTouchlinkRefresh(){
		//error_log("getRaspBEESensors pass");
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->getTouchlinkRefresh();
		unset($raspbeecom);
		return $result;
	}
	
	public function createEqLogic($device,$syncType){
		//error_log("createEqLogic pass");
		return eqLogicOperate::createEqLogic($device,$syncType);
	}
	
	public function removeAll(){
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			$equipement->remove();
		}
		$result = array("error" => '', "message" => "", "state" => "");
		$result->state="ok";
		//$error='';
		//$response->error=$httpcode;
		$result->message="Tous les équipements supprimés";
		return $result;		
	}
	
	public function removeFromGroup($deviceId,$groupId){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->removeFromGroup($deviceId,$groupId);
		unset($raspbeecom);
		return $result;
	}
	
	public function setDeconzConfig($data){
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->setDeconzConfig($data[config]);
		unset($raspbeecom);
		return $result;
	}
	
	public function setLightsConfig($data){
		//$process = str_replace('\\','',$data[config]);
		//$res = config::save("lightsConfig",$data[config]),"raspbee");
		$result = array("error" => '', "message" => $data[config], "state" => "ok");
		//$result->state="ok";
		//$result->message="Règlages de l'éclairage sauvegardés avec succès ".$data[config];
		return $result;		
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
			
			switch ($this->getConfiguration('fieldname'))
			{
				case "effect":
					$commandtosend='{"effect" : "colorloop"}';
				break;
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
			//error_log("action group".$commandtosend,3,"/tmp/prob.txt");
			switch ($eqLogic->getConfiguration('type')){
				case "Color light":
                                case "Color temperature light":
				case "Extended color light":
				case "Dimmable light":
				self::sendCommand("lights",$this->getEqlogic()->getConfiguration('origid'),$commandtosend);
				break;
				case "LightGroup":
				//error_log("action group".$commandtosend,3,"/tmp/prob.txt");
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
		if ($id===null || $command===null || $type===null)return false;
		$raspbeecom = new RaspBEECom;
		$result = $raspbeecom->sendCommand($type,$id,$command);
		unset($raspbeecom);
		return $result;		
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
