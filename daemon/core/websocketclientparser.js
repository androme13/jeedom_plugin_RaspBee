const util = require('util');
websocketclientparser = module.exports = {


	process : function (jsondata) {
		//console.log("--------------------------------");
		//console.log("beginprocess",jsondata);
		//console.log("--------------------------------");
		//console.log("websocketclientparser process:");
		// on traite le type (r)
		switch (jsondata.r){
		case 'sensors':
			return(this.sensorsprocess(jsondata));
			break;
		case 'lights':
			return(this.lightsprocess(jsondata));
			break;
		case 'groups':
			return(this.groupsprocess(jsondata));
			break;
		default :
			//console.log("raw: ",jsondata);
		return jsondata;
		}
					
	},
	
	sensorsprocess : function (sensorsobj){
		console.log("--------------------------------");
		console.log("websocketclientparser sensorsprocess:");
		console.log ("raw sensor object:", sensorsobj);
		//console.log("--------------------------------");
		var call = new Object();
		// on traite les events (t)
		switch (sensorsobj.t){
		case 'event':
			// si on a une valeur state pour le sensor
			if (typeof sensorsobj.state !== 'undefined') {
				call.type = sensorsobj.r;
				call.id = sensorsobj.id;
				call.action = sensorsobj.state;
				return call;
			}else
			// si on a une valeur config pour le sensor (battery)
			if (typeof sensorsobj.config !== 'undefined') {
				call.type = sensorsobj.r;
				call.id = sensorsobj.id;
				call.info = sensorsobj.config;
				return call;
			}else
			{
				//console.log("event inconnu: ",sensorsobj);
				return "event inconnu: "+sensorsobj;
			}
			break;
			default :
			//console.log("raw: ",sensorsobj);
			return "raw: "+sensorsobj;
			
		}
	},
	
	lightsprocess : function (lightobj){
		console.log("websocketclientparser lightsprocess:");
		console.log("raw lightobject: ",lightobj);
		//console.log("websocketclientparser sensorsprocess:");
		var call = new Object();
		// on traite les events (t)
		switch (lightobj.t){
		case 'event':
			// si on a une valeur state pour le light
			if (typeof lightobj.state !== 'undefined') {
				call.type = lightobj.r;
				call.id = lightobj.id;
				call.action = lightobj.state;
				return call;
			}else
			{
				//console.log("event inconnu: ",lightobj);
				return "event inconnu: "+JSON.stringify(lightobj);
			}
			break;
			default :
			//console.log("raw: ",lightobj);
			return "raw: "+lightobj;			
		}
	},
	groupsprocess : function (groupsobj){
		console.log("--------------------------------");
		console.log("websocketclientparser groupsprocess:");
		console.log (groupsobj);
		//console.log("--------------------------------");
		var call = new Object();
		// on traite les events (t)
		switch (groupsobj.t){
		case 'event':
			// si on a une valeur state pour le groupe
			if (typeof groupsobj.state !== 'undefined') {
				call.type = groupsobj.r;
				call.id = groupsobj.id;
				call.action = groupsobj.state;
				return call;
			}else
			// si on a une valeur config pour le groupe
			if (typeof groupsobj.config !== 'undefined') {
				call.type = groupsobj.r;
				call.id = groupsobj.id;
				call.info = groupsobj.config;
				return call;
			}else
			{
				//console.log("event inconnu: ",groupsobj);
				return "event inconnu: "+groupsobj;
			}
			break;
			default :
			//console.log("raw: ",sensorsobj);
			return "raw: "+groupsobj;
			
		}
	}
}

// ajout de préirph
//{"e":"added","r":"sensors","sensor":{"config":{"on":true,"reachable":true},"ep":2,"etag":"f87cca45d48d9f4f85299508a888be30","id":"14","manufacturername":"Philips","mode":1,"modelid":"RWL021","name":"RWL021 14","state":{},"type":"ZHASwitch","uniqueid":"00:17:88:01:02:e2:0d:2d-02-fc00"},"t":"event"}