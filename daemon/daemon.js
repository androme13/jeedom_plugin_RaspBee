#!/usr/bin/env node
var config = require('config');
var AjaxClient = require('./core/ajaxclient.js');

var http = require('http');
var fs = require("fs");
//var xmlparser = require('xml2json');
var path = require('path')
var process = require('process');
var raspbee = require('./core/raspbeegw.js');
var pidfile = require('./core/pidfile.js');
var server;
var state = pidfile.checkpidfile();
var url = require('url');

process.on('uncaughtException', function(err) {
  console.log(JSON.stringify(process.memoryUsage()));
  console.error("An uncaughtException was found, the program will end. " + err + ", stacktrace: " + err.stack);
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

function checkcfgfile (){
	try {
		return fs.readFileSync(path.resolve(__dirname, 'config/default.json'), 'UTF-8');
	} catch (err) {
		console.log ("Problème avec le fichier de configuration :",err);
		return 0;
	}
}

function websocketCallBack(jsondata){
	try {
	AjaxClient.sendPOST("a","a");
	}
	catch (err){
		console.log("websocketcallback error: ",err);
	}
	console.log("websocketcallback",jsondata);
}

function initServer(){
server = http.createServer(function(req, res) {	
//console.dir(req.param);

    if (req.method == 'POST') {
		var page = url.parse(req.url).pathname;
        console.log("POST");
        var body = '';
        req.on('data', function (data) {
			
            body += data;
            console.log("body: " + body);
			console.log(page);
        });
        req.on('end', function () {
            console.log("fin de transaction");
        });
        res.writeHead(200, {'Content-Type': 'application/json'});
        res.end('{"error":"0"}');
    }
    else
    {
        console.log("GET");
        //var html = '<html><body><form method="post" action="http://localhost:3000">Name: <input type="text" name="name" /><input type="submit" value="Submit" /></form></body>';
        //var html = fs.readFileSync('index.html');
        res.writeHead(200, {'Content-Type': 'text/html'});
		res.write("hello");
        res.end();
    }

});

server.listen(8666);
}


if (state==0) {
	console.log("Lancement du daemon (pid :"+process.pid+")");
	var test = checkcfgfile();
	if (checkcfgfile()==0){
		console.log("Impossible de trouver le fichier de configuration : ARRET du daemon ");			
	} else {		
		if (pidfile.createpidfile()==1){
		//raspbee.connect(config.info.ip,config.info.port,websocketCallBack);
		raspbee.connect("10.0.0.19","443",websocketCallBack);
		initServer();
		}
		else
		console.log("Impossible de creer le fichier PID : ARRET du daemon ");			
	}
}
else {
	console.log("Le daemon est deja en train de tourner avec le PID : ",state);
}

