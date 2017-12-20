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

jeedom.raspbee = function() {};

jeedom.raspbee.eqLogic = function() {};
jeedom.raspbee.com = function() {};


jeedom.raspbee.eqLogic.getOwnersGroups = function (_params) {
	var paramsRequired = ['origId'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action : 'getOwnersGroups',
		request: _params
	};
	$.ajax(paramsAJAX);
};

jeedom.raspbee.eqLogic.humanNameById = function (_params) {
	var paramsRequired = ['id'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action : 'humanNameById',
		request: _params
	};
	$.ajax(paramsAJAX);
};


jeedom.raspbee.eqLogic.getById = function (_params) {
	var paramsRequired = ['id'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action : 'getById',
		request: _params
	};
	$.ajax(paramsAJAX);
};

jeedom.raspbee.eqLogic.humanNameByOrigIdAndType = function (_params) {
	var paramsRequired = ['origId','type'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action : 'humanNameByOrigIdAndType',
		request: _params
	};
	$.ajax(paramsAJAX);
};

jeedom.raspbee.eqLogic.getAll = function (_params) {
	var paramsRequired = [];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action: 'getAllEqLogics',
		//request: _params
	};
	$.ajax(paramsAJAX);
}

jeedom.raspbee.eqLogic.removeFromGroup = function (_params) {
	var paramsRequired = ['deviceId','groupId'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action: 'removeFromGroup',
		request: _params
	};
	$.ajax(paramsAJAX);
}

jeedom.raspbee.eqLogic.getGroupMembers = function (_params) {
	var paramsRequired = ['groupId'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action: 'getGroupMembers',
		request: _params
	};
	$.ajax(paramsAJAX);
}


// members = json array
jeedom.raspbee.com.setGroupMembers = function (_params) {
	var paramsRequired = ['groupId','members'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action: 'setGroupMembers',
		request: _params
	};
	$.ajax(paramsAJAX);
}

jeedom.raspbee.com.setDeconzConfig = function (_params) {
	var paramsRequired = ['config'];
	var paramsSpecifics = {};
	try {
		jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
	} catch (e) {
		(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
		return;
	}
	var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
	var paramsAJAX = jeedom.private.getParamsAJAX(params);
	paramsAJAX.url = 'plugins/RaspBEE/core/php/jeeRaspBEEProxy.php';
	paramsAJAX.data = {
		action: 'setDeconzConfig',
		request: _params
	};
	$.ajax(paramsAJAX);
}
