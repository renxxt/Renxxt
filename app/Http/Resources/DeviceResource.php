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

    public function chartList($id)
    {
        if ($id == 0) {
            $result = Device::select('name as category')
                        ->where('display', '<', 2)
                        ->get();
        } else {
            $result = Device::where('attributeID', $id)
                        ->select('name as category')
                        ->where('display', '<', 2)
                        ->get();
        }

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

    public function getDevice($data)
    {
        $result = Device::where('attributeID', $data['attributeID'])
                    ->select('devices.*')
                    ->whereNotIn('devices.deviceID', function ($query) use ($data) {
                        $query->select('A.deviceID')
                            ->from('applicationforms as A')
                            ->where(function ($query) use ($data) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('A.estimated_pickup_time', '>=', $data['estimated_pickup_time'])
                                        ->where('A.estimated_pickup_time', '<=', $data['estimated_return_time']);
                                })
                                ->orWhere(function ($query) use ($data) {
                                    $query->where('A.estimated_return_time', '>=', $data['estimated_pickup_time'])
                                        ->where('A.estimated_return_time', '<=', $data['estimated_return_time']);
                                });
                            })
                            ->where('A.state', '<', 3);
                    })->get();

            return $result;
    }

    public function getDevices($data)
    {
        $result = Device::where('attributeID', $data['attributeID'])
                    ->where('display', 0)
                    ->select('devices.*')
                    ->whereNotIn('devices.deviceID', function ($query) use ($data) {
                        $query->select('A.deviceID')
                            ->from('applicationforms as A')
                            ->where('A.applicationID', '<>', $data['applicationID'])
                            ->where(function ($query) use ($data) {
                                $query->where(function ($query) use ($data) {
                                    $query->where('A.estimated_pickup_time', '>=', $data['estimated_pickup_time'])
                                        ->where('A.estimated_pickup_time', '<=', $data['estimated_return_time']);
                                })
                                ->orWhere(function ($query) use ($data) {
                                    $query->where('A.estimated_return_time', '>=', $data['estimated_pickup_time'])
                                        ->where('A.estimated_return_time', '<=', $data['estimated_return_time']);
                                });
                            })
                            ->where('A.state', '<', 3);
                    })->get();

        return $result;
    }
}
