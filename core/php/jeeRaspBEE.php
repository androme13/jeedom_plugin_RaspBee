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

// c'est ici que le daemon transmet ses infos

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'), 'RaspBEE')) {
	echo __('Vous n\'etes pas autorisé à effectuer cette action', __FILE__);	
	die();
}
//php_error('sensorspass',3,'/tmp/mes-erreurs.log');
$results = json_decode(file_get_contents("php://input"));
print_r($results);

if (!is_object($results)) {
	die();
}

if ($results->type == "sensors"){
	
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
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			if ($equipement->getConfiguration('origid')==$results->id)			
			foreach ($equipement->getCmd('info') as $cmd){
				foreach ($results->action as $actioncmd => $key){
					if ($cmd->getConfiguration('fieldname')==$actioncmd){
						$cmd->setValue($key);
						$cmd->event($key);
						$cmd->save();					
					}
				}
			}
			
		}
	}
}else
	if($results->type == "lights"){
		
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			if ($equipement->getConfiguration('origid')==$results->id)			
			foreach ($equipement->getCmd('info') as $cmd){
				foreach ($results->action as $actioncmd => $key){
					if ($cmd->getConfiguration('fieldname')==$actioncmd){
						$cmd->setValue($key);
						$cmd->event($key);
						$cmd->save();					
					}
				}
			}
			
		}
	}
else
echo json_encode($results->params);


?>