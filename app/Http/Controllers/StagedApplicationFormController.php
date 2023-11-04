<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StagedApplicationFormResource AS StagedApplicationForm;
use App\Http\Resources\ApplicationFormResource AS ApplicationForm;
use App\Http\Resources\DeviceResource AS Device;
use App\Http\Resources\UserResource AS User;
use App\Http\Resources\StagedApplicationCompanionResource AS StagedApplicationCompanion;

class StagedApplicationFormController extends Controller
{
    protected $lib;
    protected $stagedApplicationForm;

    public function __construct(Lib $lib, StagedApplicationForm $stagedApplicationForm)
    {
        $this->lib = $lib;
        $this->stagedApplicationForm = $stagedApplicationForm;
    }

    public function store(Request $request)
    {
        $access = $this->lib->userAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'applicationID' =>  [ 'integer' ],
            'deviceID' => [ 'required', 'integer' ],
            'estimated_pickup_time' => [
                'required',
                'date_format:Y-m-d H:i',
                'before:estimated_return_time'
            ],
            'estimated_return_time' => [
                'required',
                'date_format:Y-m-d H:i',
                'after:estimated_pickup_time'
            ],
            'target' => [ 'required', 'string' ]
        ]);
        $data['userID'] = Auth::user()->userID;
        $companion = array_filter($request->input('companion'), function ($value) {
            return $value !== null;
        });
        $request->merge(['companion' => $companion]);

        if (count($companion) > 0) {
            $data['companion'] = 1;
            $companionList = $request->validate([
                'companion' => [
                    'array',
                    Rule::exists('users', 'uid')
                ]
            ]);
        }

        $resource = new applicationForm();
        $check = $resource->stagedCheck($data);
        if ($check) {
            $messageData = [
                'type' => "danger",
                'message' => "該時段設備已被預借"
            ];
            return back()->with('messageData', [$messageData]);
        }

        $companionResource = new StagedApplicationCompanion();
        if (isset($data['applicationID'])) {
            $companionResource->deleteAll($data['applicationID']);
            $this->stagedApplicationForm->update($data);
            $id = $data['applicationID'];
        } else {
            $id = $this->stagedApplicationForm->store($data);
        }

        if(!empty($companionList)) {
            $userResource = new User();
            foreach($companionList['companion'] as $row) {
                $userID = $userResource->getId($row);
                $companionResource->store($id, $userID['userID']);
            }
        }

        return back();
    }

    public function show(Request $request)
    {
        $access = $this->lib->userAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'applicationID' => [ 'required', 'integer' ]
        ]);
        $result = $this->stagedApplicationForm->show($data['applicationID']);
        $resource = new device();
        $deviceList = $resource->getDevice($result);

        return ["result" => $result, "deviceList" => $deviceList];
    }

    public function delete(Request $request)
    {
        $access = $this->lib->userAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'applicationID' => [ 'required', 'integer' ]
        ]);

        $companionResource = new StagedApplicationCompanion();
        $companionResource->deleteAll($data['applicationID']);
        $result = $this->stagedApplicationForm->delete($data['applicationID']);

        echo($result);
    }
}
