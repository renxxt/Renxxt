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
    protected $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    public function list(Request $request)
    {
        $this->lib->adminAccess();
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
        $this->lib->adminAccess();
        $resource = new DeviceAttribute();
        $attributes = $resource->list();

        return view('serviceManagement.createDevice', ['attributes' => $attributes]);
    }

    public function store(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('devices')
            ],
            'type' => ['required', 'string'],
            'attributeID' => ['required', 'integer'],
            'uid' => [
                'required',
                'string',
                'exists:users,uid,state,0'
            ],
            'storage_location' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'display' => ['string']
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
        $this->lib->adminAccess();
        $resource = new DeviceAttribute();
        $attributes = $resource->list();
        $result = $this->device->show($id);

        return view('serviceManagement.editDevice', ['result' => $result, 'attributes' => $attributes]);
    }

    public function update(Request $request)
    {
        $this->lib->adminAccess();
        $deviceID = $request->input('deviceID');
        $data = $request->validate([
            'deviceID' => ['required', 'integer'],
            'name' => [
                'required',
                'string',
                Rule::unique('devices')->ignore($deviceID, 'deviceID')
            ],
            'type' => ['required', 'string'],
            'attributeID' => ['required', 'integer'],
            'uid' => [
                'required',
                'string',
                'exists:users,uid,state,0'
            ],
            'storage_location' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'display' => ['string']
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
        $this->lib->adminAccess();
        $data = $request->validate([
            'id' => ['required']
        ]);
        $result = $this->device->delete($data['id']);

        echo($result);
    }

    public function changeDisplay(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'deviceID' => ['required', 'integer'],
            'display' => ['required', 'integer']
        ]);

        $result = $this->device->changeDisplay($data);
        return $result;
    }
}
