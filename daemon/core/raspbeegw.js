const util = require('util');
var WebSocketClient = require('websocket').client;
var WSclient = new WebSocketClient();

raspbeegw = module.exports = {

	connect : function (host, port) {
		raspbeegw.setup();
		WSclient.connect('ws://10.0.0.19:443/');
	},

	close : function() {
		
	},

	setup : function(){
		
		WSclient.on('connectFailed', function(error) {
			console.log('Connect Error: ' + error.toString());
		});

		WSclient.on('connect', function(connection) {
			console.log('WebSocket Client Connected to RaspBee');
			connection.on('error', function(error) {
				console.log("Connection Error}}: " + error.toString());
			});
			connection.on('close', function() {
				console.log('Connection Closed');
			});
			connection.on('message', function(message) {
				if (message.type === 'utf8') {
					var tempMessage = JSON.parse(message.utf8Data);
					console.log("RAW: '" + message.utf8Data + "'");
					if (tempMessage.r=="sensors" && tempMessage.e=="changed"){
						console.log(util.inspect(tempMessage.state, false, null))
						//console.log("Received: '" + tempMessage.state.buttonevent + "'");
					}
				}
			});
		})
	}
};
