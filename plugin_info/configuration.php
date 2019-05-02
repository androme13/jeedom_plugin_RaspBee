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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
require_once dirname(__FILE__).'/../core/class/RaspBEECom.class.php';
require_once dirname(__FILE__).'/../core/class/DeCONZTools.class.php';

?>
<div id='div_configAlert' style="display: none;"></div>
<form class="form-horizontal">
	<div class="tab-pane active" id="summary_network">
		<span><i class="fa fa-info-circle"></i> {{Une fois la passerelle saisie, sauvegardez avant de demander une clé API RaspBEE et sauvegardez de nouveau}}.</span>
		<div class="panel panel-primary">
			<div class="panel-heading"><h4 class="panel-title"><i class="fa techno-freebox"></i> {{Passerelle}}</h4></div>
			<div class="panel-body">
				<div class="form-group">
					<label class="raspbeeGWRefresh col-lg-4 control-label">{{Adresse IP:PORT du pont RaspBEE}}</label>
					<div class="col-lg-2">
						<input class="configKey form-control" id="raspbeeGWIP" data-l1key="raspbeeIP" />
					</div>
					<div class="col-lg-5">
						<a class="btn btn-success tooltips" id="bt_searchRaspBEE" title="{{Cherche automatiquement la première passerelle RaspBee sur le réseau}}"><i class="fa fa-refresh"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading"><h4 class="panel-title"><i class="fa securite-key1"></i> {{Clé API}}</h4></div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-4 control-label">{{Clé API RaspBEE}}</label>
					<div class="col-lg-2">
						<input disabled class="configKey form-control" id="raspbeeAPIKEY" data-l1key="raspbeeAPIKEY"/>
					</div>
					<div class="col-lg-5">
						<a class="btn btn-info tooltips" id="bt_raspbeeGETNEWKEY" title="{{Demande automatiquement une nouvelle cléf API}}" disabled><i class="fa fa-refresh"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-primary" style="visibility:hidden;">
			<div class="panel-heading"><h4 class="panel-title"><i class="personne personne-boy22"></i> {{Mot de passe de l'utilisateur 'pi' de la passerelle distante}}</h4></div>
						<span><i class="fa fa-info-circle"></i> {{Utile pour que le plugin effectue les mise à jour de la passerelle tout seul. Si non rempli ou erroné, la mise à jour devra être effectuée manuellement par l'utilisateur}}.</span>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-4 control-label">{{Mot de passe}}</label>
					<div class="col-lg-2">
						<input type="password" class="configKey form-control" id="raspbeePIPWD" data-l1key="raspbeePIPWD"/>
					</div>
						<div class="col-lg-5">
						<a class="btn btn-info tooltips" id="bt_raspbeePIPWD" title="{{Tester l'accès}}"><i class="fa fa-refresh"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-primary"  style="visibility:hidden;">
			<div class="panel-heading"><h4 class="panel-title"><i class="personne personne-boy22"></i> {{etat ssh}}</h4></div>
						<span><i class="fa fa-info-circle"></i> {{ssh}}.</span>
			<div class="panel-body">
				<div class="form-group">
					<textarea id="sshtext" class="col-lg-8" rows="30"><?php
						$DT = new DeCONZTools;

						$result = $DT->cnxTest("dpkg-deb --info deconz-2.04.82-qt5.deb");
						$DT->cnxClose();
						unset($DT);
						echo $result->message;
					?></textarea>
					<a class="btn btn-info tooltips" id="bt_ssh" title="{{Tester}}"><i class="fa fa-refresh"></i></a>
				</div>
			</div>
		</div>
	</div>
</form>
<script>

$('#bt_searchRaspBEE').on('click', function () {
	$.ajax({
type: "POST",
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php",
data: {
action: "findRaspBEE",
		},
dataType: 'json',
error: function (resp, status, erreur) {
			$('#div_configAlert').showAlert({message: '{{Erreur}} : '+erreur+' ('+resp.status+')', level: 'danger'});
			//handleAjaxError(resp, statut, erreur);
		},
success: function (resp,status) {
			try
			{
			   var cleanResp = JSON.parse(resp.result.replace('\"', '"'));
			}
			catch(e)
			{
			   var cleanResp='invalid json';
			}
			if (resp.state == 'ok') {
				$('#raspbeeGWIP').val(cleanResp[0].internalipaddress+":"+cleanResp[0].internalport);
				fieldValidate($('#raspbeeGWIP').val());
				$('#div_configAlert').showAlert({message: '{{Passerelle trouvée}} : '+cleanResp[0].name+' ( {{Id}}='+cleanResp[0].id+', {{Mac}}='+cleanResp[0].macaddress+')', level: 'success'});
			} else{
				$('#div_configAlert').showAlert({message: '{{Passerelle introuvable}} : '+HTMLClean(resp.result), level: 'danger'});
			}
		}
	});
});

$('#bt_raspbeeGETNEWKEY').on('click', function () {
	$.ajax({
		type: "POST",
		url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php",
		data: {
		action: "getAPIAccess",
				},
		dataType: 'json',
		error: function (resp, status, error) {
					$('#div_configAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});
					//handleAjaxError(request, status, error);
				},
		success: function (resp) {
			try
			{
			   var cleanResp = JSON.parse(resp.result.replace('\"', '"'));
			}
			catch(e)
			{
			   var cleanResp='invalid json';
			}
			if (resp.state == 'ok') {
				$('#div_configAlert').showAlert({message: '{{Clé récupérée}}: '+cleanResp[0].success.username, level: 'success'});
				$('#raspbeeAPIKEY').val(cleanResp[0].success.username);
			} else{
				console.dir("cleanresp",resp);
				$('#div_configAlert').showAlert({message: '{{Impossible de récupérer une clé}} : '+HTMLClean(resp.result), level: 'danger'});
			}
		}
	});
});

$('#raspbeeGWIP').on('change paste keyup', function () {
	fieldValidate($(this).val());
});


function HTMLClean(value){
	return value.replace(/<\/?[^>]+(>|$)/g, "");
}


function fieldValidate(value){
	if (validateIpAndPort(value)==true){
		$('#raspbeeAPIKEY').prop('disabled', false);
		$('#bt_raspbeeGETNEWKEY').removeAttr('disabled');
	}
	else{
		$('#raspbeeAPIKEY').prop('disabled', true);
		$('#bt_raspbeeGETNEWKEY').attr('disabled', 'disabled');

	}
}

function validateIpAndPort(input) {
	var parts = input.split(":");
	var ip = parts[0].split(".");
	var port = parts[1];
	return validateNum(port, 1, 65535) &&
	ip.length == 4 &&
	ip.every(function (segment) {
		return validateNum(segment, 0, 255);
	});
}

function validateNum(input, min, max) {
	var num = +input;
	return num >= min && num <= max && input === num.toString();
}

</script>
