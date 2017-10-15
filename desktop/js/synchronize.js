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
//$('#textarealog').value("coucou");

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
		case 'basic' : help = "{{Normale}} : {{Type de synchronisation par défaut, elle conserve les équipements existants et ajoute les nouveaux équipements ainsi que les nouvelles commandes sur les équipements existants}}."; break;
		case 'renew' : help = "{{Resynchronisation totale}} : {{Tous les équipements sont supprimés, et une nouvelle synchronisation débute}}."; break;
		case 'renewbutid' : help = "{{Resynchronisation partielle}} : {{Tous les équipements sont supprimés, et une nouvelle synchronisation débute, mais les id sont conservés}}."; break;
		
	};
  $( "#syncOptionsHelp" ).html(help);
});
$('input[type=radio][name=optionType][value=basic]').click();

$('#bt_synchronize').on('click', function () {
	$('#treeSync').empty();
	$('#div_syncAlert').showAlert({message: "{{Synchronisation en cours}}...", level: 'info'});
	syncDevices('Capteurs','getRaspBEESensors');	
	syncDevices('Eclairages','getRaspBEELights');	
	syncDevices('Groupes','getRaspBEEGroups');
	$('#div_syncAlert').showAlert({message: "{{Synchronisation Terminée}}", level: 'success'});
});

var displayHelp = function() {
  var n = $( "input:checked" ).length;
  $( "div" ).text( n + (n === 1 ? " is" : " are") + " checked!" );
};

function syncDevices(type,action){
	var treechild =   '<li><input type="checkbox" id="'+type+'"><i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-down"></i><strong><label id="'+type+'Label" for="'+type+'"> '+type+'</label></strong><ul id="'+type+'childs"></ul></li>';
	$('#treeSync').append(treechild);
	$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: action,
		},
dataType: 'json',
error: function (request, status, error) {
			handleAjaxError(request, status, error);
			
		},
success: function (data) {
			
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'info'});
				return;
			}
			let devices = JSON.parse(data.result);
			if (Object.keys(devices).length>0){
				$('#'+type+'Label').append(' ('+Object.keys(devices).length+')');
				for (var device in devices) {
					devices[device].origid=device;
					createDevice(devices[device],type);										
				}
			}	
		} 
	});
}


//code css
//https://makina-corpus.com/blog/metier/2014/construire-un-tree-view-en-css-pur

function createDevice(device,type){
	console.dir(device);
	var deviceName=device.name.replace(/ /g,'')+device.etag;
	var treechild =   '<li style="list-style:none;" id="'+deviceName+'"><div id="'+deviceName+'Icon" class="fa fa-refresh"></div> '+device.name+'</li>';
	$('#'+type+'childs').append(treechild);
	$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "createSensor",
device: device
		},
dataType: 'json',
error: function (request, status, error) {
			handleAjaxError(request, status, error);
			console.log("create error",error);
			$('#'+deviceName+'Icon').attr("class", "fa fa-times");
			$('#'+deviceName+'Icon').css("color", "red");
			$('#'+deviceName).append('( erreur :'+error+')');		
		},
success: function (data) {
			//console.dir ("data",data);
			if (data.state == 'error') {
				//$('#div_syncAlert').showAlert({message: "erreur sync capteur " + data.result.message, level: 'danger'});
				$('#'+deviceName+'Icon').attr("class", "fa fa-times");
				$('#'+deviceName+'Icon').css("color", "orange");
				$('#'+deviceName).append(' <span style="font-size:80%">('+data.result.message+')</span>');				
				//return;
			}
			else
			{
				$('#'+deviceName+'Icon').attr("class", "fa fa-check");
				$('#'+deviceName+'Icon').css("color", "green");
				$('#'+deviceName).append(' <span style="font-size:80%">(Equipement ajouté)</span>');		
			}			
		} 
	});	
};

/*
ZHALightLevel ZHAPresence ZHAOpenClose ZHATemperature ZHAHumidity ZHAPressure ZHASwitch
*/

