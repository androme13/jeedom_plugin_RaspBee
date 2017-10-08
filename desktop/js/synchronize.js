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

$('#bt_synchronize').on('click', function () {
	syncDevices('Capteurs','getRaspBEESensors');	
	syncDevices('Eclairages','getRaspBEELights');	
	syncDevices('Groupes','getRaspBEEGroups');
});

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

