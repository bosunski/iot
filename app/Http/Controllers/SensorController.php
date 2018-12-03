<?php

namespace App\Http\Controllers;

use App\Foundation\PhpMQTT;
use Illuminate\Http\Request;
use App\Value;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SensorController extends Controller
{
	public function __construct(PhpMQTT $mqtt)
	{
		$this->mqtt = $mqtt;
	}

	public function index()
    {

    }

    public function updateSensorValue(Request $request)
    {
        $values = Value::all();
        $data = $request->except(['_token']);

        if(count($values) > 0) {
            $updated = $this->updateValue($data);
	        if ($updated && isset($data['state'])) {
		        $state = $data['state'];

		        $state = $state === "on" ? "1on" : "0off";
		        $this->mqtt->publish('device/state', $state);
	        }

	        return $updated;
        } else {
            return $this->createValue((object) $data);
        }
    }

    public function getLatestValue($deviceId)
    {
        $value = Value::where('device_id', $deviceId)->first();
        return $value ? $value : [];
    }

    public function showSpeedometer($deviceId)
    {
        $data['speedometer'] = $this->getLatestValue($deviceId);
        return view('speedometer', $data);
    }

    public function showEnergyPage($deviceId)
    {
        $data['value'] = $this->getLatestValue($deviceId);
        $data['deviceId'] = $deviceId;
        return view('energy', $data);
    }


    private function createValue($data)
    {
        $value = new Value;
        $value->time = $data->time;
        $value->temperature = $data->temperature;
        $value->volatge = $data->voltage;
        $value->current = $data->current;
        $value->energy = $data->energy;
        $value->power = $data->power;
        $value->save();
        return $value;
    }

    private function updateValue($data)
    {
        $value = $this->getLatestValue();
        $value = Value::where('id', $value->id)->update($data);
        // $value->time = $data->time;
        // $value->temperature = $data->temperature;
        // $value->volatge = $data->voltage;
        // $value->current = $data->current;
        // $value->energy = $data->energy;
        // $value->power = $data->power;
        // $value->save();
        return $value;
    }
}
