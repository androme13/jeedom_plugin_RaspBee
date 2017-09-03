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
        'name' => 'Chaine Actuelle',
        'type' => 'info',
        'subType' => 'text',
		'order' => 0,
		'isVisible' => true,
		'configuration' => array(
			'chaine_actuelle'=> 'aucune',
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
	
    array(
        'name' => '1',
        'type' => 'action',
        'subType' => 'other',
		'order' => 1,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '513',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '2',
        'type' => 'action',
        'subType' => 'other',
		'order' => 2,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '514',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '3',
        'type' => 'action',
        'subType' => 'other',
		'order' => 3,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '515',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '4',
        'type' => 'action',
        'subType' => 'other',
		'order' => 4,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '516',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '5',
        'type' => 'action',
        'subType' => 'other',
		'order' => 5,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '517',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),	
	
    array(
        'name' => '6',
        'type' => 'action',
        'subType' => 'other',
		'order' => 6,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '518',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '7',
        'type' => 'action',
        'subType' => 'other',
		'order' => 7,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '519',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '8',
        'type' => 'action',
        'subType' => 'other',
		'order' => 8,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '520',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => '9',
        'type' => 'action',
        'subType' => 'other',
		'order' => 9,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '521',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
    array(
        'name' => '0',
        'type' => 'action',
        'subType' => 'other',
		'order' => 10,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '512',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),	
	
    array(
        'name' => 'CH+',
        'type' => 'action',
        'subType' => 'other',
		'order' => 11,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '402',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'CH-',
        'type' => 'action',
        'subType' => 'other',
		'order' => 12,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '403',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'VOL+',
        'type' => 'action',
        'subType' => 'other',
		'order' => 13,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '115',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'VOL-',
        'type' => 'action',
        'subType' => 'other',
		'order' => 14,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '114',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'MUTE',
        'type' => 'action',
        'subType' => 'other',
		'order' => 15,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '113',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),	
	
    array(
        'name' => 'UP',
        'type' => 'action',
        'subType' => 'other',
		'order' => 16,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '103',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'DOWN',
        'type' => 'action',
        'subType' => 'other',
		'order' => 17,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '108',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'LEFT',
        'type' => 'action',
        'subType' => 'other',
		'order' => 18,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '105',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'RIGHT',
        'type' => 'action',
        'subType' => 'other',
		'order' => 19,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '106',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'OK',
        'type' => 'action',
        'subType' => 'other',
		'order' => 20,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '352',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),	
	
    array(
        'name' => 'BACK',
        'type' => 'action',
        'subType' => 'other',
		'order' => 21,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '158',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'MENU',
        'type' => 'action',
        'subType' => 'other',
		'order' => 22,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '139',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'PLAY-PAUSE',
        'type' => 'action',
        'subType' => 'other',
		'order' => 23,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '164',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'FBWD',
        'type' => 'action',
        'subType' => 'other',
		'order' => 24,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '168',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'FFWD',
        'type' => 'action',
        'subType' => 'other',
		'order' => 25,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '159',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'REC',
        'type' => 'action',
        'subType' => 'other',
		'order' => 26,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '167',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
    array(
        'name' => 'VOD',
        'type' => 'action',
        'subType' => 'other',
		'order' => 27,
		'isVisible' => true,
		'configuration' => array(
			'code_touche'=> '393',
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),
	
	array(
        'name' => 'Mosaique 1',
        'type' => 'action',
        'subType' => 'other',
		'order' => 28,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	array(
        'name' => 'Mosaique 2',
        'type' => 'action',
        'subType' => 'other',
		'order' => 29,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 3',
        'type' => 'action',
        'subType' => 'other',
		'order' => 30,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),
	
	array(
        'name' => 'Mosaique 4',
        'type' => 'action',
        'subType' => 'other',
		'order' => 31,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 5',
        'type' => 'action',
        'subType' => 'other',
		'order' => 32,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),	
	
	array(
        'name' => 'Mosaique 6',
        'type' => 'action',
        'subType' => 'other',
		'order' => 33,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),	
	
	array(
        'name' => 'Mosaique 7',
        'type' => 'action',
        'subType' => 'other',
		'order' => 34,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),

	array(
        'name' => 'Mosaique 8',
        'type' => 'action',
        'subType' => 'other',
		'order' => 35,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),

	array(
        'name' => 'Mosaique 9',
        'type' => 'action',
        'subType' => 'other',
		'order' => 36,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),
	
	array(
        'name' => 'Mosaique 10',
        'type' => 'action',
        'subType' => 'other',
		'order' => 37,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 11',
        'type' => 'action',
        'subType' => 'other',
		'order' => 38,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	array(
        'name' => 'Mosaique 12',
        'type' => 'action',
        'subType' => 'other',
		'order' => 39,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),
	
	array(
        'name' => 'Mosaique 13',
        'type' => 'action',
        'subType' => 'other',
		'order' => 40,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 14',
        'type' => 'action',
        'subType' => 'other',
		'order' => 41,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 15',
        'type' => 'action',
        'subType' => 'other',
		'order' => 42,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),

	array(
        'name' => 'Mosaique 16',
        'type' => 'action',
        'subType' => 'other',
		'order' => 43,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 17',
        'type' => 'action',
        'subType' => 'other',
		'order' => 44,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 18',
        'type' => 'action',
        'subType' => 'other',
		'order' => 45,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),

	array(
        'name' => 'Mosaique 19',
        'type' => 'action',
        'subType' => 'other',
		'order' => 46,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 20',
        'type' => 'action',
        'subType' => 'other',
		'order' => 47,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 21',
        'type' => 'action',
        'subType' => 'other',
		'order' => 48,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),

	array(
        'name' => 'Mosaique 22',
        'type' => 'action',
        'subType' => 'other',
		'order' => 49,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 23',
        'type' => 'action',
        'subType' => 'other',
		'order' => 50,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
	
	array(
        'name' => 'Mosaique 24',
        'type' => 'action',
        'subType' => 'other',
		'order' => 51,
		'isVisible' => false,
		'configuration' => array(
			'mosaique_chaine'=> 'blank',
			'mosaique_numero'=> null,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '1',
    ),
	
	array(
        'name' => 'Telecommande',
        'type' => 'action',
        'subType' => 'other',
		'order' => 52,
		'isVisible' => false,
		'configuration' => array(
			'telecommande'=> 1,
        ),
		'generic_type' => 'GENERIC_ACTION',
		'forceReturnLineAfter' => '0',
    ),
);
?>
