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

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
	echo '401 - Accès non autorisé';
	die();
}
ajax::init();
try {
	$resp = RaspBEE::humanNameByOrigIdAndType(init('request'));
	//error_log(json_encode($resp),3,"/tmp/prob.txt");
	ajax::success($resp);
} catch (Exception $e) {
	//ajax::error($e->getMessage());
	http_response_code(500);
	die($e->getMessage());
}
