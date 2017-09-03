#!/usr/bin/env node

var process = require('process');
var raspbee = require('./core/raspbeegw.js');
var pidfile = require('./core/pidfile.js');
var state = pidfile.checkpidfile();

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

if (state==0) {
	console.log("Lancement du daemon (pid :"+process.pid+")");	
	if (pidfile.createpidfile()==1)
	raspbee.connect();
	else
	console.log("Impossible de creer le fichier PID : ARRET du daemon ");	
}
else {
	console.log("Le daemon est deja en train de tourner avec le PID : ",state);
}

