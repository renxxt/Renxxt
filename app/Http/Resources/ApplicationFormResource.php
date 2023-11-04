<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm;
use Illuminate\Support\Facades\DB;

class ApplicationFormResource
{
    public function list($data)
    {
        if ($data['attributeID'] == 0) {
            $result = ApplicationForm::orderBy('applicationforms.state', 'asc')
                        ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                        ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                        ->where('applicationforms.state', '<', 4)
                        ->where(function ($query) use ($data) {
                            $query->where(function ($query) use ($data) {
                                $query->where('estimated_pickup_time', '>=', $data['date'])
                                    ->where('estimated_pickup_time', '<', $data['next']);
                            })
                            ->orWhere(function ($query) use ($data) {
                                $query->where('estimated_return_time', '>=', $data['date'])
                                    ->where('estimated_return_time', '<', $data['next']);
                            });
                        })
                        ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device',
                            DB::raw('CASE
                                WHEN applicationforms.state = 0 THEN "#FF8A00"
                                WHEN applicationforms.state = 1 THEN "#048A21"
                                WHEN applicationforms.state = 2 THEN "#409AED"
                                ELSE "#A6A6A6"
                                END AS color'
                            ),
                            DB::raw('CONCAT(applicationforms.estimated_pickup_time, " ~ ", applicationforms.estimated_return_time) AS time'),
                            DB::raw('LEFT(applicationforms.estimated_pickup_time, 4) AS startYear'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 6, 2) AS startMonth'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 9, 2) AS startDay'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 12, 2) AS startHour'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 15, 2) AS startMinute'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 18, 2) AS startSecond'),
                            DB::raw('LEFT(applicationforms.estimated_return_time, 4) AS endYear'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 6, 2) AS endMonth'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 9, 2) AS endDay'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 12, 2) AS endHour'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 15, 2) AS endMinute'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 18, 2) AS endSecond'),
                        )->get();
        } else {
            $result = ApplicationForm::orderBy('applicationforms.state', 'asc')
                        ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                        ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                        ->where('DA.attributeID', $data['attributeID'])
                        ->where('applicationforms.state', '<', 4)
                        ->where(function ($query) use ($data) {
                            $query->where(function ($query) use ($data) {
                                $query->where('estimated_pickup_time', '>=', $data['date'])
                                    ->where('estimated_pickup_time', '<', $data['next']);
                            })
                            ->orWhere(function ($query) use ($data) {
                                $query->where('estimated_return_time', '>=', $data['date'])
                                    ->where('estimated_return_time', '<', $data['next']);
                            });
                        })
                        ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device',
                                DB::raw('CASE
                                WHEN applicationforms.state = 0 THEN "#FF8A00"
                                WHEN applicationforms.state = 1 THEN "#048A21"
                                WHEN applicationforms.state = 2 THEN "#409AED"
                                ELSE "#A6A6A6"
                                END AS color'
                            ),
                            DB::raw('CONCAT(applicationforms.estimated_pickup_time, " ~ ", applicationforms.estimated_return_time) AS time'),
                            DB::raw('LEFT(applicationforms.estimated_pickup_time, 4) AS startYear'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 6, 2) AS startMonth'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 9, 2) AS startDay'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 12, 2) AS startHour'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 15, 2) AS startMinute'),
                            DB::raw('SUBSTRING(applicationforms.estimated_pickup_time, 18, 2) AS startSecond'),
                            DB::raw('LEFT(applicationforms.estimated_return_time, 4) AS endYear'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 6, 2) AS endMonth'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 9, 2) AS endDay'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 12, 2) AS endHour'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 15, 2) AS endMinute'),
                            DB::raw('SUBSTRING(applicationforms.estimated_return_time, 18, 2) AS endSecond'),
                        )->get();
        }

        return $result;
    }

    public function detail($id)
    {
        $result = ApplicationForm::where('applicationforms.applicationID', $id)
                    ->leftjoin('devices AS D', 'D.deviceID', '=', 'applicationforms.deviceID')
                    ->leftjoin('deviceattributes AS DA', 'DA.attributeID', '=', 'D.attributeID')
                    ->leftjoin('users AS U', 'U.userID', '=', 'applicationforms.userID')
                    ->leftjoin('departments AS DE', 'DE.departmentID', '=', 'U.departmentID')
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device', 'DE.department')
                    ->first();

        return $result;
    }

    public function applicationList($id)
    {
        $result = ApplicationForm::with(['companions:applicationID,U.userID,name,uid', 'approved:applicationID,U.userID,name', 'pickupformanswers:*'])
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
                    ->select('applicationforms.*', 'DA.name AS attribute', 'D.name AS device', 'DA.pickup_form', 'DA.return_form')
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
