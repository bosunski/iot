<?php

namespace App\Console\Commands;

use App\Foundation\PhpMQTT;
use Illuminate\Console\Command;
use Workerman\Worker;
use PHPSocketIO\SocketIO;


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
	    for (;;) {
		    $this->mqtt->clientid = str_random(12);
		    $volt = random_int(200, 5000);
		    $data = json_encode(['state' => "on", "voltage" => $volt, 'clientId' => $this->mqtt->clientid]);
		    $this->mqtt->publish("device/state", $data);

		    sleep(1);
	    }
    }
}
