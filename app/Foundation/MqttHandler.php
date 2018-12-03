<?php

namespace App\Foundation;


use App\Device;
use App\Value;
use BinSoul\Net\Mqtt\Message;

class MqttHandler
{
	static $message;
	static $topic;

	public function getGlobalTopicHandler(Message $message)
	{
		echo 'Message';

		if ($message->isDuplicate()) {
			echo ' (duplicate)';
		}

		if ($message->isRetained()) {
			echo ' (retained)';
		}

		echo ': '.$message->getTopic().' => '.mb_strimwidth($message->getPayload(), 0, 50, '...');

		echo "\n";

		if ($message->getTopic() === 'device/adasddadd/value') {
			$this->updateValue($message->getPayload());
		}
	}

	private function updateData($data)
	{
		return $this->updateValue(json_decode($data, true));
	}

	public function getLatestValue($deviceId)
	{
		$value = Value::where('device_id', $deviceId)->first();
		return $value ? $value : [];
	}

	private function updateValue($data)
	{
		$device = Device::where('unique_id', 'adasddadd')->first();

		$value = $this->getLatestValue($device->id);
		$data = (json_decode($data, true));

		if (!$value) {
			$data = array_merge(['device_id' => $device->id], $data);
			$value = (new Value)->fill($data)->save();

			return $value;
		}

		$value = Value::where('id', $value->id)->update($data);
		return $value;
	}
}