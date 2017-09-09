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
//require '../core/php/RaspBEECom.php';  // Ok.
//require_once dirname(__FILE__).'/../core/php/RaspBEECom.php';


//$com = new RaspBEECom;
//echo json_decode($com->findRaspBEE());
//echo $com->getAPIAccess();

?>
<form class="form-horizontal">
    <fieldset>
	<legend><i class="fa fa-list-alt"></i> {{Passerelle}}</legend>
        <div class="form-group">
            <label class="raspbeeGWRefresh col-lg-4 control-label">{{Adresse IP du pont RaspBEE}}</label>
            <div class="col-lg-2">
                <input class="configKey form-control" id="raspbeeGWIP" data-l1key="raspbeeIP" />				
            </div>		
			<div class="col-lg-5">
				<a class="btn btn-success tooltips" id="bt_searchRaspBEE" title="{{Cherche la première passerelle RaspBee sur le réseau}}"><i class="fa fa-refresh"></i></a>
			</div>			
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Clé API RaspBEE}}</label>
			<div class="col-lg-2">
                <input disabled class="configKey form-control" id="raspbeeAPIKEY" data-l1key="raspbeeAPIKEY"/>
            </div>
			<div class="col-lg-5">
				<a class="btn btn-info tooltips" id="bt_raspbeeGETNEWKEY" title="{{Demande une nouvelle cléf API}}" disabled><i class="fa fa-refresh"></i></a>
			</div>		
        </div>
		<div class="form-group">
            <label class="col-lg-4 control-label">{{Clé API plugin}}</label>
			<div class="col-lg-2">
                <input class="configKey form-control" id="pluginAPIKEY" data-l1key="pluginAPIKEY"/>
            </div>
			<div class="col-lg-5">
				<a class="btn btn-info tooltips" id="bt_pluginSETNEWKEY" title="{{Genere une nouvelle cléf API}}"><i class="fa fa-refresh"></i></a>
			</div>		
        </div>
				<div class="form-group">
            <label class="col-lg-4 control-label">{{Token API plugin}}</label>
			<div class="col-lg-2">
                <input class="configKey form-control" id="pluginTOKENKEY" data-l1key="pluginTOKENKEY"/>
            </div>
			<div class="col-lg-5">
				<a class="btn btn-info tooltips" id="bt_pluginSETNEWTOKEN" title="{{Genere une nouvelle cléf API}}"><i class="fa fa-refresh"></i></a>
			</div>		
        </div>
	<legend><i class="fa fa-list-alt"></i> {{Général}}</legend>
	<div class="form-group">
		<label class="col-lg-4 control-label">{{Supprimer automatiquement les périphériques exclus de la passerelle}}</label>
		<div class="col-lg-3">
			<input type="checkbox" class="configKey" data-l1key="autoRemoveExcludeDevice" />
		</div>
	</div>
  </fieldset>
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
        error: function (resp, statut, erreur) {
		$('#md_modal2').dialog({title: "{{Erreur RaspBEE}}"});
		$('#md_modal2').html('Aucune passerelle RaspBEE ne peut être trouvée<br>'+resp+"|"+erreur).dialog('open');	
            handleAjaxError(request, status, error);
		return;	
        },
        success: function (resp,statut) {			
			var value= resp[0].internalipaddress+":"+resp[0].internalport;
			$('#raspbeeGWIP').val(value);
			fieldValidate(value);
            //window.location.reload();
        }
    });
	
	
	/*$.ajax({
		url : 'https://dresden-light.appspot.com/discover',
		type : 'GET',
		dataType : 'json',
		success : function(resp, statut){
			var value= resp[0].internalipaddress+":"+resp[0].internalport;
			$('#raspbeeGWIP').val(value);
			console.log(resp);
			fieldValidate(value);
		},
		error : function(resp, statut, erreur){
		$('#md_modal2').dialog({title: "{{Erreur RaspBEE}}"});
		$('#md_modal2').html('Aucune passerelle RaspBEE ne peut être trouvée<br>'+resp).dialog('open');	
       }		
    });*/
});

$('#raspbeeAPIKEY').on('click', function () {	

 $.ajax({
        type: "POST", 
        url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
        data: {
            action: "getAPIAccess",
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { 
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            //window.location.reload();
        }
    });
});

	$('#raspbeeGWIP').on('change paste keyup', function () {		
		fieldValidate($(this).val());
	});
	
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
