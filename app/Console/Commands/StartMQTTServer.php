<?php

namespace App\Console\Commands;

use App\Foundation\MqttHandler;
use App\Foundation\MqttHelper;
use App\Value;
use Illuminate\Console\Command;
use PHPSocketIO\SocketIO;
use Workerman\Mqtt\Client as M;
use Workerman\Mqtt\Client;
use Workerman\Worker;


use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\Connection;
use BinSoul\Net\Mqtt\DefaultMessage;
use BinSoul\Net\Mqtt\DefaultSubscription;
use BinSoul\Net\Mqtt\Message;
use BinSoul\Net\Mqtt\Subscription;
use React\Socket\DnsConnector;
use React\Socket\TcpConnector;


class StartMQTTServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the MQTT Server';

	private $devices;

	/**
	 * @var \Illuminate\Foundation\Application|Client
	 */
	private $mqtt;

	/**
	 * @var MqttHelper
	 */
	private $mqttHelper;

	/**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->mqtt = app(Client::class);
    	$this->mqttHelper = app()->make(MqttHelper::class);
    	$this->devices = collect([]);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//		$this->setArgs()->bootMqttClient();
	    $this->runNow();
    }

    protected function bootMqttClient(): self
    {
	    $worker = new Worker();

		$ws = $this->mqttHelper->getWorkerStartCallback();
	    $worker->onWorkerStart = $ws->bindTo($this->mqttHelper);
	    $worker->run();
    }

	private function runNow()
	{
		$loop = \React\EventLoop\Factory::create();
		$dnsResolverFactory = new \React\Dns\Resolver\Factory();
		$connector = new DnsConnector(new TcpConnector($loop), $dnsResolverFactory->createCached('8.8.8.8', $loop));
		$client = new ReactMqttClient($connector, $loop);

		// Bind to events
		$client->on('open', function () use ($client) {
			// Network connection established
			echo sprintf("Open: %s:%s\n", $client->getHost(), $client->getPort());
		});

		$client->on('close', function () use ($client, $loop) {
			// Network connection closed
			echo sprintf("Close: %s:%s\n", $client->getHost(), $client->getPort());

			$loop->stop();
		});

		$client->on('connect', function (Connection $connection) {
			// Broker connected
			echo sprintf("Connect: client=%s\n", $connection->getClientID());
		});

		$client->on('disconnect', function (Connection $connection) {
			// Broker disconnected
			echo sprintf("Disconnect: client=%s\n", $connection->getClientID());
		});

		$client->on('message', function (Message $message) {
			// Incoming message
			(new MqttHandler)->getGlobalTopicHandler($message);
		});

		$client->on('warning', function (\Exception $e) {
			echo sprintf("Warning: %s\n", $e->getMessage());
		});

		$client->on('error', function (\Exception $e) use ($loop) {
			echo sprintf("Error: %s\n", $e->getMessage());

			$loop->stop();
		});

		// Connect to broker
		$client->connect(config('mqtt.host'))->then(
			function () use ($client) {
				// Subscribe to all topics
				$client->subscribe( new DefaultSubscription( '#' ) )
				       ->then( function ( Subscription $subscription ) {
					       echo sprintf( "Subscribe: %s\n", $subscription->getFilter() );
				       } )
				       ->otherwise( function ( \Exception $e ) {
					       echo sprintf( "Error: %s\n", $e->getMessage() );
				       } );
			}
		);

		$loop->run();
	}
}
