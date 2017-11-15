<?php
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
 
if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';	
?>
<div id='div_addMemberAlert' style="display: none;"></div>
<center>
<div class="col-sm-4" style="overflow: hidden;height:inherit;">
<form class="form-vertical" >
		<div class="panel-group" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a>{{Eclairages disponibles}}</a>
					</h4>
				</div>
<?php
	$lights = eqLogic::byType("RaspBEE"); 
	echo('<select multiple name="lightsList" id="lightsList">');
	foreach ($lights as $light) {
		$type = $light->getConfiguration("type");
		if(strstr($type, strtolower("light")) && $type!=="LightGroup") {
           echo('<option value='.$light->getConfiguration("origid").'>'.$light->getHumanName().'</option>');
		} 		
	}
	echo ('</select>');	
?>
			
			</div>
			<div class="panel panel-footer">			
				<a class="btn btn-info" id="bt_addSelectedLights">
				<i class="fa fa-plus-circle"></i> {{Ajouter la selection}}</a>
			</div>        
		</div> 	
	</form>
	</center>
	</div>
<?php include_file('desktop', 'addmember', 'js', 'RaspBEE');?>
