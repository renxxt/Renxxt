<?php

namespace App\Http\Resources;

use App\Models\StagedApplicationForm;
use Illuminate\Support\Facades\Auth;

class StagedApplicationFormResource
{
    public function list($id=null)
    {
        if (isset($id)) {
            $result = StagedApplicationForm::where('stagedapplicationforms.userID', $id)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'stagedapplicationforms.deviceID')
                    ->leftjoin('deviceattributes AS A', 'A.attributeID', '=', 'D.attributeID')
                    ->select('A.name AS attribute', 'D.name AS device', 'stagedapplicationforms.applicationID', 'stagedapplicationforms.estimated_pickup_time', 'stagedapplicationforms.estimated_return_time')
                    ->get();
        } else {
            $userID = Auth::user()->id;
            $result = StagedApplicationForm::with('companions:applicationID,U.userID')
                    ->where('stagedapplicationforms.userID', $userID)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'stagedapplicationforms.deviceID')
                    ->leftjoin('deviceattributes AS A', 'A.attributeID', '=', 'D.attributeID')
                    ->select('A.name AS attribute', 'D.name AS device', 'stagedapplicationforms.*')
                    ->get();
        }

        return $result;
    }

    public function store($data)
    {
        $result = StagedApplicationForm::insertGetId([
                    'userID' => $data['userID'],
                    'deviceID' => $data['deviceID'],
                    'companion' => $data['companion'] ?? 0,
                    'estimated_pickup_time' => $data['estimated_pickup_time'],
                    'estimated_return_time' => $data['estimated_return_time'],
                    'target' => $data['target'],
                ]);

        return $result;
    }

    public function show($id)
    {
        $result = StagedApplicationForm::with('companions:applicationID,U.userID,name,uid')
                    ->where('stagedapplicationforms.applicationID', $id)
                    ->leftjoin('users AS U', 'U.userID', '=', 'stagedapplicationforms.userID')
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'stagedapplicationforms.deviceID')
                    ->select('stagedapplicationforms.*', 'U.name', 'D.attributeID')
                    ->first();

        return $result;
    }

    public function update($data)
    {
        $result = StagedApplicationForm::where('applicationID', $data['applicationID'])
                    ->update([
                        'userID' => $data['userID'],
                        'deviceID' => $data['deviceID'],
                        'companion' => $data['companion'] ?? 0,
                        'estimated_pickup_time' => $data['estimated_pickup_time'],
                        'estimated_return_time' => $data['estimated_return_time'],
                        'target' => $data['target'],
                    ]);

        return $result;
    }

    public function delete($id)
    {
        $result = StagedApplicationForm::where('applicationID', $id)->delete();

        return $result;
    }

    public function getDetail($id)
    {
        $result = StagedApplicationForm::where('stagedapplicationforms.applicationID', $id)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'stagedapplicationforms.deviceID')
                    ->select('stagedapplicationforms.estimated_pickup_time', 'stagedapplicationforms.estimated_return_time', 'D.name')
                    ->first();

        return $result;
    }
}
