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

var includemode = 0;
$('input[type=radio][name=optionType][value=light]').attr('checked', true);
$('input[type=radio][name=optionType]').on( "click", function() {
	var help ="";
	switch ($( "input[type=radio][name=optionType]:checked" ).val()){
		case 'light' :
			help = "{{Un éclairage}} : {{Permet d'inclure un éclairage zigbee}}.";
			includemode = 0;
			showTouchlink();
			break;
		case 'sensor' :
			help = "{{Autre}} : {{Permet d'inclure d'autres périphériques ZigBEE comme les capteurs, ou interupteurs}}.";
			includemode = 1
			break;
		
	};
	help+="<br>-{{Le prériphérique doit être à 50 cm environ}}.";
	help+="<br>-{{Vous devez appuyer sur le bouton reset du périphérique}}.";
	help+="<br>-{{Après le reset, le périphérique pourra rejoindre le réseau}}.";
  $( "#syncOptionsHelp" ).html(help);
});
$('input[type=radio][name=optionType][value=light]').click();

$('#bt_launchinclude').on('click', function () {
	
});

var displayHelp = function() {
  var n = $( "input:checked" ).length;
  $( "div" ).text( n + (n === 1 ? " is" : " are") + " checked!" );
};

function confirmFullSync(){

	
}

function HTMLClean(value){
	return value.replace(/<\/?[^>]+(>|$)/g, "");
}

function showTouchlink(){
	$.ajax({
		type: "POST", 
		url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
		data: {
			action: "getTouchlink",
		},
		dataType: 'json',
		error: function (request, status, error) {
			console.dir(error);
			$('#div_includeAlert').showAlert({message: error.message, level: 'danger'});
			//handleAjaxError(request, status, error);
		},
		success: function (data) { 
			if (data.state != 'ok') {
				console.dir(data);
				$('#div_includeAlert').showAlert({message: data.result, level: 'danger'});
			}else
			{
				
				$('#includecontent').html(data.result);
			}
		}
	});
}

/*
ZHALightLevel ZHAPresence ZHAOpenClose ZHATemperature ZHAHumidity ZHAPressure ZHASwitch
*/

