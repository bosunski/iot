<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
	private $device;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth']);
		$this->device = new Device;
	}

	public function showNewDeviceForm()
	{
		return view('create-device');
	}

	public function createDevice(Request $request)
	{
		$isSaved = (boolean) $this->device->fill($request->all())->save();

		return perform([$this, 'goToDeviceListWithSuccess'])
				->once($isSaved)
				->is(true);
	}

	public function goToDeviceListWithSuccess()
	{
		return redirect()->route('device.list')->with('status', 'Device created successfully.');
	}

	public function listDevices()
	{
		$user_id = Auth::user()->id;

		$devices = $this->device->newQuery()->where('user_id', $user_id)->get();

		return view('devices', ['devices' => $devices]);
	}
}
