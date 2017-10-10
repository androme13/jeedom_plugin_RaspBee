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
<div id='div_syncAlert' style="display: none;"></div>
<form class="form-horizontal">
	<fieldset>
		<div class="panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#collapseOptions">Options</a>
					</h4>
				</div>
				<div id="collapseOptions" class="panel-collapse collapse in">
					<ul class="list-group">
						<li class="list-group-item">
							<div>
								<h4 class="panel-title">
									Types de Synchronisation
								</h4>
								<input type="radio" id="optionType1" name="optionType" value="basic">
								<label for="optionType1">Normale</label><br>

								<input type="radio" id="optionType2" name="optionType" value="renew">
								<label for="optionType2">renouvellement total</label><br>
								
								<input type="radio" id="optionType3" name="optionType" value="renewbutid">
								<label for="optionType3">renouvellement partiel</label><br>

							</div>
						</li>
					</ul>
					<div id="syncOptionsHelp" class="panel-footer"></div>
				</div>
			</div>
		</div> 	
	</fieldset>
	<fieldset>
		<div class="form-group">			
			<div class="col-sm-4 col-xs-6">
				<a class="btn btn-success" id="bt_synchronize">
					<i class="fa fa-refresh"> {{Synchroniser}}</i></a>
			</div>
		</div>        
	</fieldset>
	<div class="col-sm-7 ">
		<ul id="treeSync"></ul>
	</div>
</form>
<?php include_file('desktop', 'synchronize', 'js', 'RaspBEE');?>
