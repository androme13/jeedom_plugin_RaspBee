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

try {
    require_once dirname(__FILE__).'/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
	
	ajax::init();
	  
   /*	if (init('action') == 'syncEqLogicWithRaspBEE') {
		$resp=RaspBEE::syncEqLogicWithRaspBEE();		
		if ($resp===false){
			ajax::error($resp);
		}else{
			ajax::success($resp);
		}
	}*/
	
	if (init('action') == 'deleteRaspBEEUser') {
		$resp=RaspBEE::deleteRaspBEEUser(init('user'));
		error_log("|deleteRaspBEEUser raspbee.ajax.php|".$resp->state,3,"/tmp/prob.txt");
		if ($resp->state=="error"){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
	if (init('action') == 'findRaspBEE') {
		//error_log("findRaspBEE ajax debut ",3,"/tmp/prob.txt");
		$resp=RaspBEE::findRaspBEE();		
		//error_log("findRaspBEE ajax resp : ".$resp->message,3,"/tmp/prob.txt");
		if ($resp->state=="nok"){		
			ajax::error($resp->message);
		} else{
			ajax::success($resp->message);
		}
	}
	
	if (init('action') == 'getAPIAccess') {
		$resp=RaspBEE::getApiKey();
		if ($resp===false){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
	if (init('action') == 'getRaspBEEConf') {
		$resp=RaspBEE::getRaspBEEConf();
		if ($resp===false){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
	if (init('action') == 'getRaspBEEGroups') {
		$resp=RaspBEE::getRaspBEEGroups();
		if ($resp===false){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
	if (init('action') == 'getRaspBEESensors') {
		$resp=RaspBEE::getRaspBEESensors();
		if ($resp===false){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
		
	if (init('action') == 'getRaspBEELights') {
		$resp=RaspBEE::getRaspBEELights();
		if ($resp===false){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
	
	if (init('action') == 'createDevice') {
		//error_log("creation du device demande ajax");
		$resp=RaspBEE::createDevice(init('device'),init('syncType'));
		if ($resp[state]=="nok"){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}
	
	if (init('action') == 'removeAll') {
		//error_log("removeall");
		$resp=RaspBEE::removeAll();
		if ($resp===false){		
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}



	throw new Exception('Aucune methode correspondante');
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}

function genericSendResponse($resp){
	if ($resp->state=="error"){
		ajax::error($resp);
		}
		else
		{
			ajax::success($resp);
		}	
}
?>
