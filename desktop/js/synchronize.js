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
$('#bt_synchronize').on('click', function () {
	// $logs = $("#textarealog").val();
	//$logs+= "ok";
	//$("#textarealog").val($logs);
	syncSensors();
	syncLights();
});


function syncSensors(){
$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "getRaspBEESensors",
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

			// on parse les sensors
			let devices = JSON.parse(data.result);
			for (var device in devices) {
				devices[device].origid=device;
				createSensor(devices[device]);				
			}
			
			//on lance la synchro des lights ensuite		
} 
	});
}

function syncLights(){
$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "getRaspBEELights",
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

			// on parse les lights
			console.log("lights: ",data.result);
			let devices = JSON.parse(data.result);
			for (var device in devices) {
				devices[device].origid=device;
				createLight(devices[device]);				
			}
} 
	});	
}


function createSensor(sensor){
$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "createSensor",
device: sensor
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
} 
	});	
};

function createLight(light){
$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "createLight",
device: light
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
} 
	});	
};

/*
ZHALightLevel ZHAPresence ZHAOpenClose ZHATemperature ZHAHumidity ZHAPressure ZHASwitch
*/

