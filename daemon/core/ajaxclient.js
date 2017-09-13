try{
var request = require('ajax-request');
const util = require('util');
}
catch (err){
	Console.log(err);
}



ajaxclient = module.exports = {
	
	
sendPOST: function ($DATA,$URL){
	
request.post({
  url: "http://10.0.0.215/plugins/RaspBEE/core/php/jeeRaspBEE.php?apikey=iAVqJ05VEO54rmXDKkRXrYlYMFfRJOa0&test2",
  data: $DATA,
  //data: {"params":{"hello":"bonjour"}},
  headers: {}
},function(err, res, body){
	try{
	//console.log(err);
	//console.log(res);
	console.log(res.statusCode);
	console.log(res.statusMessage);
	//console.log(res.rawHeaders);
	console.log(body);
	}
	catch(err){
			console.log(err);

	}
});
	
	}
}