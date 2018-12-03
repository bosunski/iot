<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;
use PHPSocketIO\SocketIO;


class StartSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the Socket server';

	/**
	 * Create a new command instance.
	 *
	 * @param SocketIO $io
	 */
    public function __construct(SocketIO $io)
    {
    	$this->io = $io;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	global $argv;

    	$argv[1] = $this->argument('action');

    	$this->startSocketServer();
    }

    protected function startSocketServer(): void
    {
	    $io = $this->io;
	    $worker = new Worker();
//	    $worker->onWorkerStart = function () use ($io) {
		    $this->io->on('connection', function($socket) use($io) {
			    $socket->on('data/new', function($msg) use($io) {
				    // Save to data
				    $io->emit('chat message', $msg);
			    });

			    $socket->on('device/off', function($msg) use($io) {
				    // Turn device OFF
				    $io->emit('chat message', $msg);
			    });

			    $socket->on('device/state', function($data) use($io) {
				    // Turn Device ON
				    $io->emit('chat message', $data);
			    });

			    $socket->on('chat message', function($msg)use($io){
				    echo "$msg", PHP_EOL;
				    $io->emit('chat message', $msg);
			    });

			    $io->emit('chat message', "Welcome online");
		    });
//	    };

	    Worker::runAll();
    }
}
