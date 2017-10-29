
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


$('#bt_include').on('click', function () {
	$('#md_modal').dialog({
title: "{{Mode inclusion}}",
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
	$('#md_modal').load('index.php?v=d&plugin=RaspBEE&modal=include').dialog('open');
	
});




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

$('#bt_addGroup').on('click', function () {
	createGroup();	
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


function createGroup(){
var dialog_title = "{{Création d'un groupe}}";
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '{{Veuillez saisir le nom du groupe à créer}}.<br><label for="text-3">Nom du groupe: </label><br><input data-clear-btn="true" name="text-3" id="groupName" value="" type="text"><br><br><label class="lbl lbl-warning" for="name">{{Attention, une fois le groupe crée, une synchronisation limitée débutera}}.</label>';
	dialog_message += '</form>';
	bootbox.dialog({
		title: dialog_title,
		message: dialog_message,
		buttons: {
			"{{Annuler}}": {
				callback: function () {
				$('#div_raspbeeAlert').showAlert({message: "{{Création de groupe annulée}}", level: 'info'});
				}
			},
		success: {
			label: "{{Creer le groupe}}",
			className: "btn-success",
			callback: function () {		
				//console.log($("#groupName").val())
				$.ajax({
					type: "POST", 
					url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
					data: {
						action: "groupCreate",
						name: $("#groupName").val()
					},
					dataType: 'json',
					error: function (request, status, error) {
						console.dir(error);
						$('#div_raspbeeAlert').showAlert({message: error.message, level: 'danger'});
						//handleAjaxError(request, status, error);
					},
					success: function (data) { 
						if (data.state != 'ok') {
							console.dir(data);
							$('#div_raspbeeAlert').showAlert({message: data.result, level: 'danger'});
						}else
						{
							$('#div_raspbeeAlert').showAlert({message: "{{Le groupe est crée avec succès}}", level: 'success'});
						}
					}
				});
								
			}
		}
		}
	});	
	
}

// fonction executée par jeedom lors de l'affichage des details d'un eqlogic
function printEqLogic(_eqLogic) {
	if (!isset(_eqLogic)) {
		var _eqLogic = {configuration: {}};
	}
	if (!isset(_eqLogic.configuration)) {
		_eqLogic.configuration = {};
	}
	$('#table_infoseqlogic tbody').empty();
	$('#buttons_infoseqlogic').empty();
	var subst = { true : '<span title="Le périphérique est online"><i class="fa fa-check-circle" style="color:#007600;"></i></span', false: '<span title="Le périphérique est offline"><i class="fa fa-times-circle" style="color:#760000;"></i></span>'};
	printEqLogicHelper(false,"{{Connecté}}","reachable",_eqLogic,subst);
	printEqLogicHelper(true,"{{Id origine}}","origid",_eqLogic);	
	printEqLogicHelper(false,"{{Marque}}","manufacturername",_eqLogic);
	printEqLogicHelper(false,"{{Modèle}}","modelid",_eqLogic);	
	printEqLogicHelper(true,"{{Firmware}}","swversion",_eqLogic);	
	printEqLogicHelper(true,"{{Type}}","type",_eqLogic);	
	printEqLogicHelper(true,"{{UID}}","uniqueid",_eqLogic);
	//printEqLogicHelper(true,"{{membership}}","devicemembership",_eqLogic);
	// on regarde si c'est un groupe ou pas
	// on ne peut supprimer les groupes qui n'ont pas de ctrl maitre
	if(_eqLogic.configuration.type=="LightGroup" && ("devicemembership" in _eqLogic.configuration)){
		if (_eqLogic.configuration.devicemembership==="null")
		$('#buttons_infoseqlogic').append('<a id="bt_addGroup" class="btn btn-danger" style="margin-bottom:20px;"><i class="fa fa-minus-circle"></i> {{Supprimer totalement le groupe}}</a>');
	else
		$('#buttons_infoseqlogic').append('<a class="label label-info" style="margin-bottom:20px;"><i class="fa fa-info-circle"></i> {{Ce groupe ne peut être supprimé totalement car il appartient à un contrôleur}}.</a>');
	}
	if (("devicemembership" in _eqLogic.configuration))
	printMasterEqLogic(_eqLogic);
	else
	$('#masterEqLogic').empty();
	if (("lights" in _eqLogic.configuration))
	printMembersEqLogic(_eqLogic);
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
						card+= "<span style='font-size : 1.1em;position:relative; top : 15px;white-space: pre-wrap;word-wrap: break-word;'><center>"+result.humanName+"</center></span>";
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
		master+='<legend><i class="fa fa-table"></i> {{Membres du groupe}} ('+lights.length+')</legend>'
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
						card+='<div style="position: relative;">';
						card+='<div class="eqlremove'+result.id+'" ownerGroup="'+_eqLogic.configuration.id+'" style="margin:0;position:absolute;top: 3px;left: 140px;"><a id="bt_removeFromGroup" title="{{Retirer cet équipement du groupe}}"><i class="fa fa-minus-circle" style="color:#c9302c;font-size : 2em;"></i></a></div>';
						card+='<div class="eqLogicDisplayCard cursor eql'+result.id+'" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
						card+= "<center>";
						card+= '<span><i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;float: center;"></i></span>';
						card+= '<br>';
						card+= '<span style="font-size : 0.8em;">';
						card+= '{{Eclairage}}';
						card+= '</span>';
						card+= "<span style='font-size : 1.1em;position:relative; top : 15px;white-space: pre-wrap;word-wrap: break-word;'><center>"+result.humanName+"</center></span>";
						card+='</div>';
						card+='</div>';
						$('.membersCard').append(card);
						$('.eql'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
						$('.eqlremove'+result.id).click(function() {
							//$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)
							console.log("ok");
						});
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

