var fs = require("fs");
var process = require('process');
pidfile = module.exports = {
createpidfile:function(){
	try {
		fs.writeFileSync("/tmp/raspbee.pid", process.pid, "UTF-8");
		return 1;
	} catch (err) {
			return 0;
		}
	},
removepidfile: function(){
		fs.unlink('/tmp/raspbee.pid', (err) => {
			console.log('successfully deleted /tmp/hello');
		});
	},
checkpidfile: function (){
		try {
			return fs.readFileSync('/tmp/raspbee.pid', 'utf8');
		} catch (err) {
			return 0;
		}
	}
}

