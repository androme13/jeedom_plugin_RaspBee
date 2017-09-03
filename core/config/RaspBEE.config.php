<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

global $listCmdRaspBEE;

$listCmdRaspBEE = array(
    array(
        'name' => 'Etat',
        'type' => 'info',
        'subType' => 'binary',
		'order' => 0,
		'isVisible' => true,
		'configuration' => array(
			'etat'=> 0,
        ),
		'generic_type' => 'GENERIC_STATE',
    ),

		
	array(
        'name' => 'Fonction',
        'type' => 'info',
        'subType' => 'text',
		'order' => 0,
		'isVisible' => true,
		'configuration' => array(
			'fonction'=> 'aucune',
        ),
		'generic_type' => 'GENERIC_STATE',
    ),	
	
    array(
        'name' => 'Refresh',
        'type' => 'action',
        'subType' => 'other',
		'order' => 0,
		'isVisible' => true,
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
    array(
        'name' => 'ON-OFF',
        'type' => 'action',
        'subType' => 'other',
		'order' => 0,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '116',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),

	
   
);
?>
