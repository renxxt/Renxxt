<?php

namespace App\Http\Resources;

use App\Models\ReturnForm;

class ReturnFormResource
{
    public function list($id)
    {
        $result = ReturnForm::where('attributeID', $id)
                    ->leftjoin('questions AS Q', 'Q.questionID', '=', 'returnform.questionID')
                    ->get();

        return $result;
    }

    public function answerList($attributeID, $id)
    {
        $result = ReturnForm::where('attributeID', $attributeID)
                    ->where('applicationID', $id)
                    ->leftjoin('questions AS Q', 'Q.questionID', '=', 'returnform.questionID')
                    ->leftjoin('returnformanswers AS P', 'P.questionID', '=', 'Q.questionID')
                    ->get();

        return $result;
    }

    public function store($data)
    {
        $result = ReturnForm::insert([
                    'attributeID' => $data['attributeID'],
                    'questionID' => $data['questionID'],
                    'order' => $data['order'],
                    'required' => $data['required']
        ]);

        return $result;
    }

    public function show($id)
    {
        $result = ReturnForm::where('attributeID', $id)
                    ->leftjoin('questions as q', 'q.questionID', '=', 'returnform.questionID')
                    ->orderBy('order')
                    ->get();

        return $result;
    }

    public function delete($id)
    {
        $result = ReturnForm::where('attributeID', $id)->delete();

        return $result;
    }
}
