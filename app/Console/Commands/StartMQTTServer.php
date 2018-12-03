<?php

namespace App\Console\Commands;

use App\Foundation\MqttHelper;
use App\Value;
use Illuminate\Console\Command;
use PHPSocketIO\SocketIO;
use Workerman\Mqtt\Client as M;
use Workerman\Mqtt\Client;
use Workerman\Worker;

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
		$this->setArgs()->bootMqttClient();
    }

    protected function bootMqttClient(): self
    {
	    $worker = new Worker();

		$ws = $this->mqttHelper->getWorkerStartCallback();
	    $worker->onWorkerStart = $ws->bindTo($this->mqttHelper);
	    $worker->run();
    }

    private function updateData($data)
    {
    	return $this->updateValue(json_decode($data, true));
    }

	public function getLatestValue()
	{
		$value = Value::where('id', '!=', null)->first();
		return $value ? $value : [];
	}

	private function updateValue($data)
	{
		$value = $this->getLatestValue();
		$value = Value::where('id', $value->id)->update($data);
		return $value;
	}

	protected function setArgs(): self
	{
		global $argv;

		$argv[1] = $this->argument('action');

		return $this;
	}
}
