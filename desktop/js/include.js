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
			includemode = 1;
			break;
		
	};
	help+="<br>-{{Le prériphérique doit être à 50 cm environ}}.";
	help+="<br>-{{Vous devez appuyer sur le bouton reset du périphérique}}.";
	help+="<br>-{{Après le reset, le périphérique pourra rejoindre le réseau}}.";
	help+="<br>-{{L'inclusion peut ensuite être lancée}}.";
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
	var content='<a id="bt_TouchlinkRefresh" class="btn btn-success"><i class="fa fa-refresh"></i> {{Raffraichir}}</a>';	
	content+='<div id="progressbar" class="AcceptedBar" style="background-color: #497ad6;"></div>';
	
	$('#includecontent').html(content);
	$('#bt_TouchlinkRefresh').on( "click", function(e) {
		$('#bt_TouchlinkRefresh').attr('disabled','disabled');
		$('.blinkLight').attr('disabled','disabled');
		$('.resetLight').attr('disabled','disabled');
		$( "#progressbar" ).progressbar({
		  classes: {
			"ui-progressbar": "ui-corner-all",
		  }
		});
		$( "#progressbar" ).progressbar({value: 0}); 
		$('#progressbar').css("background-color","#FF0000 !important;");  
		showTouchlinkScan();
	});
	showTouchlinkRefresh();
}


function showTouchlinkRefresh(){
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
									
				$('#includetable').html(constructTouchlinkTable(data.result));
				$('.blinkLight').on( "click", function(e) {
					$('.blinkLight').attr('disabled','disabled');
					var reEnable = setInterval(function(){
						$('.blinkLight').removeAttr("disabled");
						clearInterval(reEnable);						
					},6000);
					var id = $(this).closest("tr")[0].id;
					$.ajax({
						type: "POST", 
						url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
						data: {
							action: "getTouchlinkIdentify",
							id: id
						},
						dataType: 'json',
						error: function (request, status, error) {
							console.dir(error);
							$('#div_includeAlert').showAlert({message: error.message, level: 'danger'});
						},
						success: function (data) { 
							if (data.state != 'ok') {
								console.dir(data);
								$('#div_includeAlert').showAlert({message: data.result, level: 'danger'});
							}
						}
					});
				});
			}
		}
	});
}

function showTouchlinkScan(){
		$.ajax({
			type: "POST", 
			url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
			data: {
				action: "getTouchlinkRefresh",
			},
			dataType: 'json',
			error: function (request, status, error) {
				$('#div_includeAlert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) { 
				if (data.state != 'ok') {
					$('#div_includeAlert').showAlert({message: data.result, level: 'danger'});
				}else
				{									
					var timer = 0;
					var downloadTimer = setInterval(function(){
						timer++;
						$( "#progressbar" ).progressbar({value: timer*10});
						if(timer >= 10){
							showTouchlinkRefresh();
							$('.blinkLight').removeAttr("disabled");
							$('.resetLight').removeAttr("disabled");
							$('#bt_TouchlinkRefresh').removeAttr("disabled");
							clearInterval(downloadTimer);
						};
					},1000);								
				}
			}
		});
}

function constructTouchlinkTable(data){
	//var data='{"lastscan":"2017-10-21T23:45:18","result":{"1":{"address":"0x0017880101209030","channel":15,"factorynew":false,"panid":24267,"rssi":-40},"2":{"address":"0x00178801023484a8","channel":15,"factorynew":false,"panid":24267,"rssi":-40}},"scanstate":"idle"}';
	var disabledReset='';
	var dataresultJson=JSON.parse(data);
	var table ='<table id="touchlinkTable" class="table table-bordered table-condensed" style="width:100%;">';
	table+='<caption><span class="label label-default">Dernier scan le : '+dataresultJson.lastscan+'</span></caption>';
	table+='<tr>';
	table+='<th>{{Identifier}}</th>';
	table+='<th>{{ID réseau}}</th>';
	table+='<th>{{Adresse}}</th>';
	table+='<th>{{Canal}}</th>';
	table+='<th>{{rssi}}</th>';
	table+='<th>{{Reset}}</th>';
	table+='</tr>';
	table+='<tbody>';
	for (var i=1;i<Object.keys(dataresultJson.result).length+1;i++) {
		table+='<tr id="'+i+'" >';
		table+='<td><a id="'+i+'blink" name="'+dataresultJson.result[i].address+'" class="btn btn-info  blinkLight"><i class="jeedom2 jeedom2-bright4"></i></a></td>';
		table+='<td>0x'+dataresultJson.result[i].panid.toString(16)+'</td>';
		table+='<td>'+dataresultJson.result[i].address+'</td>';
		table+='<td>'+dataresultJson.result[i].channel+'</td>';
		table+='<td>'+dataresultJson.result[i].rssi+'</td>';
		if (dataresultJson.result[i].factorynew==false)
			table+='<td><a id="'+i+'reset" name="'+dataresultJson.result[i].address+'" class="btn btn-danger resetLight"><i class="fa fa-minus-circle"></i> {{Reset}}</a></td>';
		else
			table+='<td></td>';
		table+='</tr>';	
	}
	table+='</tbody>';
	table+='</table>';
	return table;
}




