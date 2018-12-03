<?php

namespace App\Console\Commands;

use App\Foundation\PhpMQTT;
use ElephantIO\Engine\SocketIO\Version1X;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Console\Command;
use Gos\Component\WebSocketClient\Wamp\Client;
use PHPSocketIO\SocketIO;


class TestSocketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the Socket server';
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
     */
    public function handle()
    {

	    $client = new \ElephantIO\Client(new Version1X('http://0.0.0.0:2021'));
	    $client->initialize();
	    $client->emit('chat message', ['foo' => 'bar']);
	    $client->close();
    }
}
