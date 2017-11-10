
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

function commandDraw(_cmd){
	console.dir("cmd",_cmd);
	var command = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	command += '<td class="expertModeVisible">';
	command += '<span class="cmdAttr" data-l1key="id" style="" placeholder="#"></span>';
	command += '</td>';
	command += '<td>';
	command += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}" readonly>';
	command += '</td>';
	command += '<td>';
	command += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
	command += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
	if (_cmd.subType==='binary')
		command += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="configuration" data-l2key="isReversed"/>{{Inverser}}</label></span> ';
	command += '</td>';
	command += '<td>';
	if (is_numeric(_cmd.id)) {
		command += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
		command += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
	}
	//tr +=_cmd.configuration.fieldname;
	command += '</td>';
	return command;
}

function eqLogicDraw(eqLogic){
	opacity="";
	icon="";
	card="";
	type="";
	card= '<div class="eqLogicDisplayCard eqLogicHoverEffect cursor" data-eqLogic_id="'+ eqLogic.id+'" data-logical-id="' + eqLogic.logicalId+'"  style="background-color : #ffffff; height : 200px;box-shadow: 3px 3px 8px #000;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;"' + opacity + '" >';
	card+="<center>";
	
	switch (eqLogic.type){
	
	case "ZHASwitch" :
		type='{{Commande}}';
		icon='<i class="fa fa-th-large" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "ZHATemperature" :
		type='{{Capteur de température}}';
		icon= '<i class="jeedom jeedom-thermometre" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "ZHAHumidity" :
		type='{{Capteur d\'humidité}}';
		icon= '<i class="jeedom2 jeedom2-plante_eau2" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "ZHAPressure" :
		type='{{Capteur de pression}}';
		icon= '<i class="meteo meteo-nuage-soleil-pluie" style="font-size :6em;color:#767676;"></i>';
		break;
	case "Color light" :
	case "Dimmable light" :
	case "Extended color light" :
		type='{{Eclairage}}';
		icon= '<i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;"></i>';
		break;

	case "LightGroup" :
		type='{{Groupe}}';
		icon= '<i class="fa fa-circle-o" style="font-size : 6em;color:#767676;"></i>';
		break;	
	default:
		type='{{Inconnu}}';
		icon= '<i class="fa fa-question-circle" style="font-size : 6em;color:#767676;"></i>';
	}
	card+=icon;
	card+="<br>";
	card+='<span style="font-size : 0.8em;">';
	card+=type;
	card+="</span>";		
	card+="</center>";
	card+='<span style="font-size : 1.1em;position:relative; top : 15px;white-space: pre-wrap;word-wrap: normal;"><center>' + eqLogic.humanName+ '</center>';
	card+="</div>";
	
	return card;
}

function groupDraw(id,group){
	var card = "";
	card+='<div style="position: relative;">';				
	card+='<div class="eqLogicDisplayCard cursor eqLogicHoverEffect eqlg'+id+'" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
	card+= "<center>";
	card+= '<i class="fa fa-circle-o" style="font-size : 6em;color:#767676;"></i>';
	card+= '<br>';
	card+= '<span style="font-size : 0.8em;">';
	card+= '{{Groupe}}';
	card+= '</span>';
	card+= "<span style='font-size : 1.1em;position:relative; top : 15px;white-space: pre-wrap;word-wrap: break-word;'><center>"+group+"</center></span>";
	card+='</div>';
	card+='<div class="eqlremove'+group.id+'" ownerGroup="'+id+'" style="margin:0;position:absolute;top: 3px;left: 140px;"><a id="bt_removeFromGroup" title="{{Retirer l\'équipement de ce groupe}}"><i class="fa fa-minus-circle" style="color:#c9302c;font-size : 2em;"></i></a></div>';
	card+='</div>';
	return card;
}

function masterDraw(master){
	var card = "";
	card+='<div class="eqLogicDisplayCard eqLogicHoverEffect cursor eql'+master.id+'" data-eqLogic_id="6" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
	card+= "<center>";
	card+= '<i class="fa fa-th-large" style="font-size : 6em;color:#767676;"></i>';
	card+= '<br>';
	card+= '<span style="font-size : 0.8em;">';
	card+= '{{Commande}}';
	card+= '</span>';
	card+= "<span style='font-size : 1.1em;position:relative; top : 15px;white-space: pre-wrap;word-wrap: break-word;'><center>"+master.humanName+"</center></span>";
	card+='</div>';				
	return card;
}

function memberDraw(member,id)
{
	var card = "";
	card+='<div style="position: relative;" id="eqlmember'+member.id+'">';
	card+='<div class="eqLogicDisplayCard eqLogicHoverEffect cursor eql'+member.id+'" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">';
	card+= "<center>";
	card+= '<span><i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;float: center;"></i></span>';
	card+= '<br>';
	card+= '<span style="font-size : 0.8em;">';
	card+= '{{Eclairage}}';
	card+= '</span>';
	card+= "<span style='font-size : 1.1em;position:relative; top : 15px;white-space: pre-wrap;word-wrap: break-word;'><center>"+member.humanName+"</center></span>";
	card+='</div>';
	card+='<div class="eqlremove'+member.id+'" ownerGroup="'+id+'" style="margin:0;position:absolute;top: 3px;left: 140px;"><a id="bt_removeFromGroup" title="{{Retirer cet équipement du groupe}}"><i class="fa fa-minus-circle" style="color:#c9302c;font-size : 2em;"></i></a></div>';
	card+='</div>';
	return card;
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

