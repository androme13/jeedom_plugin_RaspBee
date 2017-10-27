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
				//console.dir(data);
				$('#div_includeAlert').showAlert({message: data.result, level: 'danger'});
			}else
			{
				
				$('#includecontent').html(constructTouchlinkTable(data.result));
			}
		}
	});
}

function constructTouchlinkTable(data){
	
	
	//var data='{"lastscan":"2017-10-21T23:45:18","result":{"1":{"address":"0x0017880101209030","channel":15,"factorynew":false,"panid":24267,"rssi":-40},"2":{"address":"0x00178801023484a8","channel":15,"factorynew":false,"panid":24267,"rssi":-40}},"scanstate":"idle"}';

	var dataresultJson=JSON.parse(data);
	var table ='<table class="table table-bordered table-condensed" style="width:100%">';
	//table+='<tr>';
	table+='<caption>Dernier scan le : '+dataresultJson.lastscan+'</caption>';
	//table+='</tr>';
	table+='<tr>';
	table+='<th>{{Faire clignoter}}</th>';
	table+='<th>{{Adresse}}</th>';
	table+='<th>{{Canal}}</th>';
	table+='<th>{{rssi}}</th>';
	table+='<th>{{Reset}}</th>';
	table+='</tr>';
	table+='<tbody>';


	for (var i=1;i<Object.keys(dataresultJson.result).length+1;i++) {

		table+='<tr>';
		table+='<td><a id="'+i+'blink" name="'+dataresultJson.result[i].address+'" class="btn btn-info  touchlinkDeviceReset"><i class="jeedom2 jeedom2-bright4"></i> {{Clignoter}}</a></td>';
		table+='<td>'+dataresultJson.result[i].address+'</td>';
		table+='<td>'+dataresultJson.result[i].channel+'</td>';
		table+='<td>'+dataresultJson.result[i].rssi+'</td>';
		table+='<td><a id="'+i+'reset" name="'+dataresultJson.result[i].address+'" class="btn btn-danger  touchlinkDeviceReset"><i class="fa fa-minus-circle"></i> {{Reset}}</a></td>';
		table+='</tr>';	
	}
	table+='</tbody>';
	table+='</table>';
	return table;
	
}

/*
ZHALightLevel ZHAPresence ZHAOpenClose ZHATemperature ZHAHumidity ZHAPressure ZHASwitch
*/

