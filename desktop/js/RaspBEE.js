
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
$('#bt_syncEqLogic').on('click', function () {
	//syncEqLogicWithRaspBEE();
	$('#md_modal').dialog({
title: "{{Synchronisation}}",
dialogClass: "no-close",
buttons: [
		{
text: "{{Fermer}}",
click: function() {
				window.location.reload();
				//$( this ).dialog( "close" );
			}
		}
		],
		//width: 400
	});
	$('#md_modal').load('index.php?v=d&plugin=RaspBEE&modal=synchronize').dialog('open');
	
});


$('#bt_RaspBEEHealth').on('click', function () {
	$('#md_modal').dialog({title: "{{Santé RaspBEE}}"});
	$('#md_modal').load('index.php?v=d&plugin=RaspBEE&modal=health').dialog('open');
});

$('#bt_RaspBEENetwork').on('click', function () {
	$('#md_modal').dialog({title: "{{Réseaux RaspBEE}}"});
	$('#md_modal').load('index.php?v=d&plugin=RaspBEE&modal=network').dialog('open');
});

$('#bt_RaspBEERemoveAll').on('click', function () {
	$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "removeAll",
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
			window.location.reload();	
		}
	});
});

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

/*
* Fonction de listage des commandes
*/
function addCmdToTable(_cmd) {
	if (!isset(_cmd)) {
		var _cmd = {configuration: {}};
	}
	if (!isset(_cmd.configuration)) {
		_cmd.configuration = {};
	}
	var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	tr += '<td class="expertModeVisible">';
	tr += '<span class="cmdAttr" data-l1key="id" style="" placeholder="#"></span>';
	tr += '</td>';
	tr += '<td>';
	tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}" readonly>';
	tr += '</td>';
	tr += '<td>';
	tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
	tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
	tr += '</td>';
	tr += '<td>';
	if (is_numeric(_cmd.id)) {
		tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
		tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
	}
	//tr +=_cmd.configuration.fieldname;
	tr += '</td>';
	$('#table_cmd tbody').append(tr);
	$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
	if (isset(_cmd.type)) {
		$('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
	}
	jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

// fonction executée par jeedom lors de l'affichage des details d'un eqlogic
function printEqLogic(_eqlogic) {
	if (!isset(_eqlogic)) {
		var _eqlogic = {configuration: {}};
	}
	if (!isset(_eqlogic.configuration)) {
		_eqlogic.configuration = {};
	}
	$('#table_infoseqlogic tbody').empty();
	var subst = { true : '<span title="Le périphérique est online"><i class="fa fa-check-circle" style="color:#007600;"></i></span', false: '<span title="Le périphérique est offline"><i class="fa fa-times-circle" style="color:#760000;"></i></span>'};
	printEqLogicHelper(false,"{{Connecté}}","reachable",_eqlogic,subst);
	printEqLogicHelper(true,"{{Id origine}}","origid",_eqlogic);	
	printEqLogicHelper(false,"{{Marque}}","manufacturername",_eqlogic);
	printEqLogicHelper(false,"{{Modèle}}","modelid",_eqlogic);	
	printEqLogicHelper(true,"{{Firmware}}","swversion",_eqlogic);	
	printEqLogicHelper(true,"{{Type}}","type",_eqlogic);	
	printEqLogicHelper(true,"{{UID}}","uniqueid",_eqlogic);		
	printEqLogicHelper(true,"{{Devicemembership}}","devicemembership",_eqlogic);
	printEqLogicHelper(true,"{{Lights}}","lights",_eqlogic);
	if (("devicemembership" in _eqlogic.configuration)) printMasterEqLogic(_eqlogic);
	else $('#masterEqLogic').empty();
}
 function printEqLogicHelper(expertMode,label,name,_eqLogic,_subst){
	 var expertModeVal="";
	 if (expertMode==true) expertModeVal = "expertModeVisible";
 // (expertmodevisible,nom du champ,eqlogic en cours,tableau de substitution des valeurs)
		if (_eqLogic.configuration[name]==undefined) return;
		if (_subst!=null && _subst!=undefined){
			name = _subst[_eqLogic.configuration[name]];
			var trm = '<tr class="eqLogic '+expertModeVal+'"><td class="col-sm-2"><span class="label control-label" style="font-size : 1em;">'+label+'</span></td><td><span class="label label-default" style="font-size : 1em;">'+name+'</span></td></tr>';
		}
		else
		var trm = '<tr class="eqLogic '+expertModeVal+'"><td class="col-sm-2"><span class="label control-label" style="font-size : 1em;">'+label+'</span></td><td><span class="label label-default" style="font-size : 1em;"> <span class="eqLogicAttr" data-l1key="configuration" data-l2key="'+name+'"></span></span></td></tr>';
		$('#table_infoseqlogic tbody').append(trm);
		$('#table_infoseqlogic tbody tr:last').setValues(_eqLogic, '.eqLogicAttr');		
 }
 
function printMasterEqLogic(_eqLogic){
	var humanname='<center><span class="label label-default" style="text-shadow : none;">Aucun</span><br><strong> '+_eqLogic.name+'</strong></center>';
	var master ="";
	//master+='<table id="table_masterEqLogic" class="table table-condensed expertModeVisible" ><th style="text-align: center">{{Contrôleur maître}}</th><tr><td>';
	master+='<span class="label control-label" style="font-size : 1em;">Contôleur Maître</span>'
	master+='<div class="eqLogicDisplayCard cursor" data-eqLogic_id="6" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
	master+= "<center>";
	master+= '<i class="fa fa-th-large" style="font-size : 6em;color:#767676;"></i>';
	master+= '<br>';
	master+= '<span style="font-size : 0.8em;">';
	master+= '{{Commande}}';
	master+= '</span>';
	master+= "<span style='font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;'><center>"+humanname+"</center></span>";
	//master+='</td></tr><tbody></tbody></table>';
	master+='</div';
	$('#masterEqLogic').html(master);
	//$('#masterEqLogic').setValues(_eqLogic, '.eqLogicAttr');	
	console.dir(_eqLogic);
}

/*function arraySearch(arr,val) {
    for (var i=0; i<arr.length; i++)
        if (arr[i] === val)                    
            return i;
    return false;
  }*/





function syncEqLogicWithRaspBEE() {
	$.ajax({
type: "POST", 
url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
data: {
action: "syncEqLogicWithRaspBEE",
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
}
