<?php
/* This file is part of Plugin openzwave for jeedom.
 *
 * Plugin openzwave for jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plugin openzwave for jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plugin openzwave for jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}

require_once dirname(__FILE__) . '/../../core/php/RaspBEECom.php';
$raspbeecom = new RaspBEECom;
		$sensorsJson = json_decode($raspbeecom->getSensors());
		//print_r($RaspBEEConfJson);
?>
<span class="pull-left alert" id="span_state" style="background-color : #dff0d8;color : #3c763d;height:35px;border-color:#d6e9c6;display:none;margin-bottom:0px;"><span style="position:relative; top : -7px;">{{Demande envoyée}}</span></span>
<br/><br/>

<div id='div_backupAlert' style="display: none;"></div>
<form class="form-horizontal">
    <fieldset>
        <div class="form-group">
            <label class="col-sm-4 col-xs-6 control-label">{{Lancer une synchronisation}}</label>
            <div class="col-sm-4 col-xs-6">
                <a class="btn btn-success" id="bt_synchronize"><i class="fa fa-refresh"></i> {{Synchroniser}}</a>
            </div>
        </div>        
        </fieldset>
		 <textarea rows="10" cols="100" id="textarealog">
<?php print_r($sensorsJson);?>
</textarea> 
    </form>
</div>
<?php include_file('desktop', 'synchronize', 'js', 'RaspBEE');?>
