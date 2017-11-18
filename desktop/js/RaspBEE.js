
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

/*$('#bt_RaspBEEHealth').on('click', function () {
	$('#md_modal').dialog({title: "{{Santé RaspBEE}}"});
	$('#md_modal').load('index.php?v=d&plugin=RaspBEE&modal=health').dialog('open');
});*/

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
	$('#table_cmd tbody').append(commandDraw(_cmd));
	$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
	if (isset(_cmd.type)) {
		$('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
	}
	jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

function addGroupsToMember(_eqLogic){	
	var dialog_title = "{{Inclure}} "+_eqLogic.name+" dans des groupes";
	var dialog_message = '<center><form> ';
	dialog_message += '<select size="10" multiple name="groupsList" id="groupsList"  style="width: 300px; text-align:center;">';
	dialog_message += '</select>';
	dialog_message += '</form></center>';
	bootbox.dialog({
		title: dialog_title,
		message: dialog_message,
		buttons: {
			"{{Annuler}}": {
				callback: function () {
				$('#div_raspbeeAlert').showAlert({message: "{{Ajout au groupe annulé}}", level: 'info'});
				}
			},
			success: {
				label: "{{Inclure à la selection}}",
				className: "btn-success",
				callback: function () {		
					//console.log($("#groupName").val())
					if (!$('#membersField').val()) $('#membersField').val("[]");
					
					var actualGroups = JSON.parse($('#membersField').val());
					if (!actualGroups) actualGroups = [];
					var groupsToAdd = $("#groupsList").val();
					console.dir("oldgroups",actualGroups);
					console.dir("groupsToAdd",groupsToAdd);
					groupsToAdd.forEach(function(groupToAdd)
					{
						console.dir("groupToAdd",groupToAdd);
						var index = actualGroups.indexOf(groupToAdd);
						if(index == -1){
						console.dir("ajout",groupToAdd);
						actualGroups.push(groupToAdd);
						};
					
					});
					
					$('#membersField').val(JSON.stringify(actualGroups))
					updateGroupsEqLogic(_eqLogic,$('#membersField').val());
				}
			}
		}
	}).on("shown.bs.modal", function(e) {
		jeedom.raspbee.eqLogic.getAll({
			error: function(error){
				console.dir("THE error refreshEqlogicsList "+error);
			},
			success:function (result){
				if (result!=undefined){			
					//console.dir("result eqlogics filtré",JSON.parse(result));
					resultArray=JSON.parse(result);
					var objects="";
					resultArray.forEach(function(element) {
						//var position = element.type.indexOf("light");
						if (element.type==='LightGroup'){
							//console.dir(element);
							var o = new Option("option text", element.origId);
							$(o).html(element.humanName);
							$("#groupsList").append(o);
						}
					})
				}			
			}		
		});
	});		
}


function addMemberToGroup(_eqLogic){	
	var dialog_title = "{{Ajout de membres au groupe}}: "+_eqLogic.name;
	var dialog_message = '<center><form> ';
	dialog_message += '<select size="10" multiple name="lightsList" id="lightsList"  style="width: 300px; text-align:center;">';
	dialog_message += '</select>';
	dialog_message += '</form></center>';
	bootbox.dialog({
		title: dialog_title,
		message: dialog_message,
		buttons: {
			"{{Annuler}}": {
				callback: function () {
				$('#div_raspbeeAlert').showAlert({message: "{{Ajout au groupe annulé}}", level: 'info'});
				}
			},
			success: {
				label: "{{Ajouter la selection au groupe}}",
				className: "btn-success",
				callback: function () {		
					//console.log($("#groupName").val())
					if (!$('#membersField').val()) $('#membersField').val("[]");
					
					var actualMembers = JSON.parse($('#membersField').val());
					if (!actualMembers) actualMembers = [];
					var membersToAdd = $("#lightsList").val();
					//console.dir("oldMembers",actualMembers);
					//console.dir("membersToAdd",membersToAdd);
					membersToAdd.forEach(function(memberToAdd)
					{
						//console.dir("memberToAdd",memberToAdd);
						var index = actualMembers.indexOf(memberToAdd);
						if(index == -1){
						//console.dir("ajout",memberToAdd);
						actualMembers.push(memberToAdd);
						};
					
					});
					
					$('#membersField').val(JSON.stringify(actualMembers))
					updateMembersEqLogic(_eqLogic,$('#membersField').val());
				}
			}
		}
	}).on("shown.bs.modal", function(e) {
		jeedom.raspbee.eqLogic.getAll({
			error: function(error){
				console.dir("THE error refreshEqlogicsList "+error);
			},
			success:function (result){
				if (result!=undefined){			
					//console.dir("result eqlogics filtré",JSON.parse(result));
					resultArray=JSON.parse(result);
					var objects="";
					resultArray.forEach(function(element) {
						var position = element.type.indexOf("light");
						if (position!==-1 && element.type!=='LightGroup'){
							//console.dir(element);
							var o = new Option("option text", element.origId);
							$(o).html(element.humanName);
							$("#lightsList").append(o);
						}

					})
				}			
			}		
		});
		//var actualMembers = JSON.parse($('#membersField').val());
		//var memberEql=jeedom.eqLogic.byId({id:_eqLogic.id}) 
		//console.dir(actualMembers);
		//$("#eqLogic_Remove").html(eqLogic.name);
		//$("#groupName_Remove").html(group.name);
		});		
	
	
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
	// on ne peut pas supprimer les groupes qui ont un ctrl maitre
	if(_eqLogic.configuration.type=="LightGroup" && ("devicemembership" in _eqLogic.configuration)){
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
	}
	if (("devicemembership" in _eqLogic.configuration))
	printMasterEqLogic(_eqLogic);
	else
	$('#masterEqLogic').empty();
	if (("lights" in _eqLogic.configuration) && (_eqLogic.configuration.type).indexOf('LightGroup')!=-1 )
	printMembersEqLogic(_eqLogic);
	else
		$('#membersEqLogic').empty();
	
	if ((_eqLogic.configuration.type).indexOf('light')!=-1){
		printGroupsEqLogic(_eqLogic);
	}
	else 
		$('#groupsEqLogic').empty();
	
}


function printGroupEqlogic(id,_eqLogic){	
	//console.dir("result humanNameById ",id );	
	jeedom.raspbee.eqLogic.humanNameById({
		id: id,
		error: function(error){
			if (error) $('#div_raspbeeAlert').showAlert({message: error.message, level: 'danger'});
		},
		success:function (result){
			console.dir("pringGroupEqlogic result",result);
			if (result!==undefined){			
				$('#groupsCard').append(groupDraw(result,id));
				//console.dir("result",result.origid);
				var groups = [];
				var value = $('#membersField').val()
				if (value.length>0){
					groups = JSON.parse(value);
				}	
				groups.push(result.origid);
				$('#membersField').val(JSON.stringify(groups));
				$('.eqlgroup'+id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+id)});
				console.log("print eqlgroupremove id ",id);
				$('.eqlgroupremove'+id).click(function() {
					var id = result.id;
					console.log("eqlgroupremove click",result.id);
						jeedom.raspbee.eqLogic.getById({
							id: result.id,
							error: function(error){
								console.dir("THE error refreshEqlogicsList ",error);
							},
							success:function (data){
							removeGroupFromLight(data,_eqLogic);	
							}			
						});
				});
			}			
		}				
	})
}

function printGroupsEqLogic(_eqLogic){
	$('#groupsEqLogic').empty();	
	var master ="";
	master+='<legend><i class="fa fa-circle-o"></i> {{Groupe(s)}}';
	master+='<a title="{{Inclure cet éclairage dans un groupe}}" class="btn btn-success" id="bt_addGroupToMember" style="margin-left: 5px;"><i class="fa fa-plus-circle"></i></a></legend>';
	master+='<div id="groupsCard" style="display: flex;">';	
			
	$('#groupsEqLogic').append(master);
	$('#bt_addGroupToMember').on('click', function () {
		addGroupsToMember(_eqLogic);
	});
	
	//console.dir("printGroupsEqLogic",_eqLogic.configuration["origid"]);
	var origId = _eqLogic.configuration["origid"];
	//console.log("origid: ",origId);
	jeedom.raspbee.eqLogic.getOwnersGroups({
		origId: origId,
		error: function(error){
			if (error) $('#div_raspbeeAlert').showAlert({message: error.message, level: 'danger'});
		},
		success:function (groupResult){
			//console.dir("pringroup",groupResult);
			if (groupResult!==undefined){
				//var groupsArray = [];
				for (var i=0;i<groupResult.length;i++){
					printGroupEqlogic(groupResult[i],_eqLogic);
					//console.dir("groupResult",groupResult[i].origid);
				}
			}
		}				
	})
	master+="</div>";	
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
						$('.eqlmaster'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
					}			
				}
			})
		}
		master+="</div>";	
		$('#masterEqLogic').append(master);	
	}}

function printMembersEqLogic(_eqLogic){
	$('#membersEqLogic').empty();
	//console.dir(_eqLogic.configuration.lights);
	var master ="";
	master+='<legend><i class="fa fa-table"></i> {{Membres du groupe}}';
	master+='<a title="{{Ajouter un éclairage au groupe}}" class="btn btn-success" id="bt_addMember" style="margin-left: 5px;"><i class="fa fa-plus-circle"></i></a></legend>';
	master+='<div id="membersCard" style="display: flex;">';		
	if (_eqLogic.configuration.lights){
		var lights=JSON.parse(_eqLogic.configuration.lights);
		if (!is_null(lights)){
			for(var i= 0; i < lights.length; i++){
				console.dir("boucle");
				jeedom.raspbee.eqLogic.humanNameByOrigIdAndType({
					origId:lights[i],
					type: "light",
					success:function (result){
						if (typeof result !== 'undefined'){			
							$('#membersCard').append(memberDraw(result,_eqLogic.configuration.origid));
							$('.eqlmember'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
							$('.eqlmemberremove'+result.id).click(function() {
								console.dir('eqlmemberremove',result.id);
								var id = result.id;
								jeedom.raspbee.eqLogic.getById({
									id:result.id,
									success:function (data){
										console.dir('success',data);
										console.dir('id',result.id);
										removeFromGroup(data,_eqLogic);
									}		
								});
							});
						}
					}		
				});
			}			
		}
	}
	master+="</div>";
	$('#membersEqLogic').append(master);
	$('#bt_addMember').on('click', function () {
		addMemberToGroup(_eqLogic);
	});
}


function refreshEqlogicsList(){
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
				objects+=eqLogicDraw(element);
				})
				$("#eqLogicThumbnailContainment").append(objects);
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

function removeFromGroup(_eqLogic,group){
	var dialog_title = '{{Retrait d\'un équipement d\'un groupe}}.';
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '{{Veuillez confirmer le retrait de}} <b><span id="eqLogic_Remove"></span></b> {{du groupe}} <b><span id="groupName_Remove"></span></b>.';
	dialog_message += '</form>';
	if (typeof _eqLogic !== 'undefined' &&  typeof group !== 'undefined')
		getEqlogic({
			id:group.id,
			callback:function(data){			
				bootbox.dialog({
					title: dialog_title,
					message: dialog_message,
					//size: 'small',
					buttons: {
						"{{Annuler}}": {
							callback: function () {
							$('#div_raspbeeAlert').showAlert({message: "{{Retrait de}} "+_eqLogic.name+" {{annulé}}", level: 'info'});
							}
						},
						success: {
							label: "{{Retirer du groupe}}",
							className: "btn-warning",
							callback: function () {
								$('#eqlmember'+_eqLogic.id).unbind();
								$('#eqlmember'+_eqLogic.id).remove();
								removeFromGroupStep2(_eqLogic,_eqLogic.id,_eqLogic.origid,group.configuration.origid);
							}
						}
					}
				}).on("shown.bs.modal", function(e) {
					$("#eqLogic_Remove").html(_eqLogic.name);
					$("#groupName_Remove").html(group.name);
					});	
			}
		});	


}

function removeFromGroupStep2(_eqLogic,eqLogicId,deviceId,groupId){
	console.log("removeFromGroupStep2",deviceId,groupId);
	var newTab = $('#membersEqLogic').html().match(/eqlorigid\d+/g);
	var value = "";
	if (newTab){
	// on ne garde que le nombre (qui est egal à l'id)
	for (var i=0; i<newTab.length;i++) {
		newTab[i] = newTab[i].replace('eqlorigid', "");	
	}
	//console.dir("newtab after",JSON.stringify(newTab));
	value = JSON.stringify(newTab);
	}
	$('#membersField').val(value);
}

function removeGroupFromLight(_eqLogic,group){
	var dialog_title = '{{Retrait d\'un équipement d\'un groupe}}.';
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '{{Veuillez confirmer le retrait de}} <b><span id="eqLogic_Remove"></span></b> {{du groupe}} <b><span id="groupName_Remove"></span></b>.';
	dialog_message += '</form>';
	if (typeof _eqLogic !== 'undefined' &&  typeof group !== 'undefined')
		getEqlogic({
			id:group.id,
			callback:function(data){			
				bootbox.dialog({
					title: dialog_title,
					message: dialog_message,
					//size: 'small',
					buttons: {
						"{{Annuler}}": {
							callback: function () {
							$('#div_raspbeeAlert').showAlert({message: "{{Retrait de}} "+_eqLogic.name+" {{annulé}}", level: 'info'});
							}
						},
						success: {
							label: "{{Retirer du groupe}}",
							className: "btn-warning",
							callback: function () {
								$('#eqlgroup'+_eqLogic.id).unbind();
								//$('#eqlgroupremove'+_eqLogic.id).unbind();
								$('#eqlgroup'+_eqLogic.id).remove();
								removeGroupFromLightStep2(_eqLogic,_eqLogic.id,_eqLogic.origid,group.configuration.origid);
							}
						}
					}
				}).on("shown.bs.modal", function(e) {
					$("#eqLogic_Remove").html(group.name);
					$("#groupName_Remove").html(_eqLogic.name);
					});	
			}
		});	


}

function removeGroupFromLightStep2(_eqLogic,eqLogicId,deviceId,groupId){
	console.log("removeGroupFromLightStep2",deviceId,groupId);
	var newTab = $('#groupsEqLogic').html().match(/eqlgrouporigid\d+/g);
	var value = "";
	if (newTab){
	// on ne garde que le nombre (qui est egal à l'id)
	for (var i=0; i<newTab.length;i++) {
		newTab[i] = newTab[i].replace('eqlgrouporigid', "");	
	}
	console.dir("newtab",newTab);
	//console.dir("newtab after",JSON.stringify(newTab));
	value = JSON.stringify(newTab);
	}
	$('#membersField').val(value);
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
						createEqLogic(devices[device],syncType);
					}
				}	

			} else{
				$('#div_raspbeeAlert').showAlert({message: '{{Impossible d\'afficher les infos}} : '+HTMLClean(resp.result), level: 'danger'});
			}
		} 
	});
}

function updateGroupsEqLogic(_eqLogic,groups){	
	console.dir("updateGroupsEqLogic",groups);
	$('#groupsCard').empty();	
	var groups=JSON.parse(groups);
	if (!is_null(groups)){
		var master ="";
		for(var i= 0; i < groups.length; i++){
			console.dir("boucle",groups[i]);
			jeedom.raspbee.eqLogic.humanNameByOrigIdAndType({
			origId:groups[i],
			type: 'light',
			success:function (result){
				console.dir("success",result);
					if (typeof result !== 'undefined'){			
						$('#groupsCard').append(groupDraw(result,_eqLogic.configuration.origid));
						$('.eqlgroup'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
						console.log("update eqlgroupremove id ",result.id);
						$('.eqlgroupremove'+result.id).off();
						$('.eqlgroupremove'+result.id).click(function() {
							console.log("click");
							var id = result.id;
							jeedom.raspbee.eqLogic.getById({
								id: result.id,
								error: function(error){
									console.dir("THE error refreshEqlogicsList ",error);
								},
								success:function (data){
								removeGroupFromLight(data,_eqLogic);	
								}			
							});
						});
					}
				}		
			});
		}
	}
}

function updateMembersEqLogic(_eqLogic,members){
	$('#membersCard').empty();	
	var lights=JSON.parse(members);
	if (!is_null(lights)){

		var master ="";

		for(var i= 0; i < lights.length; i++){
			jeedom.raspbee.eqLogic.humanNameByOrigIdAndType({
			origId:lights[i],
			type: "light",
			success:function (result){
					if (typeof result !== 'undefined'){			
						$('#membersCard').append(memberDraw(result,_eqLogic.configuration.origid));
						$('.eqlmember'+result.id).click(function() {$( location ).attr('href',"/index.php?v=d&m=RaspBEE&p=RaspBEE&id="+result.id)});
						$('.eqlmemberremove'+result.id).click(function() {
							var id = result.id;
							getEqlogic({
								id:id,
								callback:function(data){
									removeFromGroup(data,_eqLogic);
								}
							});
						});
					}
				}		
			});
		}
	}
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