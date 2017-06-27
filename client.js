var wsServer = 'ws://127.0.0.1:9502';
var ws = new WebSocket(wsServer);
ws.onopen = function (evt) {
    console.log("Connected to WebSocket server.");
};

ws.onclose = function (evt) {
    console.log("Disconnected");
};

ws.onmessage = function (evt) {
    console.log('Retrieved data from server: ' + evt.data);
    $('#content').text($("#content").text()+evt.data);
};

ws.onerror = function (evt, e) {
    console.log('Error occured: ' + evt.data);
};

var sendMsg = function(message){
	ws.send(message);
}