try{
	var request = require('ajax-request');
	//const util = require('util');
}
catch (err){
	Console.log(err);
}

ajaxclient = module.exports = {
	
	
sendPOST: function ($DATA,$URL){

var tempapi = "iAVqJ05VEO54rmXDKkRXrYlYMFfRJOa0";	
		request.post({
url: global.jurl+"?apikey="+global.apikey,
data: $DATA,
headers: {}
		},function(err, res, body){
			try{
				//console.log(err);
				//console.log(res);
				//console.log(res.statusCode);
				//console.log(res.statusMessage);
				//console.log(res.rawHeaders);
				//console.log(body);
			}
			catch(err){
				console.log(err);
			}
		});
		
	}
}