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

try {
    require_once dirname(__FILE__).'/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
	ajax::init();

	/*if (init('action') == 'removeAll') {
		$resp=RaspBEE::removeAll();
		if ($resp===false){
			ajax::error($resp);
		} else{
			ajax::success($resp);
		}
	}*/
	$resp='';
	switch (init('action')){
		case 'createEqLogic' :
		$resp=RaspBEE::createEqLogic(init('device'),init('syncType'));
		break;
		case 'deleteRaspBEEUser' :
		$resp=RaspBEE::deleteRaspBEEUser(init('user'));
		break;
		case 'eqLogicDelete' :
		$resp=RaspBEE::eqLogicDelete(init('id'));
		break;
		case 'findRaspBEE' :
		$resp=RaspBEE::findRaspBEE();
		break;
		case 'groupCreate' :
		$resp=RaspBEE::groupCreate(init('name'));
		break;
		case 'groupDelete' :
		$resp=RaspBEE::groupDelete(init('id'));
		break;
		case 'getAPIAccess' :
		$resp=RaspBEE::getApiKey();
		break;
		case 'getRaspBEEConf' :
		$resp=RaspBEE::getRaspBEEConf();
		break;
		case 'getRaspBEEGroups' :
		$resp=RaspBEE::getRaspBEEGroups();
		break;
		case 'getRaspBEESensors' :
		$resp=RaspBEE::getRaspBEESensors();
		break;
		case 'getRaspBEELights' :
		$resp=RaspBEE::getRaspBEELights();
		break;
		case 'getTouchlinkInfos' :
		$resp=RaspBEE::getTouchlink();
		break;
		case 'getTouchlinkIdentify' :
		$resp=RaspBEE::getTouchlinkIdentify(init('id'));
		break;
		case 'launchTouchlinkScan' :
		$resp=RaspBEE::getTouchlinkRefresh();
		break;
		case 'removeAll' :
		$resp=RaspBEE::removeAll();
		break;
	}
	if ($resp!='')
		if ($resp->state=="nok") ajax::error($resp->message);
		else ajax::success($resp->message);

	throw new Exception('Aucune methode correspondante');
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}

function genericSendResponse($resp){
	if ($resp->state=="error") {
		ajax::error($resp);
	}
	else
	{
		ajax::success($resp);
	}
}
