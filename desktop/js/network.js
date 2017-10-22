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

$('.deleteRaspBeeUser').on( "click", function(e) {
	//console.log(e);
	var row = $(this).closest("tr")[0].id;
	deleteUser(e,$(this).closest("tr")[0].id);
});

$('.showDebugInfoBTN').on( "click", function(e) {
	//console.dir(e.currentTarget.attributes.id.nodeValue);
	switch(e.currentTarget.attributes.id.value){
	case 'showdebugsensors':
		showDebug('getRaspBEESensors');
		break;
	case 'showdebuglights':
		showDebug('getRaspBEELights');
		break;
	case 'showdebuggroups':
		showDebug('getRaspBEEGroups');
		break;
	}
});


function deleteUser(item,row){	
	var dialog_title = '{{Confirmation de suppression utilisateur RaspBEE}}';
	var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
	dialog_message += '<label class="control-label" > {{Veuillez confirmer la suppression de l\'utilisateur suivant}} :</label><br><br>{{Nom}} : '+item.currentTarget['name']+'<br>{{Clé}} : ('+item.currentTarget['id']+')'+' ' + '<br><br>' + '<label class="lbl lbl-warning" for="name">{{Attention, une fois supprimé, il le sera définitivement.}}</label> ';
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
					action: "deleteRaspBEEUser",
					user: item.currentTarget['id'],
				},
				dataType: 'json',
				error: function (resp, status, error) {
					$('#div_networkRaspBEEAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});
				},
				success: function (resp) {
					console.dir('deleteuser network.js: ',resp);
					try
					{
					   var cleanResp = JSON.parse(resp.result.replace('\"', '"'));
					}
					catch(e)
					{
					   var cleanResp='invalid json';
					}							
					if (resp.state == 'ok') {
						$('#div_networkRaspBEEAlert').showAlert({message: '{{Utilisateur supprimé}}: '+cleanResp[0].success, level: 'success'});
						$('#'+row).remove();
					} else{
						console.dir("cleanresp",resp);
						$('#div_networkRaspBEEAlert').showAlert({message: '{{Impossible de supprimer l\'utilisateur}} : '+HTMLClean(resp.result), level: 'danger'});
					}
				} 
				});					
			}
		}
		}
	});
}

function HTMLClean(value){
	return value.replace(/<\/?[^>]+(>|$)/g, "");
}

function showDebug(action){	
$.ajax({
	type: "POST", 
	url: "plugins/RaspBEE/core/ajax/RaspBEE.ajax.php", 
	data: {
	action: action,
			},
	dataType: 'json',
	error: function (resp, status, error) {
				$('#div_networkRaspBEEAlert').showAlert({message: '{{Erreur}} : '+error+' ('+resp.status+')', level: 'danger'});
				//handleAjaxError(request, status, error);
			},
	success: function (resp) {		
		try
			{
			var cleanResp = resp.result.message.replace('\"', '"');
			   console.dir(resp.result.message);
			}
			catch(e)
			{
			   var cleanResp='invalid json';
			}							
			if (resp.state == 'ok') {
				var dialog_title = '{{Affichage debug RaspBEE en mode raw}}';
				var dialog_message = '<label class="control-label" > {{infos debug}}</label><br><textarea rows="15" cols="70">'+resp.result.message+'</textarea>';
				bootbox.dialog({
					title: dialog_title,
					message: dialog_message,
					buttons: {"{{Fermer}}": {
							callback: function () {}
						}
					}
				});

			} else{
				$('#div_networkRaspBEEAlert').showAlert({message: '{{Impossible d\'afficher les infos}} : '+HTMLClean(resp.result), level: 'danger'});
			}
	} 
});















}
