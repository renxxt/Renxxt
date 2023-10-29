<?php

namespace App\Http\Resources;

use App\Models\DeviceAttribute;

class DeviceAttributeResource
{
    public function list()
    {
        $result = DeviceAttribute::where('display', '<', 2)->get();

        return $result;
    }

    public function store($data)
    {
        $result = DeviceAttribute::insertGetId([
                    'name' => $data['name'],
                    'display' => $data['display'],
                    'approved_layers' => $data['approved_layers'],
                    'approved_level' => $data['approved_level'],
                    'pickup_form' => $data['pickup_form'],
                    'return_form' => $data['return_form'],
                    'companion_number' => $data['companion_number']
                ]);

        return $result;
    }

    public function show($id)
    {
        $result = DeviceAttribute::with(['pickupForms', 'returnForms'])
                    ->where('attributeID', $id)
                    ->first();

        return $result;
    }

    public function update($data)
    {
        $result = DeviceAttribute::where('attributeID', $data['attributeID'])
                    ->update([
                        'name' => $data['name'],
                        'display' => $data['display'],
                        'approved_layers' => $data['approved_layers'],
                        'approved_level' => $data['approved_level'],
                        'pickup_form' => $data['pickup_form'],
                        'return_form' => $data['return_form'],
                        'companion_number' => $data['companion_number']
                    ]);

        return $result;
    }

    public function delete($id)
    {
        $result = DeviceAttribute::where('attributeID', $id)
                    ->update([
                        'display' => 2
                    ]);

        return $result;
    }

    public function changeDisplay($data)
    {
        $result = DeviceAttribute::where('attributeID', $data['attributeID'])
                    ->update([
                        'display' => $data['display']
                    ]);

        return $result;
    }
}
