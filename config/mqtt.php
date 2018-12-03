<?php

return [
	'host'    => env('MQTT_HOST'),
	'username'  => env('MQTT_USERNAME'),
	'password'  => env('MQTT_PASSWORD'),
	'port'      => env('MQTT_PORT', 1883),
];