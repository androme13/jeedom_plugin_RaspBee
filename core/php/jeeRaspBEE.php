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

// c'est ici que le daemon transmet ses infos

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'), 'RaspBEE')) {
	echo __('Vous n\'etes pas autorisé à effectuer cette action', __FILE__);	
	die();
}
$results = json_decode(file_get_contents("php://input"));
print_r($results);

if (!is_object($results)) {
	die();
}

if ($results->type == "sensors"){	
	// on traite l'info batterie d'un device
	if (is_object($results->info->battery))
	{		
		foreach (eqLogic::byType('RaspBEE') as $equipement) {		
			foreach ($equipement->getCmd('info') as $cmd){
				// on set le niveau de batterie de l'eqlogic
				if ($equipement->getConfiguration('origid')==$results->id)
				$equipement->batteryStatus($results->info->battery);
			}			
		}
	}else
	if (is_object($results->action)){
	// on traite l'info d'un device	
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			if ($equipement->getConfiguration('origid')==$results->id)			
			foreach ($equipement->getCmd('info') as $cmd){
				foreach ($results->action as $actioncmd => $key){
					if ($cmd->getConfiguration('fieldname')==$actioncmd){
						if ($cmd->getConfiguration('fieldname')=="temperature" || $cmd->getConfiguration('fieldname')=="humidity")
							$cmd->event($key/100);
						else
							$cmd->event($key);							
					}
				}
			}			
		}
	}
}

if($results->type == "lights"){
	// on traite l'info d'un device
	foreach (eqLogic::byType('RaspBEE') as $equipement) {
		if ($equipement->getConfiguration('origid')==$results->id)			
		foreach ($equipement->getCmd('info') as $cmd){
			foreach ($results->action as $actioncmd => $key){
				if ($cmd->getConfiguration('fieldname')==$actioncmd){
					$cmd->event($key);
					foreach ($equipement->getCmd('action') as $cmd2){
						foreach ($results->action as $actioncmd2 => $key2){
							if ($cmd2->getConfiguration('fieldname')==$actioncmd2){
								if ($cmd2->getConfiguration('lastCmdValue')!=$key){
								$cmd2->setConfiguration('lastCmdValue',$key);
								$cmd2->save();
								$cmd2->getEqLogic()->refreshWidget();
								}								
							}
						}
					}						
				}
			}
		}		
	}
}

if($results->type == "groups"){
	error_log("gestion groupes",3,"/tmp/prob.txt")
	// on traite l'info d'un device
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			if ($equipement->getConfiguration('origid')==$results->id)			
			foreach ($equipement->getCmd('info') as $cmd){
				foreach ($results->action as $actioncmd => $key){	
				error_log($result->action,3,"/tmp/prob.txt")
					if ($cmd->getConfiguration('fieldname')==$actioncmd){
							
							//$cmd->event($key);							
					}
				}
			}			
		}
}



else
echo json_encode($results->params);


?>