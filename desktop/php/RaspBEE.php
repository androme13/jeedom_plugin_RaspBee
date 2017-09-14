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
sendVarToJS('eqType', 'RaspBEE');
$eqLogics = eqLogic::byType('RaspBEE');
echo '<div id="div_inclusionAlert"></div>';
?>
<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach ($eqLogics as $eqLogic) {
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
?>
           </ul>
       </div>
   </div>

   <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
    <legend><i class="fa fa-cog"></i>  {{Gestion du plugin}}</legend>

    <div class="eqLogicThumbnailContainer">
	
	<?php
	$controllerMode=1;
if ($controllerMode == 1) {
	echo '<div class="cursor changeIncludeState card" data-mode="1" data-state="0" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-sign-in fa-rotate-90" style="font-size : 6em;color:#94ca02;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Arrêter inclusion}}</center></span>';
	echo '</div>';
} else {
	echo '<div class="cursor changeIncludeState card" data-mode="1" data-state="1" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-sign-in fa-rotate-90" style="font-size : 6em;color:#94ca02;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Mode inclusion}}</center></span>';
	echo '</div>';
}
if ($controllerMode == 5) {
	echo '<div class="cursor changeIncludeState card" data-mode="0" data-state="0" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-sign-out fa-rotate-90" style="font-size : 6em;color:#FA5858;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#FA5858"><center>{{Arrêter exclusion}}</center></span>';
	echo '</div>';
} else {
	echo '<div class="cursor changeIncludeState card" data-mode="0" data-state="1" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-sign-out fa-rotate-90" style="font-size : 6em;color:#FA5858;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#FA5858"><center>{{Mode exclusion}}</center></span>';
	echo '</div>';
}

	// bouton configuration
	echo '<div class="cursor eqLogicAction card" data-action="gotoPluginConf" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>';
	echo '</div>';
	
	// bouton synchroniser
	echo '<div class="cursor card disabled" id="bt_syncEqLogic" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-refresh" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Synchroniser}}</center></span>';
	echo '</div>';

	// bouton Réseau RaspBEE
	echo '<div class="cursor card" id="bt_RaspBEENetwork" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-sitemap" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Réseau RaspBEE}}</center></span>';
	echo '</div>';
	
	// bouton santé
	echo '<div class="cursor card" id="bt_RaspBEEHealth" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-medkit" style="font-size : 6em;color:#767676;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>';
	echo '</div>';
	// bouton removeall(debug)
	echo '<div class="cursor card" id="bt_RaspBEERemoveAll" style="background-color : #8000FF; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo '<center>';
	echo '<i class="fa fa-times" style="font-size : 6em;color:#94ca02;"></i>';
	echo '</center>';
	echo '<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Remove ALL}}</center></span>';
	echo '</div>';
?>	 
  </div>
<legend><i class="fa fa-table"></i> {{Mes équipements RaspBEE}}</legend>
<div class="eqLogicThumbnailContainer">	
    <?php
foreach ($eqLogics as $eqLogic) {
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo "<center>";
	//echo '<img src="plugins/RaspBEE/doc/images/template_icon.png" height="105" width="95" />';
	//echo $eqLogic->getConfiguration('type');
	//echo "<span>";
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
		
	}
	
	echo '</span>';
	echo '</center>';
	
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>
</div>
<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
  


        
		
		 <div class="row">
            <div class="col-sm-7">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{Nom de l'équipement}}</label>
                            <div class="col-sm-6">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
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
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>
                       </div>
                   </div>
                   <div class="form-group">
                    <label class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-sm-5">
        <form class="form-horizontal">
            <fieldset>
			                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ID Origine}}</label>
                    <div class="col-sm-8">
					<span class="label label-default" style='font-size : 1em;'>
                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="origid"></span>
					</span>				
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Marque}}</label>
                    <div class="col-sm-8">
					<span class="label label-default" style='font-size : 1em;'>
                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="manufacturername"></span>
					</span>&nbsp
					<span class="label label-default" style='font-size : 1em;'>
                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="modelid"></span>
					</span>					
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Firmware}}</label>
                    <div class="col-sm-8">
						<span class="label label-default" style='font-size : 1em;'>
                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="swversion"></span>
					</span>			
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Type}}</label>
				<div class="col-sm-8">
				  <span class="label label-default" style='font-size : 1em;'>
					<span class="eqLogicAttr" data-l1key="configuration" data-l2key="product_name"></span>
					<span class="eqLogicAttr" data-l1key="configuration" data-l2key="type" title="{{Version de la configuration}}"></span>
				</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">{{UID}}</label>
                <div class="col-sm-8">
				  <span class="label label-default" style='font-size : 1em;'>
					<span class="eqLogicAttr" data-l1key="configuration" data-l2key="uniqueid"></span>
					</span>
                </div>
            </div>
        </fieldset>
    </form>
</div>
</div>
		
		
		
		
		




<legend>{{RaspBEE}}</legend>
<a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
			<th>#</th>
            <th>{{Nom}}</th>
			<th>{{Paramètres}}</th>
			<th>{{Action}}</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form class="form-horizontal">
    <fieldset>
        <div class="form-actions">
            <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>
    </fieldset>
</form>


</div>


<?php include_file('desktop', 'RaspBEE', 'js', 'RaspBEE');?>
<?php include_file('core', 'plugin.template', 'js');?>
