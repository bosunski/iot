<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	public function showNewDeviceForm()
	{
		return view('create-device');
	}

	public function createDevice(Request $request)
	{
		$device = new Device();
		$isSaved = (boolean) $device->fill($request->all())->save();

		return perform([$this, 'goToDeviceList'])
				->once($isSaved)
				->is(true);
	}
}
