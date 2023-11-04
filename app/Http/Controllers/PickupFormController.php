<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use App\Http\Resources\PickupFormResource AS PickupForm;
use App\Http\Resources\ApplicationFormResource AS ApplicationForm;
use App\Http\Resources\PickupFormAnswerResource AS PickupFormAnswer;

class PickupFormController extends Controller
{
    protected $lib;
    protected $pickupForm;

    public function __construct(Lib $lib, PickupForm $pickupForm)
    {
        $this->lib = $lib;
        $this->pickupForm = $pickupForm;
    }

    public function list(Request $request)
    {
        $data = $request->validate([
            'attributeID' => [
                'required',
                'integer'
            ]
        ]);

        $pickupData = $this->pickupForm->list($data['attributeID']);
        return $pickupData;
    }

    public function answerList(Request $request)
    {
        $data = $request->validate([
            'applicationID' => [
                'required',
                'integer'
            ]
        ]);

        $resource = new ApplicationForm();
        $attribute = $resource->getAttributeID($data['applicationID']);
        $answerData =$this->pickupForm->answerList($attribute['attributeID']);
        return $answerData;
    }

    public function show($id)
    {
        $access = $this->lib->userAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $resource = new ApplicationForm();
        $data = $resource->getAttributeID($id);
        if ($data['pickup_form'] == 0) {
            $data['state'] = 2;
            $data['applicationID'] = $id;
            $resource->changeState($data);

            return redirect()->route('applicationForm.applicationList');
        }
        $result = $this->pickupForm->show($data['attributeID']);
        if (!$result) {
            $messageData = [
                'type' => "danger",
                'message' => "無該取用表單"
            ];
            return redirect()->route('applicationForm.applicationList')->with('messageData', [$messageData]);
        }

        return view('applicationForm.form', ['applicationID' => $id, 'state' => $data['state'], 'result' => $result]);
    }

    public function store(Request $request)
    {
        $access = $this->lib->userAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'applicationID' => [ 'required', 'integer' ]
        ]);
        $data['state'] = 2;

        $list = $request->validate([
            'questions' => [ 'required', 'array' ],
            'question.*.applicationID' => [ 'required', 'integer' ],
            'question.*.questionID' => [ 'required', 'integer' ],
            'question.*.answer_text' => [ 'required', 'string' ]
        ]);

        $resource = new PickupFormAnswer();
        foreach($list AS $row) {
            $result = $resource->store($row);
        }
        $formResource = new ApplicationForm();
        $formResource->changeState($data);

        return redirect()->route('applicationForm.applicationList');
    }
}
