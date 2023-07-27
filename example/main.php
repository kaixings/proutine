<?php
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);


include __DIR__ . DS . '../vendor/autoload.php';

use Kaixings\Proutine\CurlMulti;
use Kaixings\Proutine\Mysql;
use Kaixings\Proutine\Scheduler;

$start = microtime(true);


$curlM = new CurlMulti();
//example your own domain1  best sleep
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
$end = microtime(true);

echo $start.PHP_EOL;
echo $end.PHP_EOL;
echo ($end-$start).PHP_EOL;
echo $curlGen->getReturn().PHP_EOL;
echo $curlGen2->getReturn().PHP_EOL;
