try{
	var request = require('ajax-request');
}
catch (err){
	Console.log(err);
}

ajaxclient = module.exports = {
	
	
sendPOST: function (DATA){
					console.log("sendpost"+global.jurl+"?apikey="+global.apikey);
		request.post({
url: global.jurl+"?apikey="+global.apikey,
data: DATA,
headers: {}
		},function(err, res, body){
			try{
				//console.log("data:"+JSON.stringify(DATA));
				//console.log("ajax client jeedom return body:",JSON.stringify(body));
				//console.log("ajax client jeedom return err:",err);
				//console.log(res);
				//console.log(res.statusCode);
				//console.log(res.statusMessage);
				//console.log(res.rawHeaders);
				//console.log(body);
			}
			catch(err){
				console.log("ajaxclient error",err);
			}
		});
		
	}
}