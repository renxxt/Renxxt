<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm;

class ApplicationFormResource
{
    public function applicationList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'approved:applicationID,U.userID,name'])
                    ->where('applicationforms.userID', $id)
                    ->where('applicationforms.state', '<', 3)
                    ->orderBy('applicationforms.state', 'asc')
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device')
                    ->get();

        return $result;
    }

    public function cancelList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'cancel'])
                    ->where('applicationforms.userID', $id)
                    ->where('applicationforms.state', 4)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device')
                    ->get();

        return $result;
    }

    public function completedList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'approved:applicationID,U.userID,name'])
                    ->where('applicationforms.userID', $id)
                    ->where('applicationforms.state', 3)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device')
                    ->get();

        return $result;
    }

    public function store($data)
    {
        $result = ApplicationForm::insertGetId([
                    'uuid' => $data['uuid'],
                    'userID' => $data['userID'],
                    'deviceID' => $data['deviceID'],
                    'companion' => $data['companion'],
                    'estimated_pickup_time' => $data['estimated_pickup_time'],
                    'estimated_return_time' => $data['estimated_return_time'],
                    'target' => $data['target'],
                    'state' => 0
                ]);

        return $result;
    }

    public function show($id)
    {
        $result = ApplicationForm::with('companions:applicationID,U.userID,name,uid')
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.attributeID')
                    ->where('applicationID', $id)
                    ->first();

        return $result;
    }

    public function update($data)
    {
        $result = ApplicationForm::where('applicationID', $data['applicationID'])
                    ->update([
                        'userID' => $data['userID'],
                        'deviceID' => $data['deviceID'],
                        'companion' => $data['companion'] ?? 0,
                        'estimated_pickup_time' => $data['estimated_pickup_time'],
                        'estimated_return_time' => $data['estimated_return_time'],
                        'target' => $data['target']
                    ]);

        return $result;
    }

    public function changeState($data)
    {
        if ($data['state'] == 2) {
            $result = ApplicationForm::where('applicationID', $data['applicationID'])
                        ->update([
                            'pickup_time' => now(),
                            'state' => $data['state']
                        ]);
        } else if ($data['state'] == 3) {
            $result = ApplicationForm::where('applicationID', $data['applicationID'])
                        ->update([
                            'return_time' => now(),
                            'state' => $data['state']
                        ]);
        } else {
            $result = ApplicationForm::where('applicationID', $data['applicationID'])
                        ->update([
                            'state' => $data['state']
                        ]);
        }

        return $result;
    }

    public function stagedCheck($data)
    {
        $result = ApplicationForm::where('deviceID', $data['deviceID'])
                    ->where('estimated_pickup_time', '<=', $data['estimated_pickup_time'])
                    ->where('estimated_return_time', '>=', $data['estimated_return_time'])
                    ->exists();

        return $result;
    }

    public function check($data)
    {
        if (isset($data['extend_time'])) {
            $result = ApplicationForm::where('deviceID', $data['deviceID'])
                        ->where('estimated_pickup_time', '<=', $data['estimated_pickup_time'])
                        ->where('estimated_return_time', '>=', $data['extend_time'])
                        ->where('applicationID', '<>', $data['applicationID'])
                        ->select('estimated_pickup_time', 'estimated_return_time')
                        ->first();
        } else {
            $result = ApplicationForm::where('deviceID', $data['deviceID'])
                        ->where('estimated_pickup_time', '<=', $data['estimated_pickup_time'])
                        ->where('estimated_return_time', '>=', $data['estimated_return_time'])
                        ->where('applicationID', '<>', $data['applicationID'])
                        ->exists();
        }

        return $result;
    }

    public function getAttributeID($id)
    {
        $result = ApplicationForm::where('applicationID', $id)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('DA.attributeID', 'DA.pickup_form', 'DA.return_form', 'applicationforms.state')
                    ->first();

        return $result;
    }

    public function getCheckData($id)
    {
        $result = ApplicationForm::where('applicationID', $id)
                    ->select('deviceID', 'estimated_pickup_time')
                    ->first();

        return $result;
    }

    public function extendTime($data)
    {
        $result = ApplicationForm::where('applicationID', $data['applicationID'])
                    ->update([
                        'estimated_return_time' => $data['extend_time']
                    ]);

        return $result;
    }
}
