<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ApplicationFormResource AS ApplicationForm;
use App\Http\Resources\StagedApplicationFormResource AS StagedApplicationForm;
use App\Http\Resources\StagedApplicationCompanionResource AS StagedApplicationCompanion;
use App\Http\Resources\DeviceAttributeResource AS DeviceAttribute;
use App\Http\Resources\DeviceResource AS Device;
use App\Http\Resources\UserResource AS User;
use App\Http\Resources\ApplicationCompanionResource AS ApplicationCompanion;
use App\Http\Resources\CancelApplicationResource AS CancelApplication;

class ApplicationFormController extends Controller
{
    protected $lib;
    protected $applicationForm;

    public function __construct(Lib $lib, ApplicationForm $applicationForm)
    {
        $this->lib = $lib;
        $this->applicationForm = $applicationForm;
    }

    public function applicationList()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $result = $this->applicationForm->applicationList($userID);

        return view('applicationForm.applicationList', ['result' => $result]);
    }

    public function cancelList()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $result = $this->applicationForm->cancelList($userID);

        return view('applicationForm.cancelList', ['result' => $result]);
    }

    public function completedList()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $result = $this->applicationForm->completedList($userID);

        return view('applicationForm.completedList', ['result' => $result]);
    }

    public function create()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $resource = new DeviceAttribute();
        $attributes = $resource->list();
        $stagedResource = new stagedApplicationForm();
        $stagedList = $stagedResource->list($userID);

        return view('applicationForm.createForm', ['attributes' => $attributes, 'stagedList' => $stagedList]);
    }

    public function store()
    {
        $this->lib->adminAccess();
        $stagedResource = new StagedApplicationForm();
        $list = $stagedResource->list();
        if (count($list) == 0) {
            $messageData = [
                'type' => "notFound"
            ];

            return response()->json(['messageData' => $messageData]);
        }

        $companionResource = new StagedApplicationCompanion();
        $resource = new ApplicationCompanion();
        foreach ($list as $row) {
            $check = $this->applicationForm->check($row);

            if ($check) {
                $detail = $stagedResource->getDetail($row['applicationID']);
                $messageData = [
                    'type' => "danger",
                    'message' => $detail
                ];
                return response()->json(['messageData' => $messageData]);
            }
            $row['uuid'] = 'A' . strtotime(now());
            $id = $this->applicationForm->store($row);

            if ($row['companion']) {
                foreach ($row['companions'] as $user) {
                    $user['id'] = $id;
                    $resource->store($user);
                    $companionResource->delete($user);
                }
            }
            $stagedResource->delete($row['applicationID']);
        }

        $result = [
            'type' => "success"
        ];
        return response()->json(['result' => $result]);
    }

    public function show($id)
    {
        $this->lib->adminAccess();
        $result = $this->applicationForm->show($id);
        $attributeResource = new DeviceAttribute();
        $attributes = $attributeResource->list();
        $deviceResource = new device();
        $devices = $deviceResource->getDevices($result);

        return view('applicationForm.editForm', ['result' => $result, 'attributes' => $attributes, 'devices' => $devices]);
    }

    public function update(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'applicationID' =>  [ 'required', 'integer' ],
            'deviceID' => [ 'required', 'integer' ],
            'estimated_pickup_time' => [ 'required' ],
            'estimated_return_time' => [ 'required' ],
            'target' => [ 'required', 'string' ]
        ]);
        $data['userID'] = Auth::user()->id;
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

        $check = $this->applicationForm->check($data);
        if ($check) {
            $messageData = [
                'type' => "danger",
                'message' => "該時段設備已被預借"
            ];
            return back()->with('messageData', [$messageData]);
        }

        $companionResource = new ApplicationCompanion();
        $companionResource->deleteAll($data['applicationID']);
        $this->applicationForm->update($data);

        if(!empty($companionList)) {
            $userResource = new User();
            foreach($companionList['companion'] as $row) {
                $userID = $userResource->getId($row);
                $user = [
                    'userID' => $userID['userID'],
                    'id' => $data['applicationID']
                ];
                $companionResource->store($user);
            }
        }

        return redirect()->route('applicationForm.applicationList');
    }

    public function cancel(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'applicationID' => [ 'required', 'integer' ],
            'result' => [ 'required', 'string' ]
        ]);
        $data['state'] = 4;

        $cancelResource = new CancelApplication();
        $cancelResource->store($data);
        $result = $this->applicationForm->changeState($data);

        echo($result);
    }

    public function updateReturnTime(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'applicationID' => [ 'required', 'integer' ],
            'extend_time' => [ 'required' ]
        ]);

        $details = $this->applicationForm->getCheckData($data['applicationID']);
        $details['extend_time'] = $data['extend_time'];
        $check = $this->applicationForm->check($details);
        if ($check) {
            $messageData = [
                'type' => "danger",
                'message' => $check
            ];
            return response()->json(['messageData' => $messageData]);
        }

        $this->applicationForm->extendTime($data);
        return redirect()->route('applicationForm.applicationList');
    }
}
