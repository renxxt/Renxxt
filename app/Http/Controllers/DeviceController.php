<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use App\Http\Resources\DeviceAttributeResource AS DeviceAttribute;
use App\Http\Resources\DeviceResource AS Device;
use App\Http\Resources\UserResource AS User;

class DeviceController extends Controller
{
    protected $lib;
    protected $device;

    public function __construct(Lib $lib, Device $device)
    {
        $this->lib = $lib;
        $this->device = $device;
    }

    public function list(Request $request)
    {
        $data = $request->validate([
            'attributeID' => [
                'required',
                'integer'
            ]
        ]);

        $devices = $this->device->list($data['attributeID']);
        return $devices;
    }

    public function create()
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $resource = new DeviceAttribute();
        $attributes = $resource->list();

        return view('serviceManagement.createDevice', ['attributes' => $attributes]);
    }

    public function store(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('devices')
            ],
            'type' => [ 'required', 'string' ],
            'attributeID' => [ 'required', 'integer' ],
            'uid' => [
                'required',
                'string',
                'exists:users,uid,state,0'
            ],
            'storage_location' => [ 'required', 'string' ],
            'price' => [ 'required', 'integer' ],
            'display' => [ 'string' ]
        ]);
        $data['display'] = (isset($data['display']) && $data['display'] == 'on') ? 1 : 0;
        $userResource = new User();
        $userID = $userResource->getId($data['uid']);
        $data['userID'] = $userID['userID'];
        $result = $this->device->store($data);

        return redirect()->route('serviceManagement.list');
    }

    public function show($id)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $resource = new DeviceAttribute();
        $attributes = $resource->list();
        $result = $this->device->show($id);
        if (!$result) {
            $messageData = [
                'type' => "danger",
                'message' => "無該設備"
            ];
            return redirect()->route('serviceManagement.list')->with('messageData', [$messageData]);
        }

        return view('serviceManagement.editDevice', ['result' => $result, 'attributes' => $attributes]);
    }

    public function update(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $deviceID = $request->input('deviceID');
        $data = $request->validate([
            'deviceID' => [ 'required', 'integer' ],
            'name' => [
                'required',
                'string',
                Rule::unique('devices')->ignore($deviceID, 'deviceID')
            ],
            'type' => [ 'required', 'string' ],
            'attributeID' => [ 'required', 'integer' ],
            'uid' => [
                'required',
                'string',
                'exists:users,uid,state,0'
            ],
            'storage_location' => [ 'required', 'string' ],
            'price' => [ 'required', 'integer' ],
            'display' => [ 'string' ]
        ]);
        $data['display'] = (isset($data['display']) && $data['display'] == 'on') ? 1 : 0;
        $resource = new User();
        $userID = $resource->getId($data['uid']);
        $data['userID'] = $userID['userID'];
        $result = $this->device->update($data);

        return redirect()->route('serviceManagement.list');
    }

    public function delete(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'id' => [ 'required', 'integer' ]
        ]);
        $result = $this->device->delete($data['id']);

        echo($result);
    }

    public function changeDisplay(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'deviceID' => [ 'required', 'integer' ],
            'display' => [ 'required', 'integer' ]
        ]);

        $result = $this->device->changeDisplay($data);
        return $result;
    }

    public function getDevice(Request $request)
    {
        $data = $request->validate([
            'applicationID' => [ 'integer' ],
            'attributeID' => [ 'required', 'integer' ],
            'estimated_pickup_time' => [
                'required',
                'before:estimated_return_time'
            ],
            'estimated_return_time' => [
                'required',
                'after:estimated_pickup_time'
            ]
        ]);

        if (isset($data['applicationID'])) {
            $result = $this->device->getDevices($data);
        } else {
            $result = $this->device->getDevice($data);
        }

        return $result;
    }
}
