<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm;

class ApplicationManagementResource
{
    public function applicationList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'approved:applicationID,U.userID,approvedapplication.created_at', 'pickupformanswers:*'])
                    ->where('applicationforms.state', '<', 3)
                    ->orderBy('applicationforms.state', 'asc')
                    ->leftjoin('users AS U', 'U.userID', '=', 'applicationforms.userID')
                    ->leftjoin('departments AS DP', 'DP.departmentID', '=', 'U.departmentID')
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device', 'U.name', 'DP.department')
                    ->whereIn('applicationforms.userID', function ($query) use ($id) {
                        $query->select('U.userID')
                            ->from('users as U')
                            ->where('U.superiorID', $id)
                            ->where('U.state', 0);
                    })->get();

        return $result;
    }

    public function cancelList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'cancel'])
                    ->where('applicationforms.state', 4)
                    ->leftjoin('users AS U', 'U.userID', '=', 'applicationforms.userID')
                    ->leftjoin('departments AS DP', 'DP.departmentID', '=', 'U.departmentID')
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device', 'U.name', 'DP.department')
                    ->whereIn('applicationforms.userID', function ($query) use ($id) {
                        $query->select('U.userID')
                            ->from('users as U')
                            ->where('U.superiorID', $id)
                            ->where('U.state', 0);
                    })->get();

        return $result;
    }

    public function completedList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'approved:applicationID,U.userID,approvedapplication.created_at'])
                    ->where('applicationforms.state', 3)
                    ->leftjoin('users AS U', 'U.userID', '=', 'applicationforms.userID')
                    ->leftjoin('departments AS DP', 'DP.departmentID', '=', 'U.departmentID')
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device', 'U.name', 'DP.department', 'DA.pickup_form', 'DA.return_form')
                    ->whereIn('applicationforms.userID', function ($query) use ($id) {
                        $query->select('U.userID')
                            ->from('users as U')
                            ->where('U.superiorID', $id)
                            ->where('U.state', 0);
                    })->get();

        return $result;
    }

    public function changeState($data)
    {
        $result = ApplicationForm::where('applicationID', $data['applicationID'])
                    ->update([
                        'state' => $data['state']
                    ]);

        return $result;
    }
}
