<?php

namespace App\Console\Commands;

use App\Foundation\PhpMQTT;
use Illuminate\Console\Command;
use PHPSocketIO\SocketIO;

use BinSoul\Net\Mqtt\Client\React\ReactMqttClient;
use BinSoul\Net\Mqtt\DefaultMessage;
use BinSoul\Net\Mqtt\DefaultSubscription;
use BinSoul\Net\Mqtt\Message;
use BinSoul\Net\Mqtt\Subscription;
use React\Socket\DnsConnector;
use React\Socket\TcpConnector;

class TestMqttCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests the MQTT server';
	private $io;
	/**
	 * @var \Illuminate\Foundation\Application|PhpMQTT
	 */
	private $mqtt;

	/**
	 * Create a new command instance.
	 *
	 * @param SocketIO $io
	 */
    public function __construct(SocketIO $io)
    {
    	$this->io = $io;
    	$this->mqtt = app(PhpMQTT::class);
        parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    public function handle()
    {
		$this->runNow();
    }


	private function runNow()
	{
		$loop = \React\EventLoop\Factory::create();
		$dnsResolverFactory = new \React\Dns\Resolver\Factory();
		$connector = new DnsConnector(new TcpConnector($loop), $dnsResolverFactory->createCached('8.8.8.8', $loop));
		$client = new ReactMqttClient($connector, $loop);

		// Connect to broker
		$client->connect(config('mqtt.host'))->then(
			function () use ($client) {
				// Subscribe to all topics
				$client->subscribe(new DefaultSubscription('#'))
				       ->then(function (Subscription $subscription) {
					       echo sprintf("Subscribe: %s\n", $subscription->getFilter());
				       })
				       ->otherwise(function (\Exception $e) {
					       echo sprintf("Error: %s\n", $e->getMessage());
				       });

				// Publish humidity once
				$client->publish(new DefaultMessage('sensors/humidity', '55%'))
				       ->then(function (Message $message) {
					       echo sprintf("Publish: %s => %s\n", $message->getTopic(), $message->getPayload());
				       })
				       ->otherwise(function (\Exception $e) {
					       echo sprintf("Error: %s\n", $e->getMessage());
				       });

				// Publish a random temperature every 10 seconds
				$generator = function () {
//					return mt_rand(-20, 30);
					$volt = random_int(200, 5000);
					$data = json_encode(['state' => "on", "voltage" => $volt]);
					return $data;
				};



				$client->publishPeriodically(1, new DefaultMessage('device/adasddadd/value'), $generator)
				       ->progress(function (Message $message) {
					       echo sprintf("Publish: %s => %s\n", $message->getTopic(), $message->getPayload());
				       })
				       ->otherwise(function (\Exception $e) {
					       echo sprintf("Error: %s\n", $e->getMessage());
				       });
			}
		);

		$loop->run();
	}
}
