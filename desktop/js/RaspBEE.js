
/* This file is part of Plugin RaspBEE for jeedom.
*
* Plugin RaspBEE for jeedom is free software: you can redistribute it and/or modify
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
refreshEqlogicsList();

$('#bt_syncEqLogic').on('click', function () {
	$('#md_modal').dialog({
title: "{{Synchronisation}}",
dialogClass: "no-close",
		// on cache le bouton fermer
open: function(event, ui) { jQuery('.ui-dialog-titlebar-close').hide(); },
buttons: [
		{
text: "{{Fermer}}",
click: function() {
				window.location.reload();
			}
		}
		],
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

/*$('#bt_RaspBEERemoveAll').on('click', function () {	
	var dialog_title = '{{Confirmation de suppression de tous les équipements RaspBEE}}';
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '<label class="control-label" > {{Veuillez confirmer la suppression de tous les équipements RaspBEE}}</label><br><label class="lbl lbl-warning" for="name">{{Attention, une fois supprimés, ils le seront définitivement.}}</label>';
	dialog_message += '</form>';
	bootbox.dialog({
		title: dialog_title,
		message: dialog_message,
		buttons: {
			"{{Annuler}}": {
				callback: function () {
				}
		},
		success: {
			label: "{{Supprimer}}",
			className: "btn-danger",
			callback: function () {		   
				$.ajax({
					type: "POST", 
					url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
					data: {
						action: "removeAll",
					},
					dataType: 'json',
					error: function (request, status, error) {
						console.dir(error);
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
						handleAjaxError(request, status, error);
					},
					success: function (data) { 
						if (data.state != 'ok') {
							console.dir(data);
							$('#div_alert').showAlert({message: data.result, level: 'danger'});
						}else
						{									
							$('#div_alert').showAlert({message: "{{Tous les équipements ont été supprimés}}", level: 'success'});
								
						}
						window.location.reload();	
						}
					});
								
				}
		},
		}
	});
});*/

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
	if (("devicemembership" in _eqlogic.configuration))
	printMasterEqLogic(_eqlogic);
	else
	$('#masterEqLogic').empty();
	if (("lights" in _eqlogic.configuration))
	printMembersEqLogic(_eqlogic);
	else
	$('#membersEqLogic').empty();
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
	$('#masterEqLogic').empty();	 
	var devicemembership=JSON.parse(_eqLogic.configuration.devicemembership);
	if (!is_null(devicemembership)){
		var master ="";
		master+='<legend><i class="fa fa-th-large"></i> {{Contrôleur maître}}</legend>'
		master+='<div class="mastersCard" style="display: flex;">';	
		for(var i= 0; i < devicemembership.length; i++){
			jeedom.raspbee.eqLogic.humanNameByOrigIdAndType({
origId:devicemembership[i],
				//action:'humanNameByOrigId',
type:'switch',
error: function(error){
					console.log("THE error printMasterEqLogic "+_eqLogic.name);	
					console.log("THE error printMasterEqLogic devicemembership[i] "+devicemembership[i]);
				},
success:function (result){
					if (result!=undefined){
						console.log("THE result: "+result+"|");
						var card = "";
						card+='<div class="eqLogicDisplayCard cursor eql'+result.id+'" data-eqLogic_id="6" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
						card+= "<center>";
						card+= '<i class="fa fa-th-large" style="font-size : 6em;color:#767676;"></i>';
						card+= '<br>';
						card+= '<span style="font-size : 0.8em;">';
						card+= '{{Commande}}';
						card+= '</span>';
						card+= "<span style='font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;'><center>"+result.humanName+"</center></span>";
						card+='</div>';				
						$('.mastersCard').append(card);
						$('.eql'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
					}			
				}
			})
		}
		master+="</div>";	
		$('#masterEqLogic').append(master);	
	}}

function printMembersEqLogic(_eqLogic){
	$('#membersEqLogic').empty();

	
	var lights=JSON.parse(_eqLogic.configuration.lights);
	if (!is_null(lights)){

		var master ="";
		master+='<legend><i class="fa fa-table"></i> {{Membres du groupe}}</legend>'
		master+='<div class="membersCard" style="display: flex;">';

		for(var i= 0; i < lights.length; i++){
			jeedom.raspbee.eqLogic.humanNameByOrigIdAndType({
origId:lights[i],
type: "light",
error: function(error){
					console.log("THE error printMemberEqLogic "+_eqLogic.name);	
					console.log("THE error printMemberEqLogic light[i] "+lights[i]);

				},
success:function (result){
					if (result!=undefined){			
						//console.log("success");		
						var card = "";
						card+='<div class="eqLogicDisplayCard cursor eql'+result.id+'" data-eqLogic_id="6" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
						card+= "<center>";
						card+= '<i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;"></i>';
						card+= '<br>';
						card+= '<span style="font-size : 0.8em;">';
						card+= '{{Eclairage}}';
						card+= '</span>';
						card+= "<span style='font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;'><center>"+result.humanName+"</center></span>";
						card+='</div>';				
						$('.membersCard').append(card);
						$('.eql'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
					}
				}		
			});
		}
		master+="</div>";
		$('#membersEqLogic').append(master);

	}
}


function refreshEqlogicsList(){
	console.log("refreshEqlogicsList2");
	//console.dir(jeedom.raspbee);
				jeedom.raspbee.eqLogic.getAll({
error: function(error){
					console.log("THE error refreshEqlogicsList "+error);	
					

				},
success:function (result){
	console.log("result eqlogics",result.getHumanName(true,true));	
					if (result!=undefined){			
						//console.log("result eqlogics",result);		
						
					}
				}		
			});
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
			//$('#div_alert').showAlert({message: "{{Synchronisation effectuée avec succès}}", level: 'success'});
			//window.location.reload();
		}
	});
}
