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
require_once dirname(__FILE__) . '/../../core/php/RaspBEECom.php';
$raspbeecom = new RaspBEECom;
		$RaspBEEConfJson = json_decode($raspbeecom->getConf());
		//print_r(get_object_vars($RaspBEEConfJson));
		//print_r($RaspBEEConfJson);
		//echo "debut :".$RaspBEEConfJson->apiversion;
?>
<script type="text/javascript" src="plugins/openzwave/3rdparty/vivagraph/vivagraph.min.js"></script>
<style>
    #graph_network {
        height: 80%;
        width: 90%;
        position: absolute;
    }
    #graph_network > svg {
        height: 100%;
        width: 100%
    }
    .node-item {
        border: 1px solid;
    }
    .node-primary-controller-color{
        color: #a65ba6;
    }
    .node-direct-link-color {
        color: #7BCC7B;
    }
    .node-remote-control-color {
        color: #00a2e8;
    }
    .node-more-of-one-up-color {
        color: #E5E500;
    }
    .node-more-of-two-up-color {
        color: #FFAA00;
    }
    .node-interview-not-completed-color {
        color: #979797;
    }
    .node-no-neighbourhood-color {
        color: #d20606;
    }
    .node-na-color {
        color: white;
    }
    .greeniconcolor {
        color: green;
    }
    .yellowiconcolor {
        color: #FFD700;
    }
    .rediconcolor {
        color: red;
    }
</style>
<div id='div_networkOpenzwaveAlert' style="display: none;"></div>
<div class='network' nid='' id="div_templateNetwork">
    <div class="container-fluid">
        <div id="content">
            <ul id="tabs_network" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#summary_network" data-toggle="tab"><i class="fa fa-info-circle"></i> {{Informations}}</a></li>
                <li><a href="#actions_network" data-toggle="tab"><i class="fa fa-sliders"></i> {{Actions}}</a></li>
                <li><a href="#statistics_network" data-toggle="tab"><i class="fa fa-bar-chart"></i> {{Statistiques}}</a></li>
                <li id="tab_graph"><a href="#graph_network" data-toggle="tab"><i class="fa fa-picture-o"></i> {{Graphique du réseau}}</a></li>
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
							echo $RaspBEEConfJson->name;
							?>
							</span></p>
							<p>{{ID Modèle}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->modelid;
							?>
							</span></p>
                            <p>{{Version}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->swversion;
							?>
							</span></p>								
                            <p>{{UUID}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->uuid;
							?>
							</span></p>								
							<p>{{ID bridge RaspBEE}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->bridgeid;
							?>
							</span></p>							
                            <p>{{Port Websocket}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->websocketport;
							?>							
                            <p>{{Canal ZigBEE}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->zigbeechannel;
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
							echo $RaspBEEConfJson->ipaddress.' ('.$RaspBEEConfJson->netmask.')';
							?>
							</span></p>
                            <p>{{Gateway}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->gateway;
							?>
							</span></p>
                            <p>{{Adresse MAC}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->mac;
							?>
							</span></p>								
						</div>
                    </div>
					<div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Wifi}}</h4></div>
                        <div class="panel-body"><lu style="font-size : 1em;"></span></p>
                            <p>{{Etat}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->wifi;
							?>
							</span></p>
							<p>{{SSID}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->wifiname;
							?>
							</span></p>
                            <p>{{Adresse IP}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->wifiip;
							?>
							</span></p>
                            <p>{{Canal}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->wifichannel;
							?>
							</span></p>
                            <p>{{Type}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->wifitype;
							?>
							</span></p>							
						</div>
                    </div>					
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4 class="panel-title">{{Système}}</h4></div>
                        <div class="panel-body">
                            <p>{{Heure}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->localtime;
							?>
							</span></p>							
                            <p>{{Format de l'heure}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->timeformat;
							?>
							</span></p>							
                            <p>{{TimeZone}} <span class="label label-default" style="font-size : 1em;">
							<?php
							echo $RaspBEEConfJson->timezone;
							?>
							</span></p>							
                        </div>
                    </div>
                </div>
                <div id="graph_network" class="tab-pane">
                    <table class="table table-bordered table-condensed" style="width: 350px;position:fixed;margin-top : 25px;">
                        <thead><tr><th colspan="2">{{Légende}}</th></tr></thead>
                        <tbody>
                            <tr>
                                <td class="node-primary-controller-color" style="width: 35px"><i class="fa fa-square fa-2x"></i></td>
                                <td>{{Contrôleur Primaire}}</td>
                            </tr>
                            <tr>
                                <td class="node-direct-link-color" style="width: 35px"><i class="fa fa-square fa-2x"></i></td>
                                <td>{{Communication directe}}</td>
                            </tr>
                            <tr>
                                <td class="node-remote-control-color"><i class="fa fa-square fa-2x"></i></td>
                                <td>{{Virtuellement associé au contrôleur primaire}}</td>
                            </tr>
                            <tr>
                                <td class="node-more-of-one-up-color"><i class="fa fa-square fa-2x"></i></td>
                                <td>{{Toutes les routes ont plus d'un saut}}</td>
                            </tr>
                            <tr>
                                <td class="node-interview-not-completed-color"><i class="fa fa-square fa-2x"></i></td>
                                <td>{{Interview non completé}}</td>
                            </tr>
                            <tr>
                                <td class="node-no-neighbourhood-color"><i class="fa fa-square fa-2x"></i></td>
                                <td>{{Présumé mort ou Pas de voisin}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="graph-node-name"></div>
                </div>
                <div id="api_users" class="tab-pane">
                    <br/>
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
					foreach ($RaspBEEConfJson->whitelist as $user => $value) {
						echo "<tr>";
						echo "<td>".$user."</td>";
						echo "<td>".$value->name."</td>";
						echo "<td>".$value->{"create date"}."</td>";
						echo "<td>".$value->{"last use date"}."</td>";
						echo "<td>Supprimer</td>";
						echo "</tr>";
					}
					?>
					</tbody>
					</table>
                </div>
                <div class="tab-pane" id="actions_network">
                    <table class="table">
                        <tr>
                            <td><a class="btn btn-success bt_addDevice" data-secure="0"><i class="fa fa-plus-circle"></i> {{Ajouter module (inclusion)}}</a></td>
                            <td>{{Ajouter un nouveau module au réseau Z-Wave.}}</td>
                        </tr>
                        <tr>
                            <td><a class="btn btn-warning bt_addDevice" data-secure="1"><i class="fa fa-plus-circle"></i> {{Ajouter module en mode sécurisé (inclusion)}}</a></td>
                            <td>{{Ajouter un nouveau module au réseau Z-Wave en mode sécurisé (peut ne pas marcher si le module ne le supporte pas bien).}}</td>
                        </tr>
                        <tr>
                            <td><a id="removeDevice" class="btn btn-danger"><i class="fa fa-minus-circle"></i> {{Supprimer module (Exclusion)}}</a></td>
                            <td>{{Supprimer un module du réseau Z-Wave.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="cancelCommand" class="btn btn-warning controller_action"><i class="fa fa-times"></i> {{Annuler commande}}</a></td>
                            <td>{{Annule toutes les commandes en cours sur le contrôleur.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="testNetwork" class="btn btn-primary controller_action"><i class="fa fa-check-square-o"></i> {{Test du réseau}}</a></td>
                            <td>{{Envoie une série de messages sur le réseau pour le tester.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="healNetwork" class="btn btn-success controller_action"><i class="fa fa-medkit"></i> {{Soigner le réseau}}</a></td>
                            <td>{{Soigner le réseau Z-Wave noeud par noeud.}}<br>{{Essaie de soigner tous les noeuds (un par un) en mettant à jour la liste des voisins et les routes optionnelles.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="createNewPrimary" class="btn btn-danger controller_action"><i class="fa fa-file"></i> {{Créer un nouveau noeud primaire}}</a></td>
                            <td>{{Mettez le contrôleur cible en mode de réception de configuration.}}<br>{{Le contrôleur cible doit être moins de 2m du contrôleur primaire. Nécessite SUC.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="receiveConfiguration" class="btn btn-danger controller_action"><i class="fa fa-file"></i> {{Receive Configuration}}</a></td>
                            <td>{{Transfert de la configuration réseau à partir d'un autre contrôleur.}}<br><i>{{Approcher l'autre contrôleur à moins de 2m du contrôleur primaire .}}</i></td>
                        </tr>
                        <tr>
                            <td><a data-action="transferPrimaryRole" class="btn btn-primary controller_action"><i class="fa fa-external-link"></i> {{Transférer le rôle primaire}}</a></td>
                            <td>{{Changer de contrôleur primaire. Le contrôleur primaire existant devient contrôleur secondaire.}}<br><i>{{Approcher l'autre contrôleur à moins de 2m du contrôleur primaire.}}</i></td>
                        </tr>
                        <tr>
                            <td><a data-action="writeZWConfig" class="btn btn-info controller_action"><i class="fa fa-pencil"></i> {{Ecrire le fichier de configuration}}</a></td>
                            <td>{{Ecrit le fichier de configuration OpenZwave.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="removeUnknownsDevices" class="btn btn-info controller_action"><i class="fa fa-repeat"></i> {{Régénérer la détection des noeuds inconnus}}</a></td>
                            <td>{{Supprime les informations des noeuds inconnus dans le fichier de config afin qu'il soit régénéré.}}<br><i>{{(Attention : Relance du réseau)}}</i></td>
                        </tr>
                        <tr>
                            <td><a data-action="softReset" class="btn btn-warning controller_action"><i class="fa fa-times"></i>{{Redémarrage}}</a></td>
                            <td>{{Redémarre le contrôleur sans effacer les paramètres de sa configuration réseau.}}</td>
                        </tr>
                        <tr>
                            <td><a data-action="hardReset" class="btn btn-danger controller_action"><i class="fa fa-eraser"></i>{{Remise à zéro}}</a></td>
                            <td>{{Remise à zéro du contrôleur.}} <b>{{Remet à zéro un contrôleur et efface ses paramètres de configuration réseau.}}</b><br>{{Le contrôleur devient un contrôleur primaire, prêt pour ajouter de nouveaux modules à un nouveau réseau.}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane" id="statistics_network">
                    <table class="table table-condensed table-striped">
                        <tr>
                            <td><b>{{Nombre d'émissions lues :}}</b></td>
                            <td><span class="zwaveNetworkAttr" data-l1key="controllerStatistics" data-l2key="broadcastReadCnt"></span></td>
                        </tr>
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
