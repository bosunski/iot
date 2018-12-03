<?php

namespace App\Foundation;


use Workerman\Mqtt\Client;

class MqttHelper {
	/**
	 * @var \Illuminate\Foundation\Application|Client
	 */
	private $mqtt;

	/**
	 * MqttHelper constructor.
	 */
	public function __construct()
	{
		$this->mqtt = app(Client::class);
	}

	public function getWorkerStartCallback(): callable
	{
		return function () {
				$this->mqtt->onConnect = function(Client $mqtt) {
					$mqtt->subscribe('data/updated');
					$mqtt->subscribe('device/new');
					$mqtt->subscribe('device/state');
				};

				$mqtt = $this->mqtt;

				$this->mqtt->onMessage = function($topic, $content) use($mqtt) {
				if ($topic === 'device/new') {
					$deviceID = $content;
//					$this->devices->push($deviceID);
				}

				if ($topic === 'device/state') {
					$data = json_decode($content);
					$mqtt->publish("device/$data->clientId/hello", "Hello man");
					var_dump($content);
				}

				if ($topic === 'data/updated') {
					$this->updateData($content);
				}
			};

			$this->mqtt->connect();
		};
	}
}