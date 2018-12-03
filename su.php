<?php
require 'vendor/autoload.php';
use Workerman\Worker;
$worker = new Worker();
$worker->onWorkerStart = function() {
	$mqtt = new Workerman\Mqtt\Client('mqtt://192.168.8.124:1883', ['username' => 'bosunski', 'password' => 'gabriel10']);

	$mqtt->onConnect = function($mqtt) {
		$mqtt->subscribe('test');
		$mqtt->subscribe('hot');
	};

	$mqtt->onMessage = function($topic, $content){
		echo $topic, ' => ', $content, PHP_EOL;
	};

	$mqtt->connect();
};

Worker::runAll();
