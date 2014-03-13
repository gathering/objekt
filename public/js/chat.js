var BOSH_SERVICE = 'http://obj.no:5280/http-bind'
var connection = null;

function onConnect(status)
{
    if (status == Strophe.Status.CONNECTING) {
		console.log('Strophe is connecting.');
    } else if (status == Strophe.Status.CONNFAIL) {
		console.log('Strophe failed to connect.');
    } else if (status == Strophe.Status.DISCONNECTING) {
		console.log('Strophe is disconnecting.');
    } else if (status == Strophe.Status.DISCONNECTED) {
		console.log('Strophe is disconnected.');
    } else if (status == Strophe.Status.CONNECTED) {
		console.log('Strophe is connected.');
		connection.disconnect();
    }
}

$(document).ready(function() {
	connection = new Strophe.Connection(BOSH_SERVICE);

	connection.connect("cobraz@cobraz.no", "cob33147", onConnect);
});