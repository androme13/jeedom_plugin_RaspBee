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
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('RaspBEE');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
echo '<div id="div_inclusionAlert"/>';
?>
<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-4">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<li class="filter" style="margin-bottom: 5px;">
					<input class="filter form-control input-sm" placeholder="Rechercher" style="width: 100%">
				</li>
				<?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a>
</li>';
}
?>
			</ul>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend>
			<i class="fa fa-cog"></i>  {{Gestion du plugin}}</legend>
		<div class="eqLogicThumbnailContainer">	
			<?php
$controllerMode=1;
$status=RaspBEE::deamon_info();
// bouton mode inclusion
echo '<div class="cursor changeIncludeState card" data-mode="1" data-state="0" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
echo '<center>';
echo '<i class="fa fa-sign-in fa-rotate-90" style="font-size : 6em;color:#94ca02;"></i>';
echo '</center>';
echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">
<center>{{Mode inclusion}}</center>
</span>';
echo '</div>';

// bouton configuration
echo '<div class="cursor eqLogicAction card" data-action="gotoPluginConf" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
echo '<center>';
echo '<i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>';
echo '</center>';
echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
<center>{{Configuration}}</center>
</span>';
echo '</div>';
if ($status['launchable']=="ok"){
	// bouton synchroniser
	echo '<div class="cursor card disabled" id="bt_syncEqLogic" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-refresh" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
<center>{{Synchroniser}}</center>
</span>';
	echo '</div>';

	// bouton Réseau RaspBEE
	echo '<div class="cursor card" id="bt_RaspBEENetwork" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-sitemap" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
<center>{{Réseau RaspBEE}}</center>
</span>';
	echo '</div>';
	
	// bouton santé
	echo '<div class="cursor card" id="bt_RaspBEEHealth" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-medkit" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
<center>{{Santé}}</center>
</span>';
	echo '</div>';
}
// bouton removeall(debug)
echo '<div class="cursor card" id="bt_RaspBEERemoveAll" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
echo '<center>';
echo '<i class="fa fa-times" style="font-size : 6em;color:#FA5858;"></i>';
echo '</center>';
echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#FA5858">
<center>{{Remove ALL}}</center>
</span>';
echo '</div>';
?>	 
		</div>
		<legend>
			<i class="fa fa-table"></i> {{Mes équipements RaspBEE}}</legend>
		<div class="eqLogicThumbnailContainer">	
			<?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="'. $eqLogic->getId().'" data-logical-id="' . $eqLogic->getLogicalId().'"  style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo "<center>";
	switch ($eqLogic->getConfiguration('type')){
	case "ZHASwitch" :
		echo '<i class="fa fa-th-large" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "ZHATemperature" :
		echo '<i class="jeedom jeedom-thermometre" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "ZHAHumidity" :
		echo '<i class="jeedom2 jeedom2-plante_eau2" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "ZHAPressure" :
		echo '<i class="meteo meteo-nuage-soleil-pluie" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "Extended color light" :
		echo '<i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "Dimmable light" :
		echo '<i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;"></i>';
		break;
	case "LightGroup" :
		echo '<i class="fa fa-circle-o" style="font-size : 6em;color:#767676;"></i>';
		break;	
	default:
		echo '<i class="jeedom jeedom-lumiere-off" style="font-size : 6em;color:#767676;"></i>';
	}
	//
	echo '<br>';
	echo '<span style="font-size : 0.8em;">';
	switch ($eqLogic->getConfiguration('type')){
	case "ZHASwitch" :
		echo '{{Commande}}';
		break;
	case "ZHATemperature" :
		echo '{{Capteur de température}}';
		break;
	case "ZHAHumidity" :
		echo '{{Capteur d\'humidité}}';
		break;
	case "ZHAPressure" :
		echo '{{Capteur de pression}}';
		break;
	case "Extended color light" :
		echo '{{Eclairage}}';
		break;
	case "Dimmable light" :
		echo '{{Eclairage}}';
		break;
	case "LightGroup" :
		echo '{{Groupe}}';
		break;
		default :
		echo '{{Inconnu}}';
	}
	
	echo '</span>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">
<center>' . $eqLogic->getHumanName(true, true) . '</center>
</span>';
	echo '</div>';
}
?>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE;padding-left: 25px;display: none;">
		<a class="btn btn-success eqLogicAction pull-right" data-action="save">
			<i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove">
			<i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
		<a class="btn btn-default eqLogicAction pull-right" data-action="configure">
			<i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a class="eqLogicAction cursor" aria-controls="home" role="tab"  data-action="returnToThumbnailDisplay">
					<i class="fa fa-arrow-circle-left">
				</i>
			</a>
		</li>
		<li role="presentation" class="active">
			<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
				<i class="fa fa-tachometer">
			</i> {{Equipement}}</a>
	</li>
	<li role="presentation">
		<a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
			<i class="fa fa-list-alt">
		</i> {{Commandes}}</a>
</li>
</ul>
<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
	<div role="tabpanel" class="tab-pane active" id="eqlogictab">
		<br>
		<div class="row">
			<div class="col-sm-7">
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Nom de l'équipement}}</label>
							<div class="col-sm-6">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;">
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Objet parent}}</label>
							<div class="col-sm-6">
								<select class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Catégorie}}</label>
							<div class="col-sm-8">
								<?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '">' . $value['name'];
	echo '</label>';
}
?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"></label>
							<div class="col-sm-8">
								<label class="checkbox-inline">
									<input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
								<label class="checkbox-inline">
									<input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="col-sm-5">
				<form class="form-horizontal">				
					<table id="table_infoseqlogic" class="table table-condensed" style="border-radius: 10px;">
						<thead>
						</thead>
						<tbody>
						</tbody>
					</table>
				</form>
			</div>
		</div>

		<div id="masterEqLogic"></div>
		<div id="membersEqLogic"></div>
	</div>

	<div role="tabpanel" class="tab-pane" id="commandtab">
		<a class="btn btn-success btn-sm cmdAction expertModeVisible pull-right" data-action="add" style="margin-top:5px;">
			<i class="fa fa-plus-circle"></i> {{Commandes}}</a>
		<br>
		<br>
		<table id="table_cmd" class="table table-bordered table-condensed">
			<thead>
				<tr>                
					<th class="expertModeVisible">{{ID}}</th>
					<th>{{Nom}}</th>
					<th>{{Paramètres}}</th>
					<th>{{Options}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
</div>
</div>
<?php include_file('core', 'RaspBEE', 'class.js', 'RaspBEE');?>
<?php include_file('desktop', 'RaspBEE', 'js', 'RaspBEE');?>
<?php include_file('core', 'plugin.template', 'js');?>
