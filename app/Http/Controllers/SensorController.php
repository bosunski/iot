<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Value;

class SensorController extends Controller
{
    public function index()
    {

    }

    public function updateSensorValue(Request $request)
    {
        $values = Value::all();
        $data = (object)$request->all();
        if(count($values) > 0) {
            return $this->updateValue($data);
        } else {
            return $this->createValue($data);
        }
    }

    public function getLatestValue()
    {
        $value = Value::where('id', '!=', null)->first();
        return $value ? $value : [];
    }

    public function showSpeedometer()
    {
        $data['speedometer'] = $this->getLatestValue();
        return view('speedometer', $data);
    }


    private function createValue($data)
    {
        $value = new Value;
        $value->value = $data->value;
        $value->save();
        return $value;
    }

    private function updateValue($data)
    {
        $value = $this->getLatestValue();
        $value->value = $data->value;
        $value->save();
        return $value;
    }
}
