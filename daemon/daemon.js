#!/usr/bin/env node
var AjaxClient = require('./core/ajaxclient.js');
var fs = require("fs");
var path = require('path')
var process = require('process');
var raspbee = require('./core/raspbeegw.js');
var pidfile = require('./core/pidfile.js');
var server;
var url = require('url');
global.apikey;
global.jurl;
global.rurl;
global.wsp;

process.on('uncaughtException', function(err) {
	console.log(JSON.stringify(process.memoryUsage()));
	console.error("Erreur inattendue, arrêt du daemon. " + err + ", stacktrace: " + err.stack);
	return process.exit(1);
});

process.on('exit', function () {
	cleanexit();
});

process.on('SIGINT', function() {
	cleanexit();
});

process.on('SIGTERM', function() {
	cleanexit();
});

function cleanexit(){
	console.log("Arrêt du daemon en cours ...");
	if (typeof server !== 'undefined') server.close();
	pidfile.removepidfile();
	process.exit();
}

/*function checkcfgfile (){
	try {
		return fs.readFileSync(path.resolve(__dirname, 'config/default.json'), 'UTF-8');
	} catch (err) {
		console.log ("Problème avec le fichier de configuration :",err);
		return 0;
	}
}*/

function websocketCallBack(jsondata){
	try {
		AjaxClient.sendPOST(jsondata);
	}
	catch (err){
		console.log("websocketcallback error : ",err);
	}
}

function findlaunchparam($key){
	var args = process.argv;
	for (var i = 0, len = args.length; i < len; i++) {
		var res = args[i].split("=");
		if (res[0]==$key) return res[1];
	}
	return null;
}

console.log("Lancement du daemon (pid :"+process.pid+")");
if (pidfile.createpidfile()==1){
	global.apikey=findlaunchparam("apikey");
	global.jurl=findlaunchparam("jurl");
	global.rurl=findlaunchparam("rurl");
	global.wsp=findlaunchparam("wsp");
	console.log(process.argv);
	var start = true;
	if (global.apikey==null){
		console.log('Le paramètre "apikey" est manquant');
		start = false;
	}
	if (global.jurl==null){
		console.log('Le paramètre "jurl" (adresse de jeedom) est manquant');
		start = false;
	}
	if (global.rurl==null){
		console.log('Le paramètre "rurl" (adresse de RaspBEE) est manquant');
		start = false;
	}
	if (global.wsp==null){
		console.log('Le paramètre "wsp" (port websocket) est manquant');
		start = false;
	}
	if (start==true){
		raspbee.connect(rurl,wsp,websocketCallBack);
	}
}
else
console.log('Impossible de creer le fichier PID : ARRET du daemon');
