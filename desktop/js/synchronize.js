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

/*var syncStatus = {
	value: 0,
	get value() {
		return this.firstName + ' ' + this.lastName;
	},
	set addStage (value) {
		
		this.value++;
	}
	set removeStage(){
		this.value--;
	}
}*/
$('input[type=radio][name=optionType][value=basic]').attr('checked', true);
$('input[type=radio][name=optionType]').on( "click", function() {
	var help ="";
	switch ($( "input[type=radio][name=optionType]:checked" ).val()){
		case 'limited' : help = "{{Limitée}} : {{Conserve les équipements existants et ajoute les nouveaux équipements ainsi que les nouvelles commandes sur les équipements existants}}."; break;
		case 'basic' : help = "{{Normale}} : {{Type de synchronisation par défaut, conserve les équipements existants et ajoute les nouveaux équipements ainsi que l\'ajout/suppression des nouvelles/anciennes commandes sur les équipements existants}}."; break;
		case 'renew' : help = "{{Totale}} : {{Tous les équipements sont supprimés, et une nouvelle synchronisation débute}}."; break;
		case 'renewbutidandname' : help = "{{Partielle}} : {{Tous les équipements sont supprimés, et une nouvelle synchronisation débute, mais les id et les noms sont conservés}}."; break;
		
	};
  $( "#syncOptionsHelp" ).html(help);
});
$('input[type=radio][name=optionType][value=basic]').click();

$('#bt_synchronize').on('click', function () {
	$('#treeSync').empty();
	$('#div_syncAlert').showAlert({message: "{{Synchronisation en cours}}...", level: 'info'});
	syncType=$('input[name=optionType]:checked').val();
	console.log('synctype: ',syncType);
	syncDevices('Capteurs','getRaspBEESensors',syncType);	
	syncDevices('Eclairages','getRaspBEELights',syncType);	
	syncDevices('Groupes','getRaspBEEGroups',syncType);
	$('#div_syncAlert').showAlert({message: "{{Synchronisation Terminée}}", level: 'success'});
});

var displayHelp = function() {
  var n = $( "input:checked" ).length;
  $( "div" ).text( n + (n === 1 ? " is" : " are") + " checked!" );
};

function syncDevices(type,action,syncType){
	var treechild =   '<li class="treeblock"><input type="checkbox" id="'+type+'" class="tree"><i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-down"></i><strong><label id="'+type+'Label" for="'+type+'"> '+type+'</label></strong><ul id="'+type+'childs"></ul></li>';
	$('#treeSync').append(treechild);
	$.ajax({
		type: "POST", 
		url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
		data: {
			action: action
		},
		dataType: 'json',
		error: function (resp, status, error) {
			$('#div_syncAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});		
		},		
		success: function (resp) {
		//console.dir(resp);
		try
			{
			var cleanResp = resp.result.replace('\"', '"');
			}
			catch(e)
			{
			   var cleanResp='invalid json';
			}							
			if (resp.state == 'ok') {
				let devices = JSON.parse(cleanResp);
				if (Object.keys(devices).length>0){
					$('#'+type+'Label').append(' ('+Object.keys(devices).length+')');
					for (var device in devices) {
						devices[device].origid=device;
						//console.dir(devices[device]);
						createEqLogic(devices[device],type,syncType);										
					}
				}	

			} else{
				$('#div_syncAlert').showAlert({message: '{{Impossible d\'afficher les infos}} : '+HTMLClean(resp.result), level: 'danger'});
			}
		} 
	});
}


//code css
//https://makina-corpus.com/blog/metier/2014/construire-un-tree-view-en-css-pur

function createEqLogic(device,type,syncType){
	//console.dir(device);
	var deviceName=device.name.replace(/ /g,'')+device.etag;
	var treechild = '<li class="tree" style="list-style:none;" id="'+deviceName+'"><div id="'+deviceName+'Icon" class="fa fa-refresh"></div> '+device.name+'</li>';
	$('#'+type+'childs').append(treechild);
	$.ajax({
		type: "POST", 
		url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
		data: {
			action: "createEqLogic",
			device: device,
			syncType: syncType
		},
		dataType: 'json',
		error: function (resp, status, error) {
			$('#div_syncAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});
			//handleAjaxError(request, status, error);
			//console.log("create error",error);
			$('#'+deviceName+'Icon').attr("class", "fa fa-times");
			$('#'+deviceName+'Icon').css("color", "red");
			$('#'+deviceName).append('{{Erreur}} : '+error+' ('+resp.status+')');		
		},
		
		success: function (resp) {		
			try	{
				
					var cleanResp = resp.result.replace('\"', '"');
					console.dir(cleanResp);
					var jsonResp = JSON.parse(cleanResp);
				   
				}
				catch(e)
				{
				   var jsonResp=new Object();
				   jsonResp.cmdError = 3;
				}							
				if (jsonResp.cmdError == 3) {
					$('#'+deviceName+'Icon').attr("class", "fa fa-times");
					$('#'+deviceName+'Icon').css("color", "red");
					$('#'+deviceName).append(' <span style="font-size:80%">erreur inconnue ('+cleanResp+')</span>');	
				} else{

					switch(jsonResp.cmdError){
						case 0:
							$('#'+deviceName+'Icon').attr("class", "fa fa-check");
							$('#'+deviceName+'Icon').css("color", "green");
							$('#'+deviceName).append(' <span style="font-size:80%">Equipement dejà à jour</span>');
							break;
						case 1:
							$('#'+deviceName+'Icon').attr("class", "fa fa-refresh");
							$('#'+deviceName+'Icon').css("color", "green");
							$('#'+deviceName).append(' <span style="font-size:80%">Equipement mis à jour (+'+jsonResp.addedCmd+' -'+jsonResp.removedCmd+')</span>');
							break;
						case 2:
							$('#'+deviceName+'Icon').attr("class", "fa fa-plus");
							$('#'+deviceName+'Icon').css("color", "DarkCyan");
							$('#'+deviceName).append(' <span style="font-size:80%">Equipement ajouté ('+jsonResp.TotalCmdCount+')</span>');
							break;										
					}					
				}	
		}
	});	
};

function HTMLClean(value){
	return value.replace(/<\/?[^>]+(>|$)/g, "");
}

/*
ZHALightLevel ZHAPresence ZHAOpenClose ZHATemperature ZHAHumidity ZHAPressure ZHASwitch
*/

