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
	console.log(e);
	//parent.find('modal').prop("disabled",true);
//	deleteUser("ok");
});


function deleteUser($user){	
	var dialog_title = '{{Confirmation de suppression utilisateur RaspBEE}}';
   var dialog_message = '<form class="form-horizontal onsubmit="return false;"> ';
   //dialog_title = '{{Démarrer l\'inclusion}}';
   dialog_message += '<label class="control-label" > {{Veuillez confirmer la suppression de l\'utilisateur}} </label> ' + '<br>' + '<label class="lbl lbl-warning" for="name">{{Attention, une fois supprimé, il le sera définitvement.}}</label> ';
   dialog_message += '</form>';
   var user ="test";
   bootbox.dialog({
	   title: dialog_title,
	   message: dialog_message,
	   //modal: false,
	   buttons: {
		   "{{Annuler}}": {
			  // className: "btn-success",
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
						user: user,
					},
					dataType: 'json',
					error: function (request, status, error) {
								$('#div_networkRaspBEEAlert').showAlert({message: error.message, level: 'danger'});
								handleAjaxError(request, status, error);
								
								
							},
					success: function (data) {								
								if (data.state != 'ok') {
									$('#div_networkRaspBEEAlert').showAlert({message: data.result, level: 'info'});
									return;
								}
	
							} 
						});
				   
				   
				//   parent.find('modal').prop("disabled",false);
				/*jeedom.openzwave.controller.addNodeToNetwork({
					secure : $("input[name='secure']:checked").val(),
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function (data) {
						$('#div_alert').showAlert({message: '{{Action réalisée avec succès}}', level: 'success'});
					}
				});*/
			}
		},
	}
});
}
