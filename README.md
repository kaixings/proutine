# proutine
php rustic coroutine


### 介绍
使用PHP yield 来模拟类似go、swoole的简易协程

参照鸟哥 [在PHP中使用协程实现多任务调度](https://www.laruence.com/2015/05/28/3038.html) 文章相关的代码。

利用yield生成器去遍历非阻塞io（mysqli的异步模式、curl_multi的非阻塞情况）的“句柄”，来达到同时进行的并发效果；

### 安装
``` bash
composer require kaixings/proutine
```

### 例子
``` php
use Kaixings\Proutine\CurlMulti;
use Kaixings\Proutine\Mysql;
use Kaixings\Proutine\Scheduler;

$start = microtime(true);

$curlM = new CurlMulti();
//example your own domain1
$curlGen = $curlM->curl(['url'=>'http://domain1.com']);

//example your own domain2
$curlGen2 = $curlM->curl(['url'=>'http://domain2.com']);

$mysql = new Mysql([
    'host' => 'host',
    'username'=>'root',
    'database' => 'database',
    'password' => 'password',
    'port' =>3306,
]);

$mysqlGen = $mysql->queryGen("select sleep(1);");

$scheduler = new Scheduler();
$scheduler->newTask($curlGen);
$scheduler->newTask($curlGen2);
$scheduler->newTask($mysqlGen);
$scheduler->run();
```


### 许可证
proutine是根据MIT许可证发布的. 有关更多信息，请参见 [LICENSE](LICENSE) 文件.