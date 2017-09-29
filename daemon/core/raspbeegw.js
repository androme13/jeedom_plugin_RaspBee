const util = require('util');
var WebSocketClient = require('websocket').client;
var WebSocketClientParser = require('./websocketclientparser.js');
var WSclient = new WebSocketClient();

raspbeegw = module.exports = {
	connect : function (host, port, callback) {
		raspbeegw.setup(callback);
		WSclient.connect('ws://'+host+':'+port);
	},
	close : function() {
		
	},
	setup : function(callback){		
		WSclient.on('connectFailed', function(error) {
			console.log('Connect Error: ' + error.toString());
		});
		WSclient.on('connect', function(connection) {
			console.log('Client WebSocket connecté à la passerelle RaspBee');
			connection.on('error', function(error) {
				console.log("Connection Error}}: " + error.toString());
			});
			connection.on('close', function() {
				console.log('Connection Closed');
			});
			connection.on('message', function(message) {
				if (message.type === 'utf8') {					
						try{
						callback(WebSocketClientParser.process(JSON.parse(message.utf8Data)));
						}
						catch (err){
							console.log(err);
						}
				}
			});
		});		
		}
};
