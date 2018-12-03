<?php

namespace App\Providers;

use App\Foundation\PhpMQTT;
use Illuminate\Support\ServiceProvider;
use PHPSocketIO\SocketIO;
use Workerman\Mqtt\Client;

class MQTTServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    	$this->app->singleton(Client::class, function($container) {
    		$connectionString = 'mqtt://' . config('mqtt.host') . ':' . config('mqtt.port');
    		return new Client($connectionString, ['username' => config('mqtt.username'), 'password' => config('mqtt.password')]);
	    });

	    $this->app->singleton(SocketIO::class, function($container) {
		    $io = new SocketIO(2021);
		    return $io;
	    });

	    $this->app->singleton(PhpMQTT::class, function($container) {
		    $mqtt = new PhpMQTT(config('mqtt.host'), config('mqtt.port'), '');
		    $mqtt->connect(true, NULL, config('mqtt.username'), config('mqtt.password'));
		    return $mqtt;
	    });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
