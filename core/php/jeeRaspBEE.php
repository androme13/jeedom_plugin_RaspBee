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

class colorconv{
	
	public function fGetRGB($iH, $iS, $iV) {
		if($iH < 0)   $iH = 0;   // Hue:
		if($iH > 360) $iH = 360; //   0-360
		if($iS < 0)   $iS = 0;   // Saturation:
		if($iS > 100) $iS = 100; //   0-100
		if($iV < 0)   $iV = 0;   // Lightness:
		if($iV > 100) $iV = 100; //   0-100
		$dS = $iS/100.0; // Saturation: 0.0-1.0
		$dV = $iV/100.0; // Lightness:  0.0-1.0
		$dC = $dV*$dS;   // Chroma:     0.0-1.0
		$dH = $iH/60.0;  // H-Prime:    0.0-6.0
		$dT = $dH;       // Temp variable
		while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
		$dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link
		switch(floor($dH)) {
		case 0:
			$dR = $dC; $dG = $dX; $dB = 0.0; break;
		case 1:
			$dR = $dX; $dG = $dC; $dB = 0.0; break;
		case 2:
			$dR = 0.0; $dG = $dC; $dB = $dX; break;
		case 3:
			$dR = 0.0; $dG = $dX; $dB = $dC; break;
		case 4:
			$dR = $dX; $dG = 0.0; $dB = $dC; break;
		case 5:
			$dR = $dC; $dG = 0.0; $dB = $dX; break;
		default:
			$dR = 0.0; $dG = 0.0; $dB = 0.0; break;
		}
		$dM  = $dV - $dC;
		$dR += $dM; $dG += $dM; $dB += $dM;
		$dR *= 255; $dG *= 255; $dB *= 255;
		return array(round($dR),round($dG),round($dB));
	}
	
	public function _color_hsl2rgb($hsl) {
		$h =$hsl[0]/365;
		$s = $hsl[1]/256;
		$l = $hsl[2]/256;
		$r; 
		$g; 
		$b;
		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
		$m = $l - ( $c / 2 );
		if ( $h < 60 ) {
			$r = $c;
			$g = $x;
			$b = 0;
		} else if ( $h < 120 ) {
			$r = $x;
			$g = $c;
			$b = 0;			
		} else if ( $h < 180 ) {
			$r = 0;
			$g = $c;
			$b = $x;					
		} else if ( $h < 240 ) {
			$r = 0;
			$g = $x;
			$b = $c;
		} else if ( $h < 300 ) {
			$r = $x;
			$g = 0;
			$b = $c;
		} else {
			$r = $c;
			$g = 0;
			$b = $x;
		}
		$r = ( $r + $m ) * 255;
		$g = ( $g + $m ) * 255;
		$b = ( $b + $m  ) * 255;
		return array( floor( $r ), floor( $g ), floor( $b ) );
	}
}

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

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
	foreach (eqLogic::byType('RaspBEE') as $equipement) {
		if ($equipement->getConfiguration('origid')==$results->id)			
		foreach ($equipement->getCmd('info') as $cmd){
			foreach ($results->action as $actioncmd => $key){
				if ($cmd->getConfiguration('fieldname')==$actioncmd){
					$cmd->event($key);
					foreach ($equipement->getCmd('action') as $cmd2){
						foreach ($results->action as $actioncmd2 => $key2){
							if ($cmd2->getConfiguration('fieldname')==$actioncmd2){
								error_log("|INFO ".$actioncmd.'('.$key.') => ACTION '.$actioncmd2.":".$key2."|",3,'/tmp/prob.txt');

								if ($cmd2->getConfiguration('lastCmdValue')!=$key){
									
									$cmd2->setConfiguration('lastCmdValue',$key);
									$cmd2->save();
									
									// on traite le changement de couleur du widget
									// on recuperes aussi toutes les valeurs hue sat et bri (hsl)
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
											
											$rvb = colorconv::fGetRGB($finalHue,$finalSat,$finalBri);
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