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
require_once dirname(__FILE__) . '/../../core/class/RaspBEECom.class.php';
//require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
$raspbeecom = new RaspBEECom;
		$RaspBEEConf = $raspbeecom->getConf();
		$RaspBEEConfJson = json_decode($RaspBEEConf->message,true);
?>
<script type="text/javascript" src="plugins/openzwave/3rdparty/vivagraph/vivagraph.min.js"></script>
<style>

</style>
<div id='div_networkRaspBEEAlert' style="display: none;"></div>
<div class='network' nid='' id="div_templateNetwork">
    <div class="container-fluid">
        <div id="content">
            <ul id="tabs_network" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#summary_network" data-toggle="tab"><i class="fa fa-info-circle"></i> {{Informations}}</a></li>
				<li id="tab_config"><a href="#config_network" data-toggle="tab"><i class="fa fa-cog"></i> {{Configuration}}</a></li>
				<li id="tab_actions"><a href="#api_actions" data-toggle="tab"><i class="fa fa-wrench"></i> {{Actions}}</a></li>
                <li id="tab_users"><a href="#api_users" data-toggle="tab"><i class="fa fa-user"></i> {{Utilisateurs}}</a></li>
            </ul>
            <div id="network-tab-content" class="tab-content">
                <div class="tab-pane active" id="summary_network">
                    <br>
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Informations de la passerelle RaspBEE}}</h4></div>
                        <div class="panel-body">
							<p>{{Nom}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[name];
							?>
							</span></p>
							<p>{{ID Modèle}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[modelid];
							?>
							</span></p>
                            <p>{{Version}} <span class="label label-default" style="font-size : 1em;">
							<?php							
							echo $RaspBEEConfJson[swversion];	
							echo'</span>';
							$versionDetail = explode(".", $RaspBEEConfJson[swversion]);
							// version mini de deconz 2.4.70
							$minVersion = array(2,4,82);
							$error = '';							
							if (($versionDetail===false || count($versionDetail)<3))
							$error=' <span class="label label-warning" style="font-size : 1em;">{{Version obsolète de deconz, veuillez mettre à jour deconz (mini 2.04.70)}}</span>';
							else
							if (($versionDetail[0]<$minVersion[0] || ($versionDetail[0]==$minVersion[0] && $versionDetail[1]<$minVersion[1]) || ($versionDetail[0]==$minVersion[0] && $versionDetail[1]==$minVersion[1] && $versionDetail[2]<$minVersion[2]) ))
							$error=' <span class="label label-warning" style="font-size : 1em;">{{Version obsolète de deconz, veuillez mettre à jour deconz }}({{mini}} '.$minVersion[0].'.'.$minVersion[1].'.'.$minVersion[2].')</span>';
						
							echo $error;		
							?>
							</p>								
                            <p>{{UUID}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[uuid];
							?>
							</span></p>								
							<p>{{ID bridge RaspBEE}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[bridgeid];
							?>
							</span></p>							
                            <p>{{Port Websocket}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[websocketport];
							?>							
                            <p>{{Canal ZigBEE}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[zigbeechannel];
							?>
							</span></p>
                            <p>{{Clé API RaspBEE}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo config::byKey('raspbeeAPIKEY','RaspBEE');
							?>
							</span></p>							
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Etat}}</h4></div>
                        <div class="panel-body">

                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Réseau}}</h4></div>
                        <div class="panel-body"><lu style="font-size : 1em;"></span></p>
                            <p>{{Adresse IP}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[ipaddress].' ('.$RaspBEEConfJson[netmask].')';
							?>
							</span></p>
                            <p>{{Gateway}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[gateway];
							?>
							</span></p>
                            <p>{{Adresse MAC}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[mac];
							?>
							</span></p>								
						</div>
                    </div>
					<div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Wifi}}</h4></div>
                        <div class="panel-body"><lu style="font-size : 1em;"></span></p>
                            <p>{{Etat}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[wifi];
							?>
							</span></p>
							<p>{{SSID}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[wifiname];
							?>
							</span></p>
                            <p>{{Adresse IP}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[wifiip];
							?>
							</span></p>
                            <p>{{Canal}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[wifichannel];
							?>
							</span></p>
                            <p>{{Type}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[wifitype];
							?>
							</span></p>							
						</div>
                    </div>					
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Système}}</h4></div>
                        <div class="panel-body">
                            <p>{{Heure}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[localtime];
							?>
							</span></p>							
                            <p>{{Format de l'heure}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[timeformat];
							?>
							</span></p>							
                            <p>{{TimeZone}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson[timezone];
							?>
							</span></p>							
                        </div>
                    </div>
                </div>
				
				
				
				
				<div id="config_network" class="tab-pane">
				
					<br>
					<div class="panel-group" id="network_configuration_accordion">
                    <div class="panel panel-primary">
					<div class="panel-heading"><h4 class="panel-title">
					 <a data-toggle="collapse" data-parent="#network_configuration_accordion" href="#deconzConfigPanel">
					{{Configuration de la passerelle}}</a></h4>
					</div>
					<div id="deconzConfigPanel" class="panel-collapse collapse out">
					
					<div class="panel-body">
					<div id="div_deconzConfigNetworkPanelAlert"></div>
					<p><span class="label" style="font-size : 1em;">{{Nom}}</span>
					<input class="form-control" type="text" name="gw_name_configuration" key="name" value="<?php
							echo $RaspBEEConfJson[name];
							?>">
					</p>
					<p><span class="label" style="font-size : 1em;">{{Mises à jour OTA}}</span>
					<select class="form-control" key="otauactive">
					<?php
					$options = array('Activer','Désactiver');
					foreach ($options as $option){
						echo '<option value="true">'.$option.'</option>';
						//commandes
					}
					//echo '<option value="true">Activer</option>';
					//echo '<option value="false">Désactiver</option>';
					?>
						</select>
					</p>
					<p><span class="label" style="font-size : 1em;">{{Mode découverte}}</span>
					<select class="form-control" key="discovery">
						  <option value="true">Activer</option>
						  <option value="false">Désactiver</option>
						</select>
					</p>
					<p><span class="label" style="font-size : 1em;">{{Canal ZigBEE}}</span>
					<select class="form-control" selected="11" key="zigbeechannel">
					<?php
					$options = array('11','15','20','25');
					foreach ($options as $option){
						$selected='';
						if ($option == $RaspBEEConfJson[zigbeechannel])
							$selected ='selected="selected"';
						
						echo '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
					}
					?>
						  
						</select>
					</p>
					<p><span class="label" style="font-size : 1em;">{{Time Zone}}</span>
					<select class="form-control" key="timezone" type="text">
						  <?php
							$options = DateTimeZone::listIdentifiers();
							foreach ($options as $option){
								$selected='';
								if ($option == $RaspBEEConfJson[timezone])
								$selected ='selected="selected"';
								echo '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
							}
							?>
						</select>
					</p>
					<p><span class="label" style="font-size : 1em;">{{Format de l'heure}}</span>
					<select class="form-control" key="timeformat" type="text">
							<?php
							$options = array('12h','24h');
							foreach ($options as $option){
								$selected='';
								if ($option == $RaspBEEConfJson[timeformat])
								$selected ='selected="selected"';
								echo '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
							}
							?>
						</select>
					</p>
					<a class="btn btn-success pull-right deconzConfigSaveButton" id="deconzConfigSaveButton"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
					</div>
					</div>					
					</div>
					
					<div class="panel panel-primary">
					<div class="panel-heading"><h4 class="panel-title">
					 <a data-toggle="collapse" data-parent="#network_configuration_accordion" href="#lightsConfigPanel">
					{{Configuration de l'éclairage}}</a></h4></div>
					<div id="lightsConfigPanel" class="panel-collapse collapse in">
					<div class="panel-body">
					<div id="div_lightsConfigNetworkPanelAlert"></div>
					<p><span class="label" style="font-size : 1em;">
					{{Restaurer l'état de l'éclairage lors d'une coupure/retour du courant (experimental)}}</span>
					</p><p><input class="" type="checkbox" name="power_outage" key="poweroutagerestore"></p>
					<?php
					//config::
					?>
					<a class="btn btn-success pull-right lightsConfigSaveButton" id="lightsConfigSaveButton"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>			
					</div>
					</div>					
					</div>
					
					</div>
				</div>

				
				
				
				
				
				<div id="api_actions" class="tab-pane">
					<br>
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Debug (Deconz)}}</h4></div>
                        <div class="panel-body">
						<a id="showdebugsensors" name='.$value->name.' class="btn btn-info showDebugInfoBTN"><i class="fa fa-info-circle"></i> {{Afficher les capteurs}}</a>
						<a id="showdebuglights" name='.$value->name.' class="btn btn-info  showDebugInfoBTN"><i class="fa fa-info-circle"></i> {{Afficher les éclairages}}</a>
						<a id="showdebuggroups" name='.$value->name.' class="btn btn-info showDebugInfoBTN"><i class="fa fa-info-circle"></i> {{Afficher les groupes}}</a>
						<a id="showdebugconfig" name='.$value->name.' class="btn btn-info showDebugInfoBTN"><i class="fa fa-info-circle"></i> {{Afficher la config raspbee}}</a>						
                        </div>
                    </div>
						<div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Réinitialisation}}</h4></div>
                        <div class="panel-body">
						<a id="bt_RaspBEERemoveAll" class="btn btn-danger bt_RaspBEERemoveAll"><i class="fa fa-times"></i> {{Supprimer tous les équipements RaspBEE}}</a>
						</div>
					</div>
				</div>
                <div id="api_users" class="tab-pane">
                    <br>
					<table class="table table-bordered table-condensed" style="width:100%">
					<tr>
						<th>{{Clé}}</th>
						<th>{{Nom}}</th>
						<th>{{Date de création}}</th>
						<th>{{Date dernière utilisation}}</th>
						<th>{{Action}}</th>
					</tr>
					<tbody>
					<?php
					foreach ($RaspBEEConfJson[whitelist] as $user => $value) {
						if (config::byKey('raspbeeAPIKEY','RaspBEE')==$user)
							echo '<tr id="'.$user.'" bgcolor="DarkCyan" style="color: white;">';
						else
							echo '<tr id="'.$user.'">';
						echo "<td>".$user."</td>";
						echo "<td>".$value[name]."</td>";
						echo "<td>".$value["create date"].".</td>";
						echo "<td>".$value["last use date"]."</td>";
						if (config::byKey('raspbeeAPIKEY','RaspBEE')==$user)							
							echo '<td><span class="label control-label" style="font-size : 1.1em;">{{Clé utilisée par le plugin}}.</span></td>';
						else
							echo '<td><a id='.$user.' name='.$value[name].' class="btn btn-danger  deleteRaspBeeUser"><i class="fa fa-minus-circle"></i> {{Supprimer l\'utilisateur}}</a></td>';
						echo "</tr>";
					}
					?>
					</tbody>
					</table>
                </div>                
            </div>
        </div>
    </div>
</div>
</div>
<?php
 unset($raspbeecom);
 include_file('desktop', 'network', 'js', 'RaspBEE');
 ?>
