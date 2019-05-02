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
								{{Types de Synchronisation}}
								</h4>
								<input type="radio" id="optionType1" name="optionType" value="limited" >
								<label for="optionType1">{{Limitée}}.</label><br>

								<input type="radio" id="optionType2" name="optionType" value="basic" >
								<label for="optionType1">{{Normale}} ({{recommandée}}).</label><br>

								<input type="radio" id="optionType3" name="optionType" value="renew" >
								<label for="optionType2">{{Resynchronisation totale}}.</label><br>

								<input type="radio" id="optionType4" name="optionType" value="renewbutidandname">
								<label for="optionType3">{{Resynchronisation partielle}}.</label><br>

							</div>
						</li>
					</ul>
					<div id="syncOptionsHelp" class="panel-footer"></div>
				</div>
			</div>

		<div class="panel panel-footer">
				<a class="btn btn-success" id="bt_synchronize">
					<i class="fa fa-refresh"> {{Synchroniser}}</i></a>
		</div>
		</div>
	</form>
	</div>
	<div class="panel-group col-sm-8" >
	<div class="panel panel-default">
		<div class="  panel-heading">
		<h4 class=" panel-title">
			<a>{{Résumé de la synchronisation}}</a>
		</h4>
	</div>
	<div class="  panel-default">

	<div class=" panel-body" style="">
		<ul id="treeSync" class="tree"></ul>
	</div>
	</div>
	</div>
	</div>
<style>
input.tree {
  display: none;
}
input.tree ~ ul {
 display: none;
}
input.tree:checked ~ ul {
 display: block;
}
input.tree ~ .fa-angle-double-down {
  display: none;
}
input.tree:checked ~ .fa-angle-double-right {
  display: none;
}
input.tree:checked ~ .fa-angle-double-down {
  display: inline;
}

/* habillage */
li.tree {
  display: block;
  font-family: 'Arial';
  font-size: 15px;
  padding: 0.2em;
  border: 1px solid transparent;
}
li.tree:hover {
  border-radius: 3px;
  background-color: SlateGray;
}
li.treeblock {
  display: block;
  font-family: 'Arial';
  font-size: 15px;
  padding: 0.2em;
  border: 1px solid transparent;
}

li.treeblock:hover, input.tree:checked {
  border-radius: 3px;
  background-color: black;
}

</style>


<?php include_file('desktop', 'synchronize', 'js', 'RaspBEE');?>
