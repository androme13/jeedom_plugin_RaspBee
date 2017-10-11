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
					error: function (request, status, error) {
								$('#div_networkRaspBEEAlert').showAlert({message: error.message, level: 'danger'});
								handleAjaxError(request, status, error);
							},
					success: function (data) {
						console.dir("réponse:",data);
								if (data.state != 'ok') {
									$('#div_networkRaspBEEAlert').showAlert({message: data.message, level: 'danger'});
								} else
								{									
									$('#div_networkRaspBEEAlert').showAlert({message: "{{Utilisateur supprimé}} ("+data.result[0].success+")", level: 'success'});
									$('#'+row).remove();
								}
							} 
						});
				
			}
		},
	}
});
}
