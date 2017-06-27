#Swoole学习笔记
2017-06-27 
##0:经验教训
一定要拿正式的release版本编译运行，不然可能有莫名其妙的错误。

##1.todo：
1. 怎么实现在服务端发送一个文本到各个客户端、

##1. 编译安装

1. 编译：

```
phpize
./configure
make
sudo make install

```

2. 修改配置文件：
extension = /path/to/swoole.so

3. 检查配置是否成功

php -m 查看是否有swoole
php -i |grep php.ini 定位配置文件绝对路径


##2. 碰到的问题
###1. websocket不知道有哪些函数、函数签名有哪些。
###2. 全局变量不能使用怎么办？
###3. todo:怎么接受用户端输入事件？

on（$event, $callback） 这里支持那些event？在哪里查询？

receive、
connect、
close

function onConnect(swoole_server $server, int $fd, int $from_id);

function onReceive(swoole_server $server, int $fd, int $reactor_id, string $data);

function onClose(swoole_server $server, int $fd, int $reactorId);

###4. addTimer方法变得不可用了

```
$id = $serv->addtimer(1000);

$serv->on("Timer", function ($serv, $interval){
	echo "Time";
	echo __LINE__;
});
```

###5. 当事件绑定多个回调函数的时候， 后面的会覆盖前面的函数

```
//监听数据发送事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data);
    echo __LINE__;
    echo $data;
});

$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	echo $data;
    $ret = $serv->table->set($fd, array('from_id' => $data, 'fd' => $fd, 'data' => $data));
    $data = explode(":", $data);
    $serv->send($fd, $data[1]);
});

```

1. 怎么实现在服务端接受用户端输入,并且将该文本发送到各个客户端、