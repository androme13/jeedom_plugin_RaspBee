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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class RaspBEE extends eqLogic {
	
	//private $daemonpid=0;
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'RaspBEE_dep';
		$return['progress_file'] = '/tmp/raspbee_dep';
		$backresp = array();
		$backcode = -1;
		exec("node -v",$backresp,$backcode);		
		if ($backcode==0)
			$return['state'] = 'ok';
		else
			$return['state'] = 'nok';
		return $return;
	}
	
	public static function dependancy_install() {
		log::add('RaspBEE','info','Installation des dépendances nodejs');
		//$resource_path = realpath(dirname(__FILE__) . '/../../resources');
		//passthru('/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' > ' . //log::getPathToLog('JeeOrangeTv_dep') . ' 2>&1 &');
	}
	
	public static function deamon_info() {	
		$return = array();
		$return['log'] = 'RaspBEE_node';	
		$pidfile = file_get_contents('/tmp/raspbee.pid', true);
		$cfgfile = file_get_contents('plugins/RaspBEE/daemon/raspbee.cfg', true);
		if ($cfgfile==false){
			//		if (strlen($cfgfile)==0){
			$return['launchable'] = 'ok';
		} else {
			$return['launchable'] = 'nok';
		}
		
		if ($pidfile==false){
			//if (strlen($pidfile)==0){
			$return['state'] = 'nok';	
		}else{	
			$return['state'] = 'ok';	
		}
		$key = config::byKey('raspbeeIP','RaspBEE');
		//echo "valeur clé:".$key;
		if ( $key == '') {
			
			$return['launchable'] = 'nok';
		//config::save('api::raspbee::mode', 'localhost');
	}    
		//
		return $return;
	}
	
	public static function deamon_start($_debug = false) {
		$log = log::convertLogLevel(log::getLogLevel('RaspBEE'));
		log::add('RaspBEE', 'info', 'test : ');

		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$daemon_path = realpath(dirname(__FILE__) . '/../../daemon');
		$cmd = 'nice -n 19 nodejs ' . $daemon_path . '/daemon.js ';
		//$cmd = 'nice -n 19 nodejs ' . $sensor_path . '/jeeorangetv.js ' . $url . ' ' . $log . ' ' . $freq;
		log::add('RaspBEE', 'debug', 'Lancement démon RaspBEE : ' . $cmd);
		$result = exec('nohup ' . $cmd . ' >> ' . log::getPathToLog('RaspBEE_node') . ' 2>&1 &');
		if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {

			log::add('RaspBEE', 'error', 'Impossible de lancer le démon RaspBEE : '.$result, 'unableStartDeamon');
			return false;
		}
		message::removeAll('RaspBEE', 'unableStartDeamon');
		log::add('RaspBEE', 'info', 'Démon RaspBEE lancé');
		return true;		
	}

	public static function deamon_stop() {
		
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
		
	}

	public static function deamon_changeAutoMode($_mode) {
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
		
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
