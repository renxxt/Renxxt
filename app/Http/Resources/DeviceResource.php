<?php

namespace App\Http\Resources;

use App\Models\Device;

class DeviceResource
{
    public function list($id)
    {
        $result = Device::where('devices.attributeID', $id)
                    ->select('devices.*', 'U.name AS userName')
                    ->leftjoin('deviceattributes AS A', 'A.attributeID', '=', 'devices.attributeID')
                    ->leftjoin('users AS U', 'U.userID', '=', 'devices.userID')
                    ->where('devices.display', '<', 2)
                    ->get();

        return $result;
    }

    public function store($data)
    {
        $result = Device::insert([
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'attributeID' => $data['attributeID'],
                    'userID' => $data['userID'],
                    'storage_location' => $data['storage_location'],
                    'price' => $data['price'],
                    'display' => $data['display']
                ]);

        return $result;
    }

    public function show($id)
    {
        $result = Device::where('deviceID', $id)
                    ->select('devices.*', 'U.uid')
                    ->leftjoin('users AS U', 'U.userID', '=', 'devices.userID')
                    ->first();

        return $result;
    }

    public function update($data)
    {
        $result = Device::where('deviceID', $data['deviceID'])
                    ->update([
                        'name' => $data['name'],
                        'type' => $data['type'],
                        'attributeID' => $data['attributeID'],
                        'userID' => $data['userID'],
                        'storage_location' => $data['storage_location'],
                        'price' => $data['price'],
                        'display' => $data['display']
                    ]);

        return $result;
    }

    public function delete($id)
    {
        $result = Device::where('deviceID', $id)
                    ->update([
                        'display' => 2
                    ]);

        return $result;
    }

    public function changeDisplay($data)
    {
        $result = Device::where('deviceID', $data['deviceID'])
                    ->update([
                        'display' => $data['display']
                    ]);

        return $result;
    }
}
