<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('RaspBEE');
sendVarToJS('eqType', 'RaspBEE');
$eqLogics = eqLogic::byType('RaspBEE');
echo '<div id="div_inclusionAlert"></div>';
?>
<div class="alert alert-info">
	{{Obligatoire pour que cela fonctionne.}}
</div>
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
?>	 
  </div>
<legend><i class="fa fa-table"></i> {{Mes équipements RaspBEE}}</legend>
<div class="eqLogicThumbnailContainer">	
    <?php
foreach ($eqLogics as $eqLogic) {
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo "<center>";
	echo '<img src="plugins/RaspBEE/doc/images/template_icon.png" height="105" width="95" />';
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>
</div>
<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
    <form class="form-horizontal">
        <fieldset>
            <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}  <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom de l'équipement RaspBEE}}</label>
                <div class="col-sm-3">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement RaspBEE}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                <div class="col-sm-3">
                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
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
            <label class="col-sm-3 control-label" >{{Activer}}</label>
            <div class="col-sm-9">			   
			  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
              <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>			   
           </div>
       </div>
       <div class="form-group">
        <label class="col-sm-3 control-label">{{deconz param 1}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="param1" placeholder="param1"/>
        </div>
    </div>
</fieldset>
</form>

<legend>{{RaspBEE}}</legend>
<a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
			<th>#</th>
            <th>{{Nom}}</th>
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
</div>

<?php include_file('desktop', 'RaspBEE', 'js', 'RaspBEE');?>
<?php include_file('core', 'plugin.template', 'js');?>
