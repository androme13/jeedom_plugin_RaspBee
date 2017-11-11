
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
//refreshEqlogicsList();
/*$(".eqLogicDisplayCard").draggable({
	containment : '#eqLogicThumbnailContainment'
});*/
//refreshEqlogicsList();
/*$('#bt_include').on('click', function () {
	$('#md_modal').dialog({
		title: "{{Mode inclusion}}",
		dialogClass: "no-close",
		// on cache le bouton fermer
		open: function(event, ui) { jQuery('.ui-dialog-titlebar-close').hide(); },
		buttons: [{
			text: "{{Fermer}}",
			click: function() {
				window.location.reload();
			}
		}],
	});
	$('#md_modal').load('index.php?v=d&plugin=RaspBEE&modal=include').dialog('open');	
});*/

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
		}],
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

$('#specialEqLogicSave').on('click', function () {
	specialEqLogicSave();	
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
	$('#table_cmd tbody').append(commandDraw(_cmd));
	$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
	if (isset(_cmd.type)) {
		$('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
	}
	jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}


function createGroup(){
	var dialog_title = "{{Création d'un groupe}}";
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '{{Veuillez saisir le nom du groupe à créer}}.<br><label for="text-3">Nom du groupe: </label><br><input data-clear-btn="true" name="text-3" id="groupName" value="" type="text"><br><br><label class="lbl lbl-warning" for="name">{{Attention, une fois le groupe crée, une synchronisation de celui-ci débutera}}.</label>';
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
							//console.dir(error);
							$('#div_raspbeeAlert').showAlert({message: error.message, level: 'danger'});
						},
						success: function (data) { 
							if (data.state != 'ok') {
								//console.dir(data);
								$('#div_raspbeeAlert').showAlert({message: data.result, level: 'danger'});
							}else
							{
								//console.dir(data);
								
								syncDevices('getRaspBEEGroups','basic');
								//window.location.reload();
								//$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE");
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
		//$('#specialEqLogicSave').show();
		//$('#eqLogicSave').hide();
		if (_eqLogic.configuration.devicemembership!="null"){
			$('#div_removeGeneric').hide();			
			$('#buttons_infoseqlogic').append('<a class="label label-info" style="margin-bottom:20px;"><i class="fa fa-info-circle"></i> {{Ce groupe ne peut pas être supprimé car il appartient à un contrôleur}}.</a>');
		}
		else
		{
			$('#div_removeGeneric').show();
		}
	}
	else
	{
		$('#div_removeGeneric').show();
		//$('#specialEqLogicSave').hide();
		//$('#eqLogicSave').show();
	}
	if (("devicemembership" in _eqLogic.configuration))
	printMasterEqLogic(_eqLogic);
	else
	$('#masterEqLogic').empty();
	if (("lights" in _eqLogic.configuration))
	printMembersEqLogic(_eqLogic);
	else
		$('#membersEqLogic').empty();
	if ((_eqLogic.configuration.type).indexOf('light')!=-1){
		printGroupsEqLogic(_eqLogic);
	}
	else 
		$('#groupsEqLogic').empty();
	
}


function pringGroupEqlogic(id){	
	//console.dir("result humanNameById ",id );
		
	jeedom.raspbee.eqLogic.humanNameById({
		id: id,
		error: function(error){
			if (error) $('#div_raspbeeAlert').showAlert({message: error.message, level: 'danger'});
		},
		success:function (result){
			//console.dir("pringGroupEqlogic result",result);
			if (result!==undefined){			
				$('.groupsCard').append(groupDraw(id,result));
				$('.eqlg'+id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+id)});
			}			
		}				
	})
}

function printGroupsEqLogic(_eqLogic){
	$('#groupsEqLogic').empty();	
	var master ="";
	master+='<legend><i class="fa fa-circle-o"></i> {{Groupe(s)}}</legend>'
	master+='<div class="groupsCard" style="display: flex;">';	
	master+="</div>";		
	$('#groupsEqLogic').append(master);
	var origId = _eqLogic.configuration["origid"];
	//console.log("origid: ",origId);
	jeedom.raspbee.eqLogic.getOwnersGroups({
		origId: origId,
		error: function(error){
			if (error) $('#div_raspbeeAlert').showAlert({message: error.message, level: 'danger'});
		},
		success:function (groupResult){
			if (groupResult!==undefined){
				for (var i=0;i<groupResult.length;i++){
					pringGroupEqlogic(groupResult[i]);
				}
			}
		}				
	})
	
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
					//console.log("THE error printMasterEqLogic "+_eqLogic.name);	
					//console.log("THE error printMasterEqLogic devicemembership[i] "+devicemembership[i]);
				},
				success:function (result){
					if (result!=undefined){						
						$('.mastersCard').append(masterDraw(result));
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
					//console.dir("THE error printMemberEqLogic ",_eqLogic);	
					//console.log("THE error printMemberEqLogic light[i] "+lights[i]);

			},
			success:function (result){
					if (typeof result !== 'undefined'){			
						$('.membersCard').append(memberDraw(result,_eqLogic.configuration.id));
						$('.eql'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
						$('.eqlremove'+result.id).click(function() {
							getEqlogic({
								id:result.id,
								callback:function(data){
									removeFromGroup(data,_eqLogic);
								}
							});
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
	console.dir("refreshEqlogicsList");
	//console.dir(jeedom.raspbee);
	jeedom.raspbee.eqLogic.getAll({
		error: function(error){
			console.dir("THE error refreshEqlogicsList "+error);
		},
		success:function (result){
			if (result!=undefined){			
				console.dir("result eqlogics filtré",JSON.parse(result));
				resultArray=JSON.parse(result);
				var objects="";
				resultArray.forEach(function(element) {
				//var card = JSON.parse(element);
				objects+=eqLogicDraw(element);
				//$("#eqLogicThumbnailContainment").append(eqLogicDraw(element));
				//$("#testdiv").append(eqLogicDraw(element));
				//$("#testdiv").append(eqLogicDraw(element));	
				})
				$("#eqLogicThumbnailContainment").append(objects);
				//var card = JSON.parse(result);
				
				//$("#testdiv").html(eqLogicDraw(card)[0]);
			}			
		}		
	});
}

function getEqlogic(_params){	
	jeedom.eqLogic.byId({
		id:_params.id,
		success:function (data){
			_params.callback(data);
		}		
	});
}

function removeFromGroup(eqLogic,group){
//console.dir("eqlogic",eqLogic);
	var dialog_title = '{{Retrait d\'un équipement d\'un groupe}}.';
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '{{Veuillez confirmer le retrait de}} <b><span id="eqLogic_Remove"></span></b> {{du groupe}} <b><span id="groupName_Remove"></span></b>.';
	//dialog_message +='<br><br><label class="lbl lbl-warning" for="name">{{Attention, une fois le groupe crée, une synchronisation limitée débutera}}.</label>';
	dialog_message += '</form>';
	if (typeof eqLogic !== 'undefined' &&  typeof group !== 'undefined')
		getEqlogic({
			id:group.id,
			callback:function(data){
				console.dir("removeFromGroup",data);				
				bootbox.dialog({
					title: dialog_title,
					message: dialog_message,
					//size: 'small',
					buttons: {
						"{{Annuler}}": {
							callback: function () {
							$('#div_raspbeeAlert').showAlert({message: "{{Retrait de}} "+eqLogic.name+" {{annulé}}", level: 'info'});
							}
						},
						success: {
							label: "{{Retirer du groupe}}",
							className: "btn-success",
							callback: function () {
								$('#eqlmember'+eqLogic.id).remove();
								removeFromGroupStep2(eqLogic.id,eqLogic.configuration.origid,group.configuration.origid);
							}
						}
					}
				}).on("shown.bs.modal", function(e) {
					$("#eqLogic_Remove").html(eqLogic.name);
					$("#groupName_Remove").html(group.name);
					});	
			}
		});	


}

function removeFromGroupStep2(eqLogicId,deviceId,groupId){
	console.log("removeFromGroupStep2",deviceId,groupId);
	var newTab = $('#membersEqLogic').html().match(/eql\d+/g);
	for (var i=0; i<newTab.length;i++) {
		newTab[i] = newTab[i].replace('eql', "");	
	}
	//newTab.forEach(function(element){
	//element.replace("eql", "");	
	//});
	//var newTab = $("#eqlmember").find();
	//var newTab=$("div:regex(class, /eql\d*/g)");
	//var newTab = $( "#membersEqLogic" ).find( ".eqLogicDisplayCard" );
	console.dir("newtab",JSON.stringify(newTab));
	
	
}


function AremoveFromGroup(eqLogic,group){
	//console.dir("eqlogic",eqLogic);
	var dialog_title = '{{Retrait d\'un équipement d\'un groupe}}.';
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '{{Veuillez confirmer le retrait de}} <b><span id="eqLogic_Remove"></span></b> {{du groupe}} <b><span id="groupName_Remove"></span></b>.';
	//dialog_message +='<br><br><label class="lbl lbl-warning" for="name">{{Attention, une fois le groupe crée, une synchronisation limitée débutera}}.</label>';
	dialog_message += '</form>';
	if (typeof eqLogic !== 'undefined' &&  typeof group !== 'undefined')
		getEqlogic({
			id:group.id,
			callback:function(data){
				console.dir("removeFromGroup",data);				
				bootbox.dialog({
					title: dialog_title,
					message: dialog_message,
					//size: 'small',
					buttons: {
						"{{Annuler}}": {
							callback: function () {
							$('#div_raspbeeAlert').showAlert({message: "{{Retrait de}} "+eqLogic.name+" {{annulé}}", level: 'info'});
							}
						},
						success: {
							label: "{{Retirer du groupe}}",
							className: "btn-success",
							callback: function () {
								removeFromGroupStep2(eqLogic.id,eqLogic.configuration.origid,group.configuration.origid);
							}
						}
					}
				}).on("shown.bs.modal", function(e) {
					$("#eqLogic_Remove").html(eqLogic.name);
					$("#groupName_Remove").html(group.name);
					});	
			}
		});	
}

function AremoveFromGroupStep2(eqLogicId,deviceId,groupId){
	console.log("removeFromGroupStep2",deviceId,groupId);
	
	jeedom.raspbee.eqLogic.removeFromGroup({
		deviceId:deviceId,
		groupId:groupId,
		success:function (data){
			//$('#eqlmember'+eqLogicId).empty();
			$('#eqlmember'+eqLogicId).remove();
			console.dir("removeFromGroup",data);
			console.dir("jeedom",jeedom.eqLogic);
			
			jeedom.eqLogic.save({
		//deviceId:deviceId,
		//groupId:groupId,
		success:function (data){
			console.dir("data",data);
			//console.dir("jeedom",jeedom.eqLogic);
			
		}		
	});			
		}		
	});
	
}

/*function removeFromGroupStep3(eqLogicId,deviceId,groupId){
	console.log("removeFromGroupStep2",deviceId,groupId);
	
	jeedom.raspbee.eqLogic.removeFromGroup({
		deviceId:deviceId,
		groupId:groupId,
		success:function (data){
			//$('#eqlmember'+eqLogicId).empty();
			$('#eqlmember'+eqLogicId).remove();
			console.dir("removeFromGroup",data);
			console.dir("jeedom",jeedom.eqLogic);
			
			jeedom.eqLogic.save({
		//deviceId:deviceId,
		//groupId:groupId,
		success:function (data){
			console.dir("data",data);
			//console.dir("jeedom",jeedom.eqLogic);
			
		}		
	});			
		}		
	});
	
}*/

function specialEqLogicSave(eqLogic){
	console.log ("sauvegarde eqlogix pecial");
	
	jeedom.raspbee.eqLogic.setGroupMembers({
		deviceId:deviceId,
		groupId:groupId,
		success:function (data){
			//$('#eqlmember'+eqLogicId).empty();
			$('#eqlmember'+eqLogicId).remove();
			console.dir("removeFromGroup",data);
			console.dir("jeedom",jeedom.eqLogic);
			
			jeedom.eqLogic.save({
		//deviceId:deviceId,
		//groupId:groupId,
		success:function (data){
			console.dir("data",data);
			//console.dir("jeedom",jeedom.eqLogic);
			
		}		
	});			
		}		
	});
	
}

function syncDevices(action,syncType){
	$.ajax({
		type: "POST", 
		url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
		data: {
			action: action
		},
		dataType: 'json',
		error: function (resp, status, error) {
			$('#div_raspbeeAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});		
		},		
		success: function (resp) {
		//console.dir(resp);
		try
			{
			var cleanResp = resp.result.replace('\"', '"');
			}
			catch(e)
			{
			   var cleanResp='invalid json';
			}							
			if (resp.state == 'ok') {
				let devices = JSON.parse(cleanResp);
				if (Object.keys(devices).length>0){
					for (var device in devices) {
						devices[device].origid=device;
						//console.dir(devices[device]);
						createEqLogic(devices[device],syncType);
					}
				}	

			} else{
				$('#div_raspbeeAlert').showAlert({message: '{{Impossible d\'afficher les infos}} : '+HTMLClean(resp.result), level: 'danger'});
			}
		} 
	});
}

function createEqLogic(device,syncType){
	$.ajax({
		type: "POST", 
		url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
		data: {
			action: "createEqLogic",
			device: device,
			syncType: syncType
		},
		dataType: 'json',
		error: function (resp, status, error) {
			$('#div_raspbeeAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});	
		},
		
		success: function (resp) {		
			try	{
				
					var cleanResp = resp.result.replace('\"', '"');
					console.dir(cleanResp);
					var jsonResp = JSON.parse(cleanResp);
				   
				}
				catch(e)
				{
				   var jsonResp=new Object();
				   jsonResp.cmdError = 3;
				}							
				if (jsonResp.cmdError == 3) {
					$('#div_raspbeeAlert').showAlert({message: "{{erreur inconnue}} ("+cleanResp+')', level: 'danger'});					
				} else{
					switch(jsonResp.cmdError){
						case 2:
							$('#div_raspbeeAlert').showAlert({message: "{{Le groupe est crée avec succès}}", level: 'info'});
							break;									
					}					
				}
			window.location.reload();				
		}
	});	
};


/*function arraySearch(arr,val) {
	for (var i=0; i<arr.length; i++)
		if (arr[i] === val)                    
			return i;
	return false;
}*/

