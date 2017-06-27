<?php

$client = new swoole_client(SWOOLE_SOCK_TCP);

//连接到服务器
if (!$client->connect('127.0.0.1', 9501, 0.5))
{
    die("connect failed.");
}


//向服务器发送数据
if (!$client->send("hello world"))
{
    die("send failed.");
}

// //从服务器接收数据
// $data = $client->recv();
// if (!$data)
// {
//     die("recv failed.");
// }
// echo $data;

//todo:怎么接受用户端输入事件？

//$t = time();
//$cnt = 0;

//每隔2000ms触发一次
$tid1 = swoole_timer_tick(2000, function ($timer_id) {
    echo $timer_id."tick-2000ms\n";
    //$cnt++;
    //$client->send($cnt." ".$t);
}, 1);

$tid2 = swoole_timer_tick(1000, function ($timer_id) {
    echo $timer_id."tick-2000ms\n";
    //$cnt++;
    //$client->send($cnt." ".$t);

   
}, 2);

do{
	$data = $client->recv();
	if(!$data){
		echo "Not recev data";
	}
	echo "recv $data";
	$data = $data+1;
	$data = $client->send("got:$data");
}while(true);

//关闭连接
$client->close();

