#!/usr/bin/env node

var config = require('config');
var server;
//var test = require('./config/config.xml')({ xml: xml });
//var GWConfig = require('config.xml')({ xml: xml });
var fs = require("fs");
//var xmlparser = require('xml2json');
var path = require('path')
var process = require('process');
var raspbee = require('./core/raspbeegw.js');
var pidfile = require('./core/pidfile.js');
var state = pidfile.checkpidfile();


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
	console.log("Arrêt du daemon");
	pidfile.removepidfile();
	process.exit();
}

function checkcfgfile (){

	try {
		return fs.readFileSync(path.resolve(__dirname, 'config/default.json'), 'UTF-8');
	} catch (err) {
		console.log ("Problème avec le fichier de configuration :",err);
		return 0;
	}
}


if (state==0) {
	console.log("Lancement du daemon (pid :"+process.pid+")");
	var test = checkcfgfile();
	if (checkcfgfile()==0){
		console.log("Impossible de trouver le fichier de configuration : ARRET du daemon ");			
	} else {		
		if (pidfile.createpidfile()==1)
		raspbee.connect(config.info.ip,config.info.port);
		else
		console.log("Impossible de creer le fichier PID : ARRET du daemon ");			
	}
}
else {
	console.log("Le daemon est deja en train de tourner avec le PID : ",state);
}

