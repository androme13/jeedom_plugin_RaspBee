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
 
require_once dirname(__FILE__) . '/../../core/class/eqLogicOperate.class.php'; 

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}

require_once dirname(__FILE__) . '/../../core/class/RaspBEECom.class.php';
/*$raspbeecom = new RaspBEECom;
		$lights = json_decode($raspbeecom->getLights());
		$groups = json_decode($raspbeecom->getGroups());*/		
?>
<div id='div_includeAlert' style="display: none;"></div>
<div class="col-sm-4" style="overflow: hidden;height:inherit;">
<form class="form-vertical" >
		<div class="panel-group" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#collapseOptions">{{Options}}</a>
					</h4>
				</div>
				<div id="collapseOptions" class="panel-collapse collapse in">
					<ul class="list-group">
						<li class="list-group-item">
							<div>
								<h4 class="panel-title">
								{{Type d'équipement à inclure}}
								</h4>
								<input type="radio" id="optionType1" name="optionType" value="light" >
								<label for="optionType1">{{Un éclairage}}.</label><br>
								
								<input type="radio" id="optionType2" name="optionType" value="sensor" >
								<label for="optionType1">{{Autre (capteur, télécommande, etc ...)}}.</label><br>
							</div>
						</li>
					</ul>
					<div id="syncOptionsHelp" class="panel-footer"></div>
				</div>
			</div>
			<div class="panel panel-footer">			
				<a class="btn btn-info" id="bt_launchinclude">
				<i class="fa fa-sign-in fa-rotate-90"></i> {{Lancer l'inclusion}}</a>
			</div>        
		</div> 	
	</form>
	</div>
	<div class="panel-group col-sm-8" >
	<div class="panel panel-default">
		<div class="  panel-heading">
		<h4 class=" panel-title">
			<a">{{Etat du mode inclusion}}</a>
		</h4>
	</div>
	<div class="  panel-default">

	<div class=" panel-body" style="">
		<div id="includecontent" ></div>
		<div id="includetable"></div>
	</div>
	</div>
	</div>
	</div>
<style>
.AcceptedBar {
  background-color: #497ad6;
}


</style>


<?php include_file('desktop', 'include', 'js', 'RaspBEE');?>
