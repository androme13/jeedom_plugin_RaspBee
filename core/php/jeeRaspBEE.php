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
require_once dirname(__FILE__) . "/../class/colorHelper.class.php";


if (!jeedom::apiAccess(init('apikey'), 'RaspBEE')) {
	echo __('Vous n\'etes pas autorisé à effectuer cette action', __FILE__);	
	die();
}
$results = json_decode(file_get_contents("php://input"));


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
						break;
					}
				}
			}			
		}
	}
}



if($results->type == "lights"){
	//error_log("info light à traiter",3,'/tmp/prob.txt');
	// on traite l'info d'un device
	// on parcours tous les équipements RASPBEE
	foreach (eqLogic::byType('RaspBEE') as $equipement) {
		// si l'origiid et result->id correspondent
		if ($equipement->getConfiguration('origid')==$results->id)
		// on parcours les commandes de type info
		foreach ($equipement->getCmd('info') as $cmd){
			// on parcours les resultats->action
			foreach ($results->action as $actioncmd => $key){
				// si la clé correspond au fieldname (bri, sat etc ..)
				if ($cmd->getConfiguration('fieldname')==$actioncmd){
					// on affecte la valeur à la commande
					$cmd->event($key);
					// on parcours les commandes de type action
					foreach ($equipement->getCmd('action') as $cmd2){
						// on parcours les resultats->action
						foreach ($results->action as $actioncmd2 => $key2){
							// si la clé correspond au fieldname (bri, sat etc ..)
							if ($cmd2->getConfiguration('fieldname')==$actioncmd2){
								error_log("|INFO ".$actioncmd.'('.$key.') => ACTION '.$actioncmd2.":".$key2."|",3,'/tmp/prob.txt');
								// si la valeur est differente de la valeur stockée
								if ($cmd2->getConfiguration('lastCmdValue')!=$key){
									$cmd2->setConfiguration('lastCmdValue',$key);
									$cmd2->save();
									
									// on traite le changement de couleur du widget
									// on recuperes aussi toutes les valeurs hue sat et bri (hsl) afin d'envoyer un hexrgb au widget;
									if ($actioncmd=='hue' || $actioncmd=='sat' || $actioncmd=='bri'){
										error_log("changement couleur recquis",3,'/tmp/prob.txt');
										$hue=0;
										$sat=0;
										$bri=0;
										foreach ($equipement->getCmd('action') as $colorcpnt){
											switch ($colorcpnt->getConfiguration('fieldname')){
											case "hue":
												$hue = $colorcpnt->getConfiguration('lastCmdValue');
												break;
											case "sat":
												$sat = $colorcpnt->getConfiguration('lastCmdValue');
												break;
											case "bri":
												$bri = $colorcpnt->getConfiguration('lastCmdValue');
												break;
											}
											$finalHue = (360*((100/65535)*$hue))/100;
											$finalSat = (100/255)*$sat;
											$finalBri = (100/255)*$bri;
											
											$rvb = colorHelper::HSV2RGB($finalHue,$finalSat,$finalBri);
											$color = sprintf("#%02x%02x%02x", $rvb[0], $rvb[1], $rvb[2]); // #0d00ff
											foreach ($equipement->getCmd('action') as $colorSearch){
												if ($colorSearch->getConfiguration('fieldname')=='color'){
													$colorSearch->setConfiguration('lastCmdValue',$color)	;
													$colorSearch->save();
												}
											}
										}
									}
									$cmd2->getEqLogic()->refreshWidget();
									break;
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
	// on traite l'info d'un device
	foreach (eqLogic::byType('RaspBEE') as $equipement) {
		if ($equipement->getConfiguration('origid')==$results->id){			
			foreach ($equipement->getCmd('info') as $cmd){
				foreach ($results->action as $actioncmd => $key){
					//error_log("|any_on: ".$actioncmd."=".$key." cmd :".$cmd->getConfiguration('fieldname')."|",3,"/tmp/prob.txt");
					if ($actioncmd==="any_on" && $cmd->getConfiguration('fieldname')=="on"){
						$cmd->event($key);
						break;
					}				
					if ($cmd->getConfiguration('fieldname')==$actioncmd){
						$cmd->event($key);
						break;							
					}
				}
			}
		}			
	}
}



//else
echo json_encode($results->params);
?>