<?php

//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 9502);

//todo：保存所有监听客户端列表?
//todo：提供运维工具，查看当前在线用户数
//TODO: 定时器功能，定时返回当前在线用户数


//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    var_dump($request);
    $ws->push($request->fd, "hello, welcome\n");

    $ws->tick(1000, function() use ($ws, $fd){
        foreach ($ws->connections as $fd) {
          $ws->push($fd, "on timer");
        }  
    });
   
});





//监听WebSocket消息事件

$ws->on('message', function ($ws, $frame) {
    echo "Msg:{$frame->data}\n";
    $data = $frame->data+1;
    foreach($ws->connections as $fd){
        $ws->push($fd, "Got $data");
    }
});


//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();