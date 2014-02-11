<?php

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| We use a default files handler but for production we will forget about it
| and use a more robust solution. We will format our application errors
| using a logstash formatter and later push them via UDP to our
| Logstash + Kibana instance.
|
*/

$monolog = Log::getMonolog();
$monolog->popHandler();

$appName = 'ipalaus-api' . (App::runningInConsole() ? '-cli' : '');
$formatter = new Monolog\Formatter\LogstashFormatter($appName);

$handler = new Monolog\Handler\SocketHandler('udp://polymorphic.ipalaus.com:3009');
$handler->setPersistent(true);
$handler->setFormatter($formatter);

$monolog->pushHandler($handler, Monolog\Logger::DEBUG);

/*
|--------------------------------------------------------------------------
| Disable Query Log
|--------------------------------------------------------------------------
|
| If we disable the query log we won't be able to dump the queries that our
| application has ran but will also help decreasing the memory consumption.
|
*/

DB::disableQueryLog();
