<?php

//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("127.0.0.1", 9501); 

//
$table = new swoole_table(1024);
$table->column('fd', swoole_table::TYPE_INT);
$table->column('from_id', swoole_table::TYPE_INT);
$table->column('data', swoole_table::TYPE_STRING, 64);
$table->create();


//将table保存在serv对象上
$serv->table = $table;

//监听连接进入事件
$serv->on('connect', function ($serv, $fd) {  
    echo "Client: Connect.\n";
    
    $serv->table->set($fd, array("from_id"=>'text','fd'=>$fd, 'data'=>'data'));
});



$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	echo $data;
    $ret = $serv->table->set($fd, array('from_id' => $data, 'fd' => $fd, 'data' => $data));
    $data = explode(":", $data);
    $serv->send($fd, $data[1]);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});










//启动服务器
$serv->start(); 