<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use App\Http\Resources\ReturnFormResource AS ReturnForm;
use App\Http\Resources\ApplicationFormResource AS ApplicationForm;
use App\Http\Resources\ReturnFormAnswerResource AS ReturnFormAnswer;

class ReturnFormController extends Controller
{
    protected $lib;
    protected $returnForm;

    public function __construct(Lib $lib, ReturnForm $returnForm)
    {
        $this->lib = $lib;
        $this->returnForm = $returnForm;
    }

    public function list(Request $request)
    {
        $data = $request->validate([
            'attributeID' => [
                'required',
                'integer'
            ]
        ]);

        $returnData = $this->returnForm->list($data['attributeID']);
        return $returnData;
    }

    public function show($id)
    {
        $resource = new ApplicationForm();
        $data = $resource->getAttributeID($id);
        if ($data['return_form'] == 0) {
            $data['state'] = 3;
            $data['applicationID'] = $id;
            $resource->changeState($data);

            return redirect()->route('applicationForm.completedList');
        }
        $result = $this->returnForm->show($data['attributeID']);

        return view('applicationForm.form', ['applicationID' => $id, 'state' => $data['state'], 'result' => $result]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'applicationID' => [ 'required', 'integer' ]
        ]);
        $data['state'] = 3;

        $list = $request->validate([
            'questions' => [ 'required', 'array' ],
            'question.*.applicationID' => [ 'required', 'integer' ],
            'question.*.questionID' => [ 'required', 'integer' ],
            'question.*.answer_text' => [ 'required', 'string' ]
        ]);

        $resource = new ReturnFormAnswer();
        foreach($list AS $row) {
            $result = $resource->store($row);
        }
        $formResource = new ApplicationForm();
        $formResource->changeState($data);

        return redirect()->route('applicationForm.completedList');
    }
}
