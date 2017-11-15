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

$('#bt_addSelectedLights').on('click', function () {
	console.log("add members");
	//console.dir($("#lightsList").val());
	var actualMembers = JSON.parse($('#membersField').val());
	var membersToAdd = $("#lightsList").val();
	console.dir("oldMembers",actualMembers);
	console.dir("membersToAdd",membersToAdd);
	membersToAdd.forEach(function(memberToAdd)
	{
		console.dir("memberToAdd",memberToAdd);
		var index = actualMembers.indexOf(memberToAdd);
		if(index == -1){
		//console.dir("ajout",memberToAdd);
		actualMembers.push(memberToAdd);
		};
	
	});
	$('#membersField').val(JSON.stringify(actualMembers))
	//console.dir("newMembers",actualMembers);
});


